<?php

namespace App\Http\Controllers\deliveryBoy;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Razorpay\Api\Api;
use Exception;
use App\Models\UserPayment;
use Illuminate\Support\Facades\Validator;
use App\Models\FranchiseBag;
use App\Http\Controllers\franchise\RateCalculator;
use App\Models\DeliveryBoyCommissionDetail;
use App\Models\DeliveryBoy;
use App\Notifications\SMSNotification;
use App\Http\Controllers\deliveryBoy\BagController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail as MailFacade;
use Carbon\Carbon;
use DB;
use App\Mail\deliveredMail;
use App\Models\GotogoSpeedPostParcel;
use App\Models\GotogoSuperFastParcel;
use App\Models\GotogoBusinessParcel;
use App\Models\GotogoRegisteredParcel;
use App\Models\IndiaPostSpeedPostParcel;
use App\Models\IndiaPostBusinessParcel;
use App\Models\IndiaPostRegisteredParcel;


class ParcelPaymentController extends Controller
{

    public function index()
    {
        try {
            return view('deliveryBoy.parcel-payment.index');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Unable to create Razorpay order: ' . $e->getMessage());
        }
    }



    public function store(Request $request, RateCalculator $rateCalculator, BagController $bagController)
    {

        // Validate request data
        $validator = Validator::make($request->all(), [
            'service_type' => 'required',
            'barcode_no' => 'required',
            'razorpay_payment_id' => 'required',
            'final_amount' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 400);
        }

        $service_type = $request->service_type;
        $barcode_no = $request->barcode_no;
        $delivery_boy = Auth::guard('delboy')->user();
        $Model = FranchiseBag::getServiceModel($service_type);
        $parcel = $Model::where('barcode_no', $request->barcode_no)->first();


        // Initialize Razorpay API
        $api = new Api(env('RAZORPAY_KEY'), env('RAZORPAY_SECRET'));

        DB::beginTransaction();
        try {
            // Create payment record
            $paymentRecord = UserPayment::create([
                'barcode_no' => $barcode_no,
                'service_type' => $service_type,
                'name' => $parcel->consignee_name,
                'phone' => $parcel->consignee_mobile,
                'email' => $parcel->consignee_email,
                'address' => $parcel->consignee_address,
                'payment_method' => 'cod',
                'razorpay_payment_id' => $request->razorpay_payment_id,
                'amount' => $request->final_amount,
                'status' => 'pending',
                'method' => 'razorpay',
            ]);

            // Fetch payment details from Razorpay
            $payment = $api->payment->fetch($request->razorpay_payment_id);

            // Attempt to capture the payment
            $response = $payment->capture([
                'amount' => $payment['amount'],
                'currency' => 'INR',
            ]);

            // Update the payment record based on the response from Razorpay
            $paymentRecord->updateOrFail([
                'status' => $response['status'] === 'captured' ? 'completed' : 'failed',
            ]);

            DB::commit();



            $commission = $rateCalculator->calculateCommissionForGotogoPost($parcel->package_weight, 'delivery', $service_type);
            DeliveryBoyCommissionDetail::create([
                "delivery_boy_id" => $delivery_boy->id,
                "service_type" => $request->service_type,
                "amount" => $parcel->payment_amount,
                "payment_method" => $parcel->payment_method,
                "commission_type" => "delivery",
                "commission" => $commission,
            ]);

            // ✅ Update Tracking & Delivery Details
            $TrackingModel = FranchiseBag::getTrackingModel($service_type);
            $trackingModalToUpdate =  $TrackingModel::where('barcode_no', $request->barcode_no)->first();
            $trackingModalToUpdate->delivery_datetime = now();
            $trackingModalToUpdate->delivery_location = $parcel->consignee_address;
            $trackingModalToUpdate->save();

            $parcel->delivered_date = Carbon::today()->toDateString();
            $parcel->delivered_by = $delivery_boy->id;
            $parcel->save();

            // ✅ Send Notifications
            $notification = new SMSNotification($parcel->pickup_mobile, 'DELIVERED', [$parcel->barcode_no, $delivery_boy->name, now()]);
            $notification->sendMessage();

            $notification = new SMSNotification($parcel->consignee_mobile, 'DELIVERED', [$parcel->barcode_no, $delivery_boy->name, now()]);
            $notification->sendMessage();

            MailFacade::to($parcel->pickup_email)->send(new deliveredMail([$parcel]));
            //  MailFacade::to('fuloriadeepak999@gmail.com')->send(new deliveredMail([$parcel]));

            $bagController->sendNotificationToUser($parcel, $service_type);

            return response()->json([
                'success' => true,
                'message' => 'Payment processed successfully!',
                'data' => $paymentRecord,
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Payment processing failed: ' . $e->getMessage(),
            ], 500);
        }
    }


    public function commissionDetail(Request $request, RateCalculator $rateCalculator)
    {

        $delivery_boy_id = Auth::guard('delboy')->user()->id;
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $commission_type = $request->input('commission_type');

        $data = collect();

        if ($startDate && $endDate) {
            $startDate = \Carbon\Carbon::parse($startDate)->startOfDay();
            $endDate = \Carbon\Carbon::parse($endDate)->endOfDay();
            $data = DeliveryBoyCommissionDetail::where('delivery_boy_id', $delivery_boy_id)
                ->where('commission_type', $commission_type)
                ->selectRaw('
                delivery_boy_id, 
                service_type, 
                SUM(amount) as total_amount, 
                SUM(commission) as total_commission,
                SUM(CASE WHEN LOWER(payment_method) = "prepaid" THEN amount ELSE 0 END) as prepaid_amount,
                SUM(CASE WHEN LOWER(payment_method) = "cod" THEN amount ELSE 0 END) as cod_amount,
                SUM(CASE WHEN LOWER(payment_method) = "prepaid" THEN commission ELSE 0 END) as prepaid_commission,
                SUM(CASE WHEN LOWER(payment_method) = "cod" THEN commission ELSE 0 END) as cod_commission
            ')
                ->whereBetween('created_at', [$startDate, $endDate])
                ->groupBy('service_type')
                ->get();
        } else {

            $today = Carbon::today();
            $data = DeliveryBoyCommissionDetail::where('delivery_boy_id', $delivery_boy_id)
                ->where('commission_type', $commission_type)
                ->selectRaw('
                    delivery_boy_id, 
                    service_type, 
                    SUM(amount) as total_amount, 
                    SUM(commission) as total_commission,
                    SUM(CASE WHEN LOWER(payment_method) = "prepaid" THEN amount ELSE 0 END) as prepaid_amount,
                    SUM(CASE WHEN LOWER(payment_method) = "cod" THEN amount ELSE 0 END) as cod_amount,
                    SUM(CASE WHEN LOWER(payment_method) = "prepaid" THEN commission ELSE 0 END) as prepaid_commission,
                    SUM(CASE WHEN LOWER(payment_method) = "cod" THEN commission ELSE 0 END) as cod_commission
                ')
                ->whereDate('created_at', $today)
                ->groupBy('service_type')
                ->get();
        }

        // Ensure all service types are present
        $allServiceTypes = [1, 3, 4, 5, 6, 9];
        $dataMap = $data->keyBy('service_type');

        foreach ($allServiceTypes as $serviceType) {
            if (!isset($dataMap[$serviceType])) {
                $data->push((object)[
                    'delivery_boy_id' => $delivery_boy_id,
                    'service_type' => $serviceType,
                    'total_amount' => "0",
                    'total_commission' => "0",
                    'prepaid_amount' => "0",
                    'cod_amount' => "0",
                    'prepaid_commission' => "0",
                    'cod_commission' => "0",
                    'service_name' => GotogoSpeedPostParcel::getServiceType($serviceType),
                ]);
            }
        }

        // Assign service names
        foreach ($data as $entry) {
            $entry->service_name = GotogoSpeedPostParcel::getServiceType($entry->service_type);
        }

        // Split data into Gotogo and India Post
        $gotogoCommission = [];
        $indiaPostCommission = [];
        $totalGotogoCommission = 0;
        $totalIndiaPostCommission = 0;

        foreach ($data as $entry) {
            if ($entry->service_type == 5 || $entry->service_type == 6) {
                $indiaPostCommission[] = $entry;
                $totalIndiaPostCommission += (float) $entry->total_commission;
            } else {
                $gotogoCommission[] = $entry;
                $totalGotogoCommission += (float) $entry->total_commission;
            }
        }



        $franchiseDetails = DeliveryBoy::findOrFail($delivery_boy_id);

        return view('deliveryBoy.commission.index', compact(
            'gotogoCommission',
            'indiaPostCommission',
            'totalGotogoCommission',
            'totalIndiaPostCommission',
            'franchiseDetails'
        ));
    }

    public function printCommissionDetail(Request $request, RateCalculator $rateCalculator)
    {
        $data = collect();
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $commission_type = $request->input('commission_type');
        $delivery_boy_id = Auth::guard('delboy')->user()->id;

        if ($startDate && $endDate) {
            $startDate = \Carbon\Carbon::parse($startDate)->startOfDay();
            $endDate = \Carbon\Carbon::parse($endDate)->endOfDay();
            $data = DeliveryBoyCommissionDetail::where('delivery_boy_id', $delivery_boy_id)
                ->where('commission_type', $commission_type)
                ->selectRaw('
                    delivery_boy_id, 
                    service_type, 
                    SUM(amount) as total_amount, 
                    SUM(commission) as total_commission,
                    SUM(CASE WHEN LOWER(payment_method) = "prepaid" THEN amount ELSE 0 END) as prepaid_amount,
                    SUM(CASE WHEN LOWER(payment_method) = "cod" THEN amount ELSE 0 END) as cod_amount,
                    SUM(CASE WHEN LOWER(payment_method) = "prepaid" THEN commission ELSE 0 END) as prepaid_commission,
                    SUM(CASE WHEN LOWER(payment_method) = "cod" THEN commission ELSE 0 END) as cod_commission
                ')
                ->whereBetween('created_at', [$startDate, $endDate])
                ->groupBy('service_type')
                ->get();
        } else {
            $today = Carbon::today();
            $data = DeliveryBoyCommissionDetail::where('delivery_boy_id', $delivery_boy_id)
                ->where('commission_type', $commission_type)
                ->selectRaw('
                    delivery_boy_id, 
                    service_type, 
                    SUM(amount) as total_amount, 
                    SUM(commission) as total_commission,
                    SUM(CASE WHEN LOWER(payment_method) = "prepaid" THEN amount ELSE 0 END) as prepaid_amount,
                    SUM(CASE WHEN LOWER(payment_method) = "cod" THEN amount ELSE 0 END) as cod_amount,
                    SUM(CASE WHEN LOWER(payment_method) = "prepaid" THEN commission ELSE 0 END) as prepaid_commission,
                    SUM(CASE WHEN LOWER(payment_method) = "cod" THEN commission ELSE 0 END) as cod_commission
                ')
                ->whereDate('created_at', $today)
                ->groupBy('service_type')
                ->get();
        }

        $allServiceTypes = [1, 3, 4, 5, 6, 9];
        $dataMap = $data->keyBy('service_type');

        foreach ($allServiceTypes as $serviceType) {
            if (!isset($dataMap[$serviceType])) {
                $data->push((object)[
                    'delivery_boy_id' => $delivery_boy_id,
                    'service_type' => $serviceType,
                    'total_amount' => "0",
                    'total_commission' => "0",
                    'prepaid_amount' => "0",
                    'cod_amount' => "0",
                    'prepaid_commission' => "0",
                    'cod_commission' => "0",
                    'service_name' => GotogoSpeedPostParcel::getServiceType($serviceType),
                ]);
            }
        }

        foreach ($data as $entry) {
            $entry->service_name = GotogoSpeedPostParcel::getServiceType($entry->service_type);
        }

        $gotogoCommission = [];
        $indiaPostCommission = [];
        $totalGotogoCommission = 0;
        $totalIndiaPostCommission = 0;

        foreach ($data as &$entry) {
            if (in_array($entry->service_type, [5, 6])) {
                $indiaPostCommission[] = $entry;
                $totalIndiaPostCommission += (float) $entry->total_commission;
            } else {
                $gotogoCommission[] = $entry;
                $totalGotogoCommission += (float) $entry->total_commission;
            }
        }

        $gstRate = 18;
        $tdsRate = 5;

        $gstGotogo = ($totalGotogoCommission * $gstRate) / 100;
        $tdsGotogo = ($totalGotogoCommission * $tdsRate) / 100;
        $totalGotogo = $totalGotogoCommission + $gstGotogo - $tdsGotogo;

        $gstIndiaPost = ($totalIndiaPostCommission * $gstRate) / 100;
        $tdsIndiaPost = ($totalIndiaPostCommission * $tdsRate) / 100;
        $totalIndiaPost = $totalIndiaPostCommission + $gstIndiaPost - $tdsIndiaPost;

        $franchiseDetails = DeliveryBoy::findOrFail($delivery_boy_id);

        // Same data pass for printing
        $otherPageContent = View('deliveryBoy.commission.printCommissionDetail', compact(
            'gotogoCommission',
            'indiaPostCommission',
            'totalGotogoCommission',
            'totalIndiaPostCommission',
            'totalGotogo',
            'totalIndiaPost',
            'gstGotogo',
            'gstIndiaPost',
            'tdsGotogo',
            'tdsIndiaPost',
            'franchiseDetails'
        ))->render();

        return response()->json([
            'otherPageContent' => $otherPageContent
        ]);
    }
}
