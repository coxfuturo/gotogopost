<?php

namespace App\Http\Controllers\api\user;

use App\Http\Controllers\Controller;
use App\Models\GotogoSpeedPostParcel;
use App\Models\GotogoBusinessParcel;
use App\Models\GotogoRegisteredParcel;
use App\Models\IndiaPostSpeedPostParcel;
use App\Models\IndiaPostBusinessParcel;

use App\Models\PickupDetails;
use App\Models\Franchise;
use App\Models\UserNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\FranchiseBag;
use Illuminate\Support\Facades\File;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\StreamedResponse;
use GuzzleHttp\Client;
use Picqer\Barcode\BarcodeGeneratorPNG;
use DB;
use App\Models\FranchiseBarcodeSeries;
use App\Models\FranchiseBarcodes;
use Illuminate\Support\Facades\View;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use  App\Http\Controllers\franchise\RateCalculator;
use App\Models\UserPayment;
use Razorpay\Api\Api;


class DashboardController extends Controller

{
    public function userNotification(Request $request)
    {

        $date = $request->input('date');

        $formattedDate = $date ? Carbon::parse($date)->format('Y-m-d') : Carbon::today()->format('Y-m-d');
        $id = Auth::guard('apiuser')->user()->id;
        $get_notification = UserNotification::where('user_id', $id)
            //->whereDate('created_at', $formattedDate)
            ->orderBy('created_at', 'desc')
            ->get();

        return $get_notification;
    }


    public function trackOrder(Request $request)
    {
        $trackOrder = $request->input('trackOrder');

        // Get Tracking and Booking Models (These should return class names, not objects)
        $TrackingModel = FranchiseBag::getTrackingModelByBarcode($trackOrder);
        $BookingModel = FranchiseBag::getBookingModelByBarcode($trackOrder);
        $service_type_no = FranchiseBag::getServiceTypeFromModel($BookingModel);
        $service_type = FranchiseBag::getServiceType($service_type_no);

        // Check if model class names are valid
        if (!class_exists($TrackingModel) || !class_exists($BookingModel)) {
            return response()->json([
                'message' => 'Invalid barcode or model not found.'
            ], 400);
        }

        // Fetch tracking and booking details
        $trackingDetails = $TrackingModel::where('barcode_no', $trackOrder)->first();
        $bookingDetails = $BookingModel::where('barcode_no', $trackOrder)->first();

        // Check if any data is found
        if (!$trackingDetails && !$bookingDetails) {
            if (!class_exists($TrackingModel) || !class_exists($BookingModel)) {
                return response()->json([
                    'message' => 'Invalid barcode or model not found.'
                ], 400);
            }
        }

        return response()->json([
            'message' => 'Tracking details found',
            'service_type' => $service_type,
            'tracking' => $trackingDetails,
            'booking' => $bookingDetails,
        ], 200);
    }



    public function parcel(Request $request)
    {
        $user = Auth::guard('apiuser')->user();

        $parcels = collect();

        $models = [
            ['model' => GotogoSpeedPostParcel::class, 'service_type' => \App\Models\Admin::GOTOGO_POST_SPEED],
            ['model' => GotogoBusinessParcel::class, 'service_type' => \App\Models\Admin::GOTOGO_POST_BUSINESS],
            ['model' => GotogoRegisteredParcel::class, 'service_type' => \App\Models\Admin::GOTOGO_POST_REGISTERED],
            ['model' => IndiaPostSpeedPostParcel::class, 'service_type' => \App\Models\Admin::INDIA_POST_SPEED],
            ['model' => IndiaPostBusinessParcel::class, 'service_type' => \App\Models\Admin::INDIA_POST_BUSINESS],
        ];

        foreach ($models as $entry) {
            $records = $entry['model']::where('pickup_mobile', $user->phone)
                ->orderBy('created_at', 'desc')
                ->get()
                ->map(function ($parcel) use ($entry) {
                    return [
                        'id' => $parcel->id,
                        'pickup_name' => $parcel->pickup_name,
                        'pickup_mobile' => $parcel->pickup_mobile,
                        'pickup_address' => $parcel->pickup_address,
                        'pickup_pincode' => $parcel->pickup_pincode,
                        'pickup_email' => $parcel->pickup_email,
                        'pickup_city' => $parcel->pickup_city,
                        'pickup_state' => $parcel->pickup_state,
                        'consignee_name' => $parcel->consignee_name,
                        'consignee_mobile' => $parcel->consignee_mobile,
                        'consignee_address' => $parcel->consignee_address,
                        'consignee_pincode' => $parcel->consignee_pincode,
                        'consignee_email' => $parcel->consignee_email,
                        'consignee_city' => $parcel->consignee_city,
                        'consignee_state' => $parcel->consignee_state,
                        'package_weight' => $parcel->package_weight,
                        'payment_method' => $parcel->payment_method,
                        'payment_amount' => $parcel->payment_amount,
                        'barcode_no' => $parcel->barcode_no,
                        'barcode_image_src' => $parcel->barcode_image_src,
                        'delivered_by' => $parcel->delivered_by ?? null,
                        'signature' => $parcel->signature ?? null,
                        'delivered_date' => $parcel->delivered_date
                            ? Carbon::parse($parcel->delivered_date)->format('d-m-Y')
                            : null,
                        'created_at' => $parcel->created_at
                            ? Carbon::parse($parcel->created_at)->format('d-m-Y')
                            : null,
                        'service_type' => $entry['service_type'],
                    ];
                });

            $parcels = $parcels->merge($records);
        }

        return response()->json([
            'message' => 'parcels found',
            'parcels' => $parcels->values(),
        ], 200);
    }


    public function paymentStore(Request $request, RateCalculator $rateCalculator)
    {

        // Validate request data
        $validator = Validator::make($request->all(), [
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


        $user = Auth::guard('apiuser')->user();

        // Initialize Razorpay API
        $api = new Api(env('RAZORPAY_KEY'), env('RAZORPAY_SECRET'));

        DB::beginTransaction();
        try {
            // Create payment record
            $paymentRecord = UserPayment::create([
                // 'barcode_no' => $barcode_no,
                'service_type' => $request->service_type,
                'name' => $user->name,
                'phone' => $user->phone,
                'email' => $user->email,
                'address' => $user->address,
                'razorpay_payment_id' => $request->razorpay_payment_id,
                'amount' => intval($request->final_amount),
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


    public function pickupCharge(Request $request, RateCalculator $rateCalculator)
    {


        $pickup_address = $request->pickup_address;
        $franchise_address = $request->franchise_address;
        $pickupCharge = $rateCalculator->calculatePickupCommissionForGotogoPost($franchise_address, $pickup_address);

        return response()->json([
            'success' => true,
            'pickupCharge' => $pickupCharge,
            'message' => 'Data retrieved successfully',
        ], 200);
    }
}
