<?php



namespace App\Http\Controllers\franchise;


use App\Http\Controllers\Controller;

use App\Models\GotogoRegisteredParcel;

use App\Models\PickupDetails;
use App\Models\GotogoLink;
use App\Models\Franchise;
use App\Models\FranchiseCommissionDetail;
use App\Models\GotogoRegisteredTrackOrder;
use App\Models\MManager;
use App\Models\MManagerKyc;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\Hash;

use Illuminate\Support\Facades\File;

use PhpOffice\PhpSpreadsheet\IOFactory;

use PhpOffice\PhpSpreadsheet\Spreadsheet;

use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

use Symfony\Component\HttpFoundation\StreamedResponse;

use GuzzleHttp\Client;

use Picqer\Barcode\BarcodeGeneratorPNG;


use App\Models\FranchiseBarcodeSeries;

use App\Models\FranchiseBarcodes;

use Illuminate\Support\Facades\View;

use Carbon\Carbon;

use PDF;

use Illuminate\Support\Facades\Mail;

use App\Mail\ParcelMail;

use  App\Http\Controllers\franchise\RateCalculator;
use App\Models\ManagerCommissionDetail;
use App\Notifications\FranchisePushNotification;
use App\Models\FranchiseBag;
use App\Models\User;
use App\Models\NoRegisterCustomer;
use Config;
use App\Notifications\SMSNotification;
use Illuminate\Support\Facades\Log;



class GotogoRegisteredController extends Controller

{


    public function __construct()
    {
        $this->middleware(function ($request, $next) {

            if (Auth::guard('franchise')->check()) {
                $user = Auth::guard('franchise')->user();
            } elseif (Auth::guard('franchiseRoleUser')->check()) {
                $user = Auth::guard('franchiseRoleUser')->user();
            } else {
                return abort(403, 'Unauthorized.');
            }

            $action = $request->route()->getActionMethod();

            $actionToPermissionMap = [
                'index' => 'Bookings-view',
                'create' => 'Bookings-create',
                'store' => 'Bookings-create',
                'edit' => 'Bookings-edit',
                'delete' => 'Bookings-delete',
            ];

            if (!array_key_exists($action, $actionToPermissionMap)) {
                return $next($request);
            }

            if (array_key_exists($action, $actionToPermissionMap)) {
                $requiredPermission = $actionToPermissionMap[$action];

                // if (!$user->hasPermissionTo($requiredPermission, 'franchise')) {

                //     abort(403, 'You do not have permission to perform this action.');
                // }
            }
            return $next($request);
        });


        $this->middleware(function ($request, $next) {
            $serviceStatuses = Franchise::checkServiceStatus(GotogoRegisteredParcel::SERVICE_TYPE_GOTO_POST_REGISTERED);

            // Check if at least one service status is set to 1
            if (!$serviceStatuses) {
                return abort(403, 'Service not available.');
            }

            return $next($request);
        });
    }


    public function getUniqueCode()

    {

        $serviceType = GotogoRegisteredParcel::SERVICE_TYPE_GOTO_POST_REGISTERED;

        $randomNumber = rand(1, 9);

        $serviceTypeValue = GotogoRegisteredParcel::getServiceTypeDB($serviceType);

        $range_start_column = "parcel_barcode_range_start_{$serviceTypeValue}";

        $range_end_column = "parcel_barcode_range_end_{$serviceTypeValue}";

        $last_code_issued_column = "last_parcel_code_issued_{$serviceTypeValue}";



        $franchiseId = Franchise::getFranchiseId();

        $franchiseSeriesDetails = FranchiseBarcodeSeries::where("franchise_id", $franchiseId)->first();


        if ($franchiseSeriesDetails) {

            if ($franchiseSeriesDetails->{$range_end_column} != null && $franchiseSeriesDetails->{$range_end_column} > $franchiseSeriesDetails->{$last_code_issued_column}) {

                if ($franchiseSeriesDetails->{$last_code_issued_column}) {

                    $last_parcel_code_issued = $franchiseSeriesDetails->{$last_code_issued_column};

                    $seriesNum = str_pad($last_parcel_code_issued + 1, 8, '0', STR_PAD_LEFT);
                } else {

                    $seriesNum = str_pad($franchiseSeriesDetails->{$range_start_column}, 8, '0', STR_PAD_LEFT);
                }

                $serviceCode = FranchiseBarcodeSeries::getServiceCode($serviceType);

                $code = FranchiseBarcodeSeries::$PARCELCODE . $serviceCode . $seriesNum . $randomNumber . 'ND';

                return $code;
            } else {

                return 'Barcode series end';
            }
        } else {

            return 'Barcodes not assigned';
        }
    }

    public function getBarcodeAvailableCount()
    {
        $serviceType = GotogoRegisteredParcel::SERVICE_TYPE_GOTO_POST_REGISTERED;
        $serviceTypeValue = GotogoRegisteredParcel::getServiceTypeDB($serviceType);

        $range_start_column = "parcel_barcode_range_start_{$serviceTypeValue}";
        $range_end_column = "parcel_barcode_range_end_{$serviceTypeValue}";
        $last_code_issued_column = "last_parcel_code_issued_{$serviceTypeValue}";

        $franchiseId = Franchise::getFranchiseId();
        $franchiseSeriesDetails = FranchiseBarcodeSeries::where("franchise_id", $franchiseId)->first();

        if (!$franchiseSeriesDetails || $franchiseSeriesDetails->{$range_end_column} === null) {
            return 0; // No barcodes available
        }

        $start = (int) $franchiseSeriesDetails->{$range_start_column};
        $end = (int) $franchiseSeriesDetails->{$range_end_column};
        $lastIssued = (int) ($franchiseSeriesDetails->{$last_code_issued_column} ?? $start - 1);

        // Calculate available barcodes
        return max(0, ($end - $lastIssued));
    }


    public function index(Request $request, RateCalculator $rateCalculator)

    {
        $totalParcels = 0;
        $totalAmount = 0;
        $searchKey = $request->input('searchKey');

         $fromdate = $request->input('fromdate');
        $todate = $request->input('todate');

        $query = GotogoRegisteredParcel::where('franchise_id', Franchise::getFranchiseId())

            // ->where('insert_type', $request->insert_type)

            ->orderBy('created_at', 'desc');

        if ($searchKey) {

            $query->where(function ($q) use ($searchKey) {

                $q->where('pickup_name', 'LIKE', "%{$searchKey}%")

                    ->orWhere('pickup_mobile', 'LIKE', "%{$searchKey}%")

                    ->orWhere('pickup_email', 'LIKE', "%{$searchKey}%")

                    ->orWhere('pickup_pincode', 'LIKE', "%{$searchKey}%")

                    ->orWhere('pickup_city', 'LIKE', "%{$searchKey}%")

                    ->orWhere('pickup_state', 'LIKE', "%{$searchKey}%")

                    ->orWhere('pickup_address', 'LIKE', "%{$searchKey}%")

                    ->orWhere('consignee_name', 'LIKE', "%{$searchKey}%")

                    ->orWhere('consignee_mobile', 'LIKE', "%{$searchKey}%")

                    ->orWhere('consignee_email', 'LIKE', "%{$searchKey}%")

                    ->orWhere('consignee_pincode', 'LIKE', "%{$searchKey}%")

                    ->orWhere('consignee_city', 'LIKE', "%{$searchKey}%")

                    ->orWhere('consignee_state', 'LIKE', "%{$searchKey}%")

                    ->orWhere('consignee_address', 'LIKE', "%{$searchKey}%")

                    ->orWhere('barcode_no', 'LIKE', "%{$searchKey}%");
            });

            if (!empty($fromdate) || !empty($todate)) {
                if (!empty($fromdate) && !empty($todate)) {
                    $fromdate = Carbon::createFromFormat("d-m-Y", $fromdate)
                        ->startOfDay()
                        ->toDateString();
                    $todate = Carbon::createFromFormat("d-m-Y", $todate)
                        ->startOfDay()
                        ->toDateString();
                    $query->whereBetween("created_at", [$fromdate, $todate]);
                } elseif (!empty($fromdate)) {
                    // सिर्फ fromdate मिला
                    $fromdate = Carbon::createFromFormat(
                        "d-m-Y",
                        $fromdate
                    )->toDateString();
                    $query->whereDate("created_at", $fromdate);
                } elseif (!empty($todate)) {
                    // सिर्फ todate मिला
                    $todate = Carbon::createFromFormat(
                        "d-m-Y",
                        $todate
                    )->toDateString();
                    $query->whereDate("created_at", $todate);
                }
            }

            $datas = $query->get();
            $totalAmount = (clone $query)->sum('payment_amount');
            $totalParcels = (clone $query)->count();
            return view('franchise.gotogoRegistered.index', compact('datas','totalAmount','totalParcels'));
        }

        if (!empty($fromdate) || !empty($todate)) {
            
            if (!empty($fromdate) && !empty($todate)) {
                $fromdate = Carbon::createFromFormat('d-m-Y', $fromdate)->startOfDay()->toDateString();
                $todate = Carbon::createFromFormat('d-m-Y', $todate)->startOfDay()->toDateString();
                $query->whereBetween('created_at', [$fromdate, $todate]);
            } elseif (!empty($fromdate)) {
            // सिर्फ fromdate मिला
              $fromdate = Carbon::createFromFormat('d-m-Y', $fromdate)->toDateString();
              $query->whereDate('created_at', $fromdate);
              } elseif (!empty($todate)) {
             // सिर्फ todate मिला
              $todate = Carbon::createFromFormat('d-m-Y', $todate)->toDateString();
              $query->whereDate('created_at', $todate);
             }
            $datas = $query->get();
            $totalAmount = (clone $query)->sum('payment_amount');
            $totalParcels = (clone $query)->count();
            return view('franchise.gotogoRegistered.index', compact('datas','totalAmount','totalParcels'));
        } else {

            $query->whereDate('created_at', Carbon::today());
            $datas = $query->get();
            $totalAmount = (clone $query)->sum('payment_amount');
            $totalParcels = (clone $query)->count();

            return view('franchise.gotogoRegistered.index', compact('datas','totalAmount','totalParcels'));
        }
    }


    public function getPrice($origin, $destination, $weight, $fuel_charge = 0, $pickup_charge = 0, $other_service_charge = 0)

    {
        $newrequest = new Request([
            'originPincode' => $origin,
            'destinationPincode' => $destination,
            'packageWeight' => $weight,
            'fuel_charge' => $fuel_charge,
            'pickup_charge' => $pickup_charge,
            'other_service_charge' => $other_service_charge,
            'service_type' => GotogoRegisteredParcel::SERVICE_TYPE_GOTO_POST_REGISTERED,
        ]);
        $rateCalculator = new RateCalculator();
        $result = $rateCalculator->calculate($newrequest);
        return $result;
    }


    public function showPrice(Request $request, RateCalculator $rateCalculater)

    {

        if ($request->isMethod('post')) {

            try {

                if ($request->amount) {
                    $result = $rateCalculater->calculateByAmount($request);
                    return response()->json(['status' => 'success', 'data' => $result, 'message' => "data fetched successfully"]);
                } else {

                    $from = $request->from;
                    $to = $request->to;
                    $weight = $request->weight;
                    $fuel_charge = $request->fuel_charge ?? 0;
                    $pickup_charge = $request->pickup_charge ?? 0;
                    $other_service_charge = $request->other_service_charge ?? 0;
                    $result = $this->getPrice($from, $to, $weight, $fuel_charge, $pickup_charge, $other_service_charge);

                    return response()->json(['status' => 'success', 'data' => $result, 'message' => "data fetched successfully"]);
                }
            } catch (\Throwable $th) {

                return response()->json(['status' => 'error', 'message' => $th->getMessage()], 500);
            }
        }



        return response()->json(['status' => 'error', 'message' => 'Invalid request method'], 400);
    }



    public function create()

    {

        $franchise_details = Franchise::where('id', Franchise::getFranchiseId())->select('franchise_no', 'credit_balance', 'gotogo_balance', 'gst_number')->first();
        $linkDetail = GotogoLink::where('franchise_no', $franchise_details->franchise_no)->first();
        $pickupDetails = PickupDetails::where('franchise_id', Franchise::getFranchiseId())->where('status', 0)->get();
        $barcodeAvailable = $this->getBarcodeAvailableCount();
        return view('franchise.gotogoRegistered.create', [
            'pickupDetails' => $pickupDetails,
            'franchise_details' => $franchise_details,
            'linkDetails' => $linkDetail,
            'barcodeAvailable' => $barcodeAvailable
        ]);
    }


    public function savePdf($parcel)
    {
        $generator = new BarcodeGeneratorPNG();
        $code = $parcel->barcode_no;
        $barcode = $generator->getBarcode($code, $generator::TYPE_CODE_128);
        $barcode = base64_encode($barcode);

        $fuel_charge = $parcel->fuel_charge;
        $pickup_charge = $parcel->pickup_charge;
        $other_service_charge = $parcel->other_service_charge;
        $total_payment_amount = $parcel->payment_amount;
        $net_price = $total_payment_amount / 1.18;
        $amount = $net_price - ($fuel_charge + $pickup_charge + $other_service_charge);
        $gst = number_format($net_price * 0.18, 2, '.', '');
        $net_price_formatted = number_format($net_price, 2, '.', '');

        $rateDetails = [
            'fuel_charge' => $fuel_charge,
            'pickup_charge' => $pickup_charge,
            'other_service_charge' => $other_service_charge,
            'total_payment_amount' => number_format($total_payment_amount, 2, '.', ''),
            'net_price' => $net_price_formatted,
            'amount' => number_format($amount, 2, '.', ''),
            'gst' => $gst,
        ];

        $pdf = PDF::loadView('franchise.mail.pdfview', ['parcel' => $parcel, 'barcode' => $barcode, 'rateDetails' => $rateDetails]);
        $destinationPath = public_path('tenancy/assets/franchise/Pdf/');

        $fileName = uniqid() . '_parcel_' . '.pdf';
        $filePath = $destinationPath . $fileName;
        $pdf->save($filePath);

        return $filePath;
    }



    public function sendNotificationToUser($franchise, $parcel)
    {
        try {
            $service_type = GotogoRegisteredParcel::SERVICE_TYPE_GOTO_POST_REGISTERED;
           $link = route('franchise.parcel.receipt.download', [$service_type, $parcel->id]);
            \Log::info("sendNotificationToUser function called", [
                'parcel_id' => $parcel->id ?? null,
                'franchise_id' => $franchise->id ?? null,
                'type' => $service_type,
            ]);

            \Log::info("Fetching user with phone: {$parcel->pickup_mobile}");

            $userPhone = $parcel->pickup_mobile;
            $user = User::where('phone', $userPhone)->first();

            if (!$user) {
                \Log::error("User not found for phone: {$userPhone}");
                return;
            }

            \Log::info("User found", ['user_id' => $user->id]);

            $token = $user->fcm_token;
            if ($token) {
                \Log::info("FCM token found", ['user_id' => $user->id, 'fcm_token' => $token]);

                $title = FranchiseBag::getServiceType(GotogoRegisteredParcel::SERVICE_TYPE_GOTO_POST_REGISTERED);
                $body = "✅ Order Placed: Article No. {$parcel->barcode_no}\n" .
                    "📅 Date: " . date('M d, Y, h:i A', strtotime($parcel->created_at)) . "\n" .
                    "📍 From: {$parcel->pickup_address}\n" .
                    "📍 To: {$parcel->consignee_address}\n" .
                    "💰 Amount: ₹{$parcel->payment_amount}\n" .
                    "🧾 Receipt: {$link}\n" .
                    "🚀 Your order is placed successfully and will be processed soon!";

                \Log::info("Preparing to send push notification", [
                    'title' => $title,
                    'body' => $body,
                    'user_id' => $user->id,
                    'parcel_id' => $parcel->id,
                    'link'     => $link
                ]);

                $notification = new FranchisePushNotification($token, $parcel, $title, $body);
                $notification->sendPushNotification();

                \Log::info("Push notification sent successfully", ['user_id' => $user->id]);

                \Log::info("Saving user notification record");

                $parcel->userNotifications()->create([
                    'user_id' => $user->id,
                    'service_type' => get_class($parcel),
                    'parcel_id' => $parcel->id,
                    'message' => "Order Placed successfully",
                    'barcode_no' => $parcel->barcode_no,
                    'body' => $body,
                    'current_location' => $franchise->address,
                    // 'link'             => $link,
                ]);

                \Log::info("User notification record created successfully");
            } else {
                \Log::error("FCM token not found for user", ['user_id' => $user->id]);
            }
        } catch (\Exception $e) {
            \Log::error("Error in sendNotificationToUser: " . $e->getMessage(), [
                'parcel_id' => $parcel->id ?? null,
                'user_phone' => $userPhone ?? null,
                'exception' => $e
            ]);
        }
    }


    public function sendNotificationToFranchise($franchise, $parcel)
    {
        try {

            $franchiseId = $franchise->id;
            if (!$franchise) {

                return;
            }

            // Get FCM Token
            $token = $franchise->fcm_token;
            if (!$token) {

                return;
            }

            // Prepare Notification
            $title = FranchiseBag::getServiceType(GotogoRegisteredParcel::SERVICE_TYPE_GOTO_POST_REGISTERED);
            $body = "✅ Order Placed: Article No. {$parcel->barcode_no}\n" .
                "📅 Date: " . date('M d, Y, h:i A', strtotime($parcel->created_at)) . "\n" .
                "📍 From: {$parcel->pickup_address}\n" .
                "📍 To: {$parcel->consignee_address}\n" .
                "💰 Amount: ₹{$parcel->payment_amount}\n" .
                "🚀 Your order is placed successfully";

            // Send Push Notification

            $notification = new FranchisePushNotification($token, $parcel, $title, $body);
            $notification->sendPushNotification();
            \Log::info("notification  sent to franchise");

            $parcel->franchiseNotifications()->create([
                'franchise_id' => $franchiseId,
                'service_type' => get_class($parcel),
                'parcel_id' => $parcel->id,
                'message' => "Order Placed successfully",
                'barcode_no' => $parcel->barcode_no,
                'body' => $body,
                'current_location' => $franchise->address,
            ]);

            \Log::info("franchise notification created");
        } catch (\Exception $e) {
            \Log::error("Error in sendNotificationToFranchise: " . $e->getMessage(), [
                'parcel_id' => $parcel->id ?? 'N/A',
                'franchise_id' => $franchiseId ?? 'N/A',
                'exception' => $e
            ]);
        }
    }


    public function store(Request $request, RateCalculator $rateCalculater)

    {

        $requestData = $request->all();

        $this->validate($request, [

            // Pickup details validation rules

            'PickupName' => 'required|string|max:191',

            'PickupMobile' => 'required|string',

            // 'PickupEmail' => 'required|email|max:191',

            'PickupPincode' => 'required|string|max:6',

            'PickupCity' => 'required|string|max:191',

            'PickupState' => 'required|string|max:191',

            'PickupAddress' => 'required|string|max:255',

            // Consignee details validation rules

            'ConsigneeName' => 'required|string|max:191',

            // 'ConsigneeMobile' => 'required|string',

            // 'ConsigneeEmail' => 'required|email|max:191',

            'ConsigneePincode' => 'required|string|max:6',

            'ConsigneeCity' => 'required|string|max:191',

            'ConsigneeState' => 'required|string|max:191',

            'ConsigneeAddress' => 'required|string|max:255',

            // Parcel details validation rules

            'package_weight' => 'required|numeric',

        ]);

        $franchiseId = Franchise::getFranchiseId();
        $franchise = Franchise::findOrFail($franchiseId);
        $linkDetail = GotogoLink::where('franchise_no', $franchise->franchise_no)->first();
        $from = $request->PickupPincode;
        $to = $request->ConsigneePincode;
        $weight = $request->package_weight;
        $fuel_charge = $request->fuel_charge ?? 0;
        $pickup_charge = $request->pickup_charge ?? 0;
        $other_service_charge = $request->other_service_charge ?? 0;
        $rateDetails = $this->getPrice($from, $to, $weight, $fuel_charge, $pickup_charge, $other_service_charge);


        $barcode_no =   $this->getUniqueCode();
        $generator = new BarcodeGeneratorPNG();
        $barcode = $generator->getBarcode($barcode_no, $generator::TYPE_CODE_128);
        $barcode_image_src = base64_encode($barcode);


        if ($request->amount) {
            $amount = $request->amount ?? 0;
            $price = $amount + $fuel_charge + $pickup_charge + $other_service_charge;
            $gst = number_format(($price) * 0.18, 2, '.', '');
            $total = $price + $gst;
            $payment_amount =  $total;
        } else {

            $payment_amount = $rateDetails['total'];
        }


        if ($rateDetails['status'] == 'fail') {

            return response()->json(['status' => 400, 'message' => $rateDetails['message'], 'data' => $requestData]);
        }

        if ($barcode_no == 'Barcode series end' || $barcode_no == 'Barcodes not assigned') {

            return response()->json(['status' => 400, "showMessage" => "1", 'message' => 'barcode series end', 'data' => $requestData]);
        }

        if ($payment_amount <= 0) {

            return response()->json(['status' => 400, "showMessage" => "1", 'message' => 'Service Not Available', 'data' => $requestData]);
        }

        $scanned_barcode_no = $request->scanned_barcode_no;

        if (!empty($scanned_barcode_no)) {
            $parcel = GotogoRegisteredParcel::where('barcode_no', $scanned_barcode_no)->first();
            if ($parcel) {
                return response()->json(['status' => 400, 'message' => 'Barcode Already Exist', 'data' => $requestData]);
            } else {
                $barcode_no = $scanned_barcode_no;
            }
        } 


        $gotogo_balance = $franchise->gotogo_balance;
        $credit_balance = $franchise->credit_balance;
        $total_balance = $gotogo_balance + $credit_balance;

        if ($total_balance < $payment_amount) {
            return response()->json([
                'status' => 400,
                "showMessage" => "1",
                'message' => 'Balance Low',
                'data' => $requestData
            ]);
        }

        try {


            $franchise_role_user_id = null;
            if (Auth::guard('franchiseRoleUser')->user()) {
                $franchise_role_user_id = Auth::guard('franchiseRoleUser')->user()->id;
            }

              $cod_parcel_id = null;
           if ($request->payment_method == 'franchise') {
                $cod_parcel_id = $this->getOrCreateCodUser($request,$franchise);
           }else{
                $cod_parcel_id = $request->pickupDetails;
            }

            if ($request->payment_method == 'manager') {
                $payment_type = $request->payment_type;
            }elseif($request->payment_method == 'pickup') {
               $payment_type = $request->payment_type;
            }


            $parcel = GotogoRegisteredParcel::create([

                'franchise_id' => Franchise::getFranchiseId(),

                'franchise_role_users_id' => $franchise_role_user_id,

                'no_r_customer_id' => $cod_parcel_id,

                'booking_type' => $request->payment_method,

                'pickup_name' => $request->PickupName,

                'pickup_mobile' => $request->PickupMobile,

                'pickup_gst_number' => $request->PickupGstNo,

                'pickup_email' => $request->PickupEmail,

                'pickup_pincode' => $request->PickupPincode,

                'pickup_city' => $request->PickupCity,

                'pickup_state' => $request->PickupState,

                'pickup_address' => $request->PickupAddress,

                'consignee_name' => $request->ConsigneeName,

                'consignee_mobile' => $request->ConsigneeMobile,

                'consignee_gst_number' => $request->ConsigneeGstNo,

                'consignee_email' => $request->ConsigneeEmail,

                'consignee_pincode' => $request->ConsigneePincode,

                'consignee_city' => $request->ConsigneeCity,

                'consignee_state' => $request->ConsigneeState,

                'consignee_address' => $request->ConsigneeAddress,

                'package_weight' => $request->package_weight,

                'package_length' => $request->package_length,

                'package_width' => $request->package_width,

                'package_height' => $request->package_height,

                'fuel_charge' => $request->fuel_charge,
                'pickup_charge' => $request->pickup_charge,
                'other_service_charge' => $request->other_service_charge,

                'payment_amount' => $payment_amount,

                "payment_method" => 'prepaid',

                'barcode_no' =>  $barcode_no,

                'barcode_image_src' => $barcode_image_src,

                'insert_type' =>  GotogoRegisteredParcel::INSERT_TYPE_SINGLE,

            ]);

            GotogoRegisteredTrackOrder::create([
                'parcel_id' => $parcel->id,
                'barcode_no' => $barcode_no,
                'source_franchise_id' => Franchise::getFranchiseId(),
                'order_placed_datetime' => now(),
                'source_franchise_location' => $franchise->address,
            ]);


            $serviceTypeValue = GotogoRegisteredParcel::getServiceTypeDB(GotogoRegisteredParcel::SERVICE_TYPE_GOTO_POST_REGISTERED);
            $range_start_column = "parcel_barcode_range_start_{$serviceTypeValue}";

            $last_code_issued_column = "last_parcel_code_issued_{$serviceTypeValue}";

           
            $commission = $rateCalculater->calculateCommissionForGotogoPost($request->package_weight, 'franchise', GotogoRegisteredParcel::SERVICE_TYPE_GOTO_POST_REGISTERED);
             if ($request->payment_method === 'manager' || $request->payment_method === 'pickup') {
            $marketcommission = $rateCalculater->calculateCommissionForIndiaPostMarket($payment_type, $payment_amount);
            }

             if ($request->payment_method === 'manager' || $request->payment_method === 'pickup') {
              $commission = $commission - $marketcommission ?? 0;
              
            }
            if (($gotogo_balance + $credit_balance) >= $payment_amount) {
                // Pehle Gotogo Balance se katna hai jitna ho sake
                $deduct_from_gotogo = min($gotogo_balance, $payment_amount);
                $franchise->decrement('gotogo_balance', $deduct_from_gotogo);

                // Bacha hua amount credit balance se katna hai
                $remaining_amount = $payment_amount - $deduct_from_gotogo;
                if ($remaining_amount > 0) {
                    $franchise->decrement('credit_balance', $remaining_amount);
                }
            } else {
                return response()->json(['status' => 400, 'message' => 'Balance Low']);
            }

          // Franchise-specific commission
$serviceType = GotogoRegisteredParcel::SERVICE_TYPE_GOTO_POST_REGISTERED;

$netAmount = $payment_amount / 1.18;

$commissionData = $rateCalculater->calculateFranchiseCommission(
    $franchiseId,
    $serviceType,
    $netAmount
);

$commissionRate = $commissionData['rate'];
$commission = $commissionData['commission'];

FranchiseCommissionDetail::create([
    "franchise_id" => $franchiseId,
    "service_type" => $serviceType,
    "amount" => $netAmount,
    "commission" => $commission,
    "commission_rate" => $commissionRate,
    "payment_method" => 'prepaid',
]);

            if ($request->payment_method === 'manager' || $request->payment_method === 'pickup') {
        $datamanager = new ManagerCommissionDetail();
        $datamanager->commission_id = $payment_type;
        $datamanager->servicetype = IndiaPostSpeedPostParcel::SERVICE_TYPE_INDIA_POST_SPEED;
        $datamanager->amount = $payment_amount / 1.18;
        $datamanager->commission = number_format($marketcommission, 2, '.', '');
        $datamanager->type = $request->payment_method;
        $datamanager->payment_method = 'prepaid';
        $datamanager->save();

}

            $franchiseSeriesDetails = FranchiseBarcodeSeries::where("franchise_id", $franchiseId)->first();

            if ($franchiseSeriesDetails->{$last_code_issued_column}) {

                $last_parcel_code_issued = $franchiseSeriesDetails->{$last_code_issued_column};

                $seriesNum = $last_parcel_code_issued + 1;
            } else {

                $seriesNum = $franchiseSeriesDetails->{$range_start_column};
            }


            if ($request['pickup-details']) {
                PickupDetails::where('id', $request['pickup-details'])
                    ->update(['status' => 2]);
            }

            // ======================================

            // Send notification to user
            try {
                $this->sendNotificationToUser($franchise, $parcel);
            } catch (\Exception $e) {
                \Log::error('Error sending user notification: ' . $e->getMessage());
            }

            // Save PDF
            try {
                $savedPdfFilePath = $this->savePdf($parcel);
            } catch (\Exception $e) {
                \Log::error('Error saving PDF: ' . $e->getMessage());
                $savedPdfFilePath = null;
            }

            // Send email
            try {
                if ($savedPdfFilePath) {
                    Mail::to($request->PickupEmail)->send(new ParcelMail([$savedPdfFilePath, $parcel]));
                }
            } catch (\Exception $e) {
                \Log::error('Error sending email: ' . $e->getMessage());
            }


            // Send SMS to consignee mobile
            try {
                $notification = new SMSNotification($request->ConsigneeMobile, 'ORDER', [$barcode_no, now()]);
                $consigneeResponse = $notification->sendMessage();
            } catch (\Exception $e) {
                \Log::error('Error sending SMS to consignee mobile: ' . $e->getMessage());
            }


            $franchiseSeriesDetails->{$last_code_issued_column} = $seriesNum;
            $franchiseSeriesDetails->save();
            $franchiseBarcode = new FranchiseBarcodes;
            $franchiseBarcode->barcodes = $barcode_no;
            $franchiseBarcode->franchise_barcodeseries_id = $franchiseSeriesDetails->id;
            $franchiseBarcode->save();

            $barcode_available = $this->getBarcodeAvailableCount();
            // $otherPageContent = View::make('print.gotogopost.shortPrintWhileBooking', ['parcel' => $parcel, 'linkDetail' => $linkDetail])->render();
            // return response()->json(['status' => 200, 'message' => 'new parcel added', 'data' => $requestData, 'otherPageContent' => $otherPageContent, 'barcodeImageSrc' => $barcode_image_src, 'franchiseDetails' => $franchise, 'barcode_available' => $barcode_available]);
           return response()->json(['status' => 200, 'message' => 'New Parcel Create','parcel_id'=> $parcel->id, 'data' => $requestData, 'barcodeImageSrc' => $barcode_image_src, 'franchiseDetails' => $franchise, 'barcode_available' => $barcode_available, 'payment_method' => $request->payment_method, 'customer_id'=>$request->pickupDetails, 'payment_type'=>$request->payment_type]);
        } catch (\Exception $th) {
            return back()->with('error', $th->getMessage());
        }
    }



    public function view($id)

    {
        $data = GotogoRegisteredParcel::findorfail($id);

        $fuel_charge = $data->fuel_charge;
        $pickup_charge = $data->pickup_charge;
        $other_service_charge = $data->other_service_charge;
        $total_payment_amount = $data->payment_amount;
        $net_price = $total_payment_amount / 1.18;
        $amount = $net_price - ($fuel_charge + $pickup_charge + $other_service_charge);
        $gst = number_format($net_price * 0.18, 2, '.', '');
        $net_price_formatted = number_format($net_price, 2, '.', '');


        $rateDetails = [
            'fuel_charge' => $fuel_charge,
            'pickup_charge' => $pickup_charge,
            'other_service_charge' => $other_service_charge,
            'total_payment_amount' => number_format($total_payment_amount, 2, '.', ''),
            'net_price' => $net_price_formatted,
            'amount' => number_format($amount, 2, '.', ''),
            'gst' => $gst,
        ];

        return view('franchise.gotogoRegistered.view', compact('data', 'rateDetails'));
    }



    public function edit(Request $request, $id)

    {

        $post = GotogoRegisteredParcel::findOrFail($id);

        if ($request->isMethod('POST')) {

            $this->validate($request, [

                // Pickup details validation rules

                'PickupName' => 'required|string|max:191',

                'PickupMobile' => 'required|string',

                'PickupEmail' => 'required|email|max:191',

                'PickupPincode' => 'required|string|max:6',

                'PickupCity' => 'required|string|max:191',

                'PickupState' => 'required|string|max:191',

                'PickupAddress' => 'required|string|max:255',

                // Consignee details validation rules

                'ConsigneeName' => 'required|string|max:191',

                'ConsigneeMobile' => 'required|string',

                'ConsigneeEmail' => 'required|email|max:191',

                'ConsigneePincode' => 'required|string|max:6',

                'ConsigneeCity' => 'required|string|max:191',

                'ConsigneeState' => 'required|string|max:191',

                'ConsigneeAddress' => 'required|string|max:255',

                // Parcel details validation rules

                'package_weight' => 'required|numeric',

            ]);





            try {

                $post->pickup_name = $request->PickupName;

                $post->pickup_mobile = $request->PickupMobile;

                $post->pickup_email = $request->PickupEmail;

                $post->pickup_pincode = $request->PickupPincode;

                $post->pickup_city = $request->PickupCity;

                $post->pickup_state = $request->PickupState;

                $post->pickup_address = $request->PickupAddress;

                $post->consignee_name = $request->ConsigneeName;

                $post->consignee_mobile = $request->ConsigneeMobile;

                $post->consignee_email = $request->ConsigneeEmail;

                $post->consignee_pincode = $request->ConsigneePincode;

                $post->consignee_city = $request->ConsigneeCity;

                $post->consignee_state = $request->ConsigneeState;

                $post->consignee_address = $request->ConsigneeAddress;

                $post->package_weight = $request->package_weight;

                $post->package_length = $request->package_length;

                $post->package_width = $request->package_width;

                $post->package_height = $request->package_height;

                $post->save();



                return redirect()->route('franchise.go-registered.index', ['insert_type' => $post->insert_type])->with('success', 'Parcel updated successfully!');
            } catch (\Exception $th) {

                return back()->with('error', $th->getMessage())->withInput();
            }
        }



        $data = $post;

        $pickupDetails = PickupDetails::where('franchise_id', Franchise::getFranchiseId())

            ->get();



        return view('franchise.gotogoRegistered.edit', compact('data', 'pickupDetails'));
    }


    public function delete($id)
{
    try {
        $franchiseId = Franchise::getFranchiseId();
        $data = GotogoRegisteredParcel::findOrFail($id);  // Find the parcel, throws error if not found
        
        // Find the franchise by ID
        $franchise = Franchise::findOrFail($franchiseId);
        
        // Increment the india_credit_amount with the payment amount from the parcel
        $franchise->increment("gotogo_balance", $data->payment_amount);
        
        // Proceed with deleting the parcel
        $data->delete();

        // Return success response
        return redirect()
            ->back()
            ->with("success", "Parcel Deleted Successfully");

    } catch (\Exception $th) {
        // In case of an error, return back with error message
        return back()
            ->with("error", $th->getMessage())
            ->withInput();
    }
}



    public function downloadFormat()

    {

        try {

            $filePath = public_path('admin/assets/file/excelFomatFile.xlsx');

            if (!file_exists($filePath)) {

                return back()->with('error', 'File not found');
            }

            return response()->download($filePath, 'excelFomatFile.xlsx');
        } catch (\Exception $e) {

            return back()->with('error', 'Error downloading file: ' . $e->getMessage());
        }
    }



    public function storeByFile(Request $request, RateCalculator $rateCalculater)
{
 
    if (!$request->hasFile('file')) {
        return back()->with('error', 'No file uploaded.');
    }

    $franchiseId = Franchise::getFranchiseId();
    $franchise = Franchise::findOrFail($franchiseId);

    try {
        // Move uploaded file
        $file = $request->file('file');
        $destinationPath = public_path('tenancy/assets/franchise/RoleUser/');
        $fileName = uniqid() . '_' . $file->getClientOriginalName();
        $file->move($destinationPath, $fileName);

        // Load Excel
        $filePath = public_path("tenancy/assets/franchise/RoleUser/$fileName");
        if (!File::exists($filePath)) {
            return back()->with('error', 'File not found.');
        }

        $spreadsheet = IOFactory::load($filePath);
        $sheet = $spreadsheet->getActiveSheet();
        $data = [];
        $header = null;

        // Read rows
        foreach ($sheet->getRowIterator() as $row) {
            $rowData = [];
            $hasData = false;

            foreach ($row->getCellIterator() as $cell) {
                $cellValue = $cell->getValue();
                $rowData[] = $cellValue;
                $hyperlink = $cell->getHyperlink();
                if ($hyperlink && $hyperlink->getUrl()) {
                    $rowData['Hyperlink'] = $hyperlink->getUrl();
                }
                if (!is_null($cellValue) && $cellValue !== '') {
                    $hasData = true;
                }
            }

            if ($hasData) {
                if (is_null($header)) {
                    $header = $rowData;
                    $header[] = "Hyperlink";
                } else {
                    if (count($rowData) < count($header)) {
                        $rowData = array_pad($rowData, count($header), null);
                    }
                    $data[] = array_combine($header, $rowData);
                }
            }
        }

        // Barcode series check
        $franchiseSeriesDetails = FranchiseBarcodeSeries::where("franchise_id", $franchiseId)->first();
        $serviceType = GotogoRegisteredParcel::SERVICE_TYPE_GOTO_POST_SPEED;
        $serviceTypeValue = GotogoRegisteredParcel::getServiceTypeDB($serviceType);
        $range_start_column = "parcel_barcode_range_start_{$serviceTypeValue}";
        $range_end_column = "parcel_barcode_range_end_{$serviceTypeValue}";
        $range = $franchiseSeriesDetails->{$range_end_column} - $franchiseSeriesDetails->{$range_start_column};

        if ($franchiseSeriesDetails->{$range_end_column} == null || $range < count($data)) {
            return redirect()->back()->with('error', 'Number of data in excel file exceeds barcode series available for this service');
        }

        $client = new Client();
        $downloadDirectory = public_path("tenancy/assets/franchise/RoleUser/");

        // Process each row
        foreach ($data as $element) {
            try {
                // Download hyperlink file if any
                if (!empty($element['Hyperlink'])) {
                    $hyperlinkUrl = $element['Hyperlink'];
                    $downloadFileName = uniqid() . '_' . pathinfo($hyperlinkUrl, PATHINFO_BASENAME);
                    $destination = $downloadDirectory . $downloadFileName;
                    try {
                        $client->get($hyperlinkUrl, ['sink' => $destination]);
                    } catch (\Exception $e) {
                        Log::error("Error downloading file from $hyperlinkUrl: " . $e->getMessage());
                        continue;
                    }
                }

                // Get rate
                $rateDetails = $this->getPrice($element['Pickup Pincode'], $element['Consignee Pincode'], $element['Package Weight']);
                if ($rateDetails['status'] == 'fail') {
                    Log::warning("Rate calculation failed: " . json_encode($element));
                    continue;
                }

                // Resolve customer
                $customer = $this->resolveCustomer($element, $franchiseId);
                if ($customer instanceof \Illuminate\Http\RedirectResponse) {
                    Log::warning("Customer not found: " . json_encode($element));
                    continue;
                }
                // Barcode
                if ($request->barcode_option === "barcode_auto") {
                    $generator = new BarcodeGeneratorPNG();
                    $code = $this->getUniqueCode();
                    $barcode = base64_encode($generator->getBarcode($code, $generator::TYPE_CODE_128));
                } else {
                    $code = null;
                    $barcode = null;
                }


                // Hegiht width
                $actualWeight = floatval($element['Package Weight'] ?? 0); // grams
                $length = floatval($element['Package Length'] ?? 0);
                $width  = floatval($element['Package Width'] ?? 0);
                $height = floatval($element['Package Height'] ?? 0);

                $totalWeight = $actualWeight;

               if ($length && $width && $height) {
                  // volumetric in grams
                    $volumetricGrams = (($length * $width * $height) / 5000) * 1000;
                      $totalWeight += $volumetricGrams;
                 }


                    $from = $element['Pickup Pincode'];
                    $to = $element['Consignee Pincode'];
                    // $weight = $element['Package Weight'];
                    $fuel_charge = $element['Fuel Charge'] ?? 0;
                    $pickup_charge = $element['Pickup Charge'] ?? 0;
                    $other_service_charge = $element['Other service Charge'] ?? 0;
                    $rateDetails = $this->getPrice($from, $to, $totalWeight, $fuel_charge, $pickup_charge, $other_service_charge);

                    if (isset($element['Amount'])) {
                        $amount = $element['Amount'] ?? 0;
                        $price = $amount + $fuel_charge + $pickup_charge + $other_service_charge;
                        $gst = number_format(($price) * 0.18, 2, '.', '');
                        $total = $price + $gst;
                        $payment_amount =  $total;
                    } else {

                        $payment_amount = $rateDetails['total'];
                    }

                    $gotogo_balance = $franchise->gotogo_balance;
                    $credit_balance = $franchise->credit_balance;
                    $total_balance = $gotogo_balance + $credit_balance;

                    if ($total_balance < $payment_amount) {
                        return back()->with('error', 'Balance Low');
                    }
                
                //    return $payment_amount;

                // Create parcel
                $parcel = GotogoRegisteredParcel::create([
                    'franchise_id' => $franchiseId,
                    'pickup_name' => $element['Pickup Name'],
                    'pickup_mobile' => $element['Pickup Phone'],
                    'pickup_email' => $element['Pickup Email'],
                    'pickup_gst_number' => $element['Pickup gst'],
                    'pickup_pincode' => $element['Pickup Pincode'],
                    'pickup_city' => $element['Pickup City'],
                    'pickup_state' => $element['Pickup State'],
                    'pickup_address' => $element['Pickup Address'],
                    'consignee_name' => $element['Consignee Name'],
                    'consignee_mobile' => $element['Consignee Phone'],
                    'consignee_email' => $element['Consignee Email'],
                    'consignee_pincode' => $element['Consignee Pincode'],
                    'consignee_city' => $element['Consignee City'],
                    'consignee_state' => $element['Consignee State'],
                    'consignee_address' => $element['Consignee Address'],
                    'package_weight' => $actualWeight,
                    'package_length' => $length,
                    'package_width' => $width,
                    'package_height' => $height,
                    'payment_method' => 'prepaid',
                    'fuel_charge' => $fuel_charge,
                    'pickup_charge' => $pickup_charge,
                    'other_service_charge' => $other_service_charge,
                    'payment_amount' => $payment_amount,
                    'barcode_no' => $code,
                    'barcode_image_src' => $barcode,
                    'insert_type' => GotogoRegisteredParcel::INSERT_TYPE_BULK,
                ]);

                // Deduct balance
                $deduct_from_gotogo = min($gotogo_balance, $payment_amount);
                $franchise->decrement('gotogo_balance', $deduct_from_gotogo);
                $remaining_amount = $payment_amount - $deduct_from_gotogo;
                if ($remaining_amount > 0) {
                    $franchise->decrement('credit_balance', $remaining_amount);
                }

                // Commission
                FranchiseCommissionDetail::create([
                    "franchise_id" => $franchiseId,
                    "service_type" => GotogoRegisteredParcel::SERVICE_TYPE_GOTO_POST_SPEED,
                    "amount" => $payment_amount,
                    "commission" => $rateCalculater->calculateCommissionForGotogoPost($actualWeight, 'franchise', GotogoRegisteredParcel::SERVICE_TYPE_GOTO_POST_SPEED),
                    "payment_method" => 'prepaid',
                ]);

                // Save barcode series if auto
                if ($request->barcode_option === "barcode_auto") {
                    $last_code_issued_column = "last_parcel_code_issued_{$serviceTypeValue}";
                    if ($franchiseSeriesDetails->{$last_code_issued_column}) {
                        $seriesNum = $franchiseSeriesDetails->{$last_code_issued_column} + 1;
                    } else {
                        $seriesNum = $franchiseSeriesDetails->{$range_start_column};
                    }
                    $franchiseSeriesDetails->{$last_code_issued_column} = $seriesNum;
                    $franchiseSeriesDetails->save();

                    FranchiseBarcodes::create([
                        'barcodes' => $code,
                        'franchise_barcodeseries_id' => $franchiseSeriesDetails->id,
                    ]);
                }

    
                 try {
                $this->sendNotificationToUser($franchise, $parcel);
            } catch (\Exception $e) {
                \Log::error('Error sending user notification: ' . $e->getMessage());
            }

            // Save PDF
            try {
                $savedPdfFilePath = $this->savePdf($parcel);
            } catch (\Exception $e) {
                \Log::error('Error saving PDF: ' . $e->getMessage());
                $savedPdfFilePath = null;
            }

            // Send email
            try {
                if ($savedPdfFilePath) {
                   Mail::to($element['Pickup Email'])->send(new ParcelMail([$savedPdfFilePath, $parcel]));
                }
            } catch (\Exception $e) {
                \Log::error('Error sending email: ' . $e->getMessage());
            }


            try {
                $notification = new SMSNotification($element['Consignee Phone'], 'ORDER', [$code, now()]);
                $consigneeResponse = $notification->sendMessage();
            } catch (\Exception $e) {
                \Log::error('Error sending SMS to consignee mobile: ' . $e->getMessage());
            }


            } catch (\Exception $e) {
                Log::error("Error processing row: " . $e->getMessage());
                continue;
            }
        }

        return redirect()->route('franchise.go-registered.index', [
            'insert_type' => GotogoRegisteredParcel::INSERT_TYPE_BULK
        ])->with('success', 'Data added successfully');

    } catch (\Exception $e) {
        return back()->with('error', $e->getMessage());
    }
}



    public function fullPrint($id)

    {

        $parcel = GotogoRegisteredParcel::findorfail($id);

        $generator = new BarcodeGeneratorPNG();
        $code = $parcel->barcode_no;
        $barcode = $generator->getBarcode($code, $generator::TYPE_CODE_128);
        $barcode = base64_encode($barcode);

        $fuel_charge = $parcel->fuel_charge;
        $pickup_charge = $parcel->pickup_charge;
        $other_service_charge = $parcel->other_service_charge;
        $total_payment_amount = $parcel->payment_amount;
        $net_price = $total_payment_amount / 1.18;
        $amount = $net_price - ($fuel_charge + $pickup_charge + $other_service_charge);
        $gst = number_format($net_price * 0.18, 2, '.', '');
        $net_price_formatted = number_format($net_price, 2, '.', '');


        $rateDetails = [
            'fuel_charge' => $fuel_charge,
            'pickup_charge' => $pickup_charge,
            'other_service_charge' => $other_service_charge,
            'total_payment_amount' => number_format($total_payment_amount, 2, '.', ''),
            'net_price' => $net_price_formatted,
            'amount' => number_format($amount, 2, '.', ''),
            'gst' => $gst,
        ];


        $otherPageContent = View::make('print.gotogopost.fullPrint', ['parcel' => $parcel, 'barcode' => $barcode, 'rateDetails' => $rateDetails,'type' =>3])->render();

        return response()->json([

            'otherPageContent' => $otherPageContent

        ]);
    }





    public function shortPrintForCreatedParcel(Request $request)

    {
        $fromdate = $request->input("fromdate");
        $todate = $request->input("todate");
        $searchKey = $request->input('searchKey');
        $franchiseId = Franchise::getFranchiseId();
        $franchise = Franchise::findOrFail($franchiseId);
        $linkDetail = GotogoLink::where('franchise_no', $franchise->franchise_no)->first();
        $query = GotogoRegisteredParcel::where('franchise_id', Franchise::getFranchiseId())

            // ->where('insert_type', $request->insert_type)

            ->orderBy('created_at', 'desc');


        if ($searchKey) {

            $query->where(function ($q) use ($searchKey) {

                $q->where('pickup_name', 'LIKE', "%{$searchKey}%")

                    ->orWhere('pickup_mobile', 'LIKE', "%{$searchKey}%")

                    ->orWhere('pickup_email', 'LIKE', "%{$searchKey}%")

                    ->orWhere('pickup_pincode', 'LIKE', "%{$searchKey}%")

                    ->orWhere('pickup_city', 'LIKE', "%{$searchKey}%")

                    ->orWhere('pickup_state', 'LIKE', "%{$searchKey}%")

                    ->orWhere('pickup_address', 'LIKE', "%{$searchKey}%")

                    ->orWhere('consignee_name', 'LIKE', "%{$searchKey}%")

                    ->orWhere('consignee_mobile', 'LIKE', "%{$searchKey}%")

                    ->orWhere('consignee_email', 'LIKE', "%{$searchKey}%")

                    ->orWhere('consignee_pincode', 'LIKE', "%{$searchKey}%")

                    ->orWhere('consignee_city', 'LIKE', "%{$searchKey}%")

                    ->orWhere('consignee_state', 'LIKE', "%{$searchKey}%")

                    ->orWhere('consignee_address', 'LIKE', "%{$searchKey}%")

                    ->orWhere('barcode_no', 'LIKE', "%{$searchKey}%");
            });

             if (!empty($fromdate) || !empty($todate)) {
                if (!empty($fromdate) && !empty($todate)) {
                    $fromdate = Carbon::createFromFormat("d-m-Y", $fromdate)
                        ->startOfDay()
                        ->toDateString();
                    $todate = Carbon::createFromFormat("d-m-Y", $todate)
                        ->startOfDay()
                        ->toDateString();
                    $query->whereBetween("created_at", [$fromdate, $todate]);
                } elseif (!empty($fromdate)) {
                    // सिर्फ fromdate मिला
                    $fromdate = Carbon::createFromFormat(
                        "d-m-Y",
                        $fromdate
                    )->toDateString();
                    $query->whereDate("created_at", $fromdate);
                } elseif (!empty($todate)) {
                    // सिर्फ todate मिला
                    $todate = Carbon::createFromFormat(
                        "d-m-Y",
                        $todate
                    )->toDateString();
                    $query->whereDate("created_at", $todate);
                }
            }

            $parcels = $query->get();

            $otherPageContent = View::make('print.gotogopost.shortPrint', ['data' => $parcels, 'linkDetail' => $linkDetail,'type' =>3])->render();
            return response()->json([

                'otherPageContent' => $otherPageContent

            ]);
        }



       if (!empty($fromdate) || !empty($todate)) {
            if (!empty($fromdate) && !empty($todate)) {
                $fromdate = Carbon::createFromFormat("d-m-Y", $fromdate)
                    ->startOfDay()
                    ->toDateString();
                $todate = Carbon::createFromFormat("d-m-Y", $todate)
                    ->startOfDay()
                    ->toDateString();
                $query->whereBetween("created_at", [$fromdate, $todate]);
            } elseif (!empty($fromdate)) {
                // सिर्फ fromdate मिला
                $fromdate = Carbon::createFromFormat(
                    "d-m-Y",
                    $fromdate
                )->toDateString();
                $query->whereDate("created_at", $fromdate);
            } elseif (!empty($todate)) {
                // सिर्फ todate मिला
                $todate = Carbon::createFromFormat(
                    "d-m-Y",
                    $todate
                )->toDateString();
                $query->whereDate("created_at", $todate);
            }
            $parcels = $query->get();
        } else {
            $query->whereDate('created_at', Carbon::today());
            $parcels = $query->get();
        }

        $otherPageContent = View::make('print.gotogopost.shortPrint', ['data' => $parcels, 'linkDetail' => $linkDetail,'type' =>3])->render();


        return response()->json([

            'otherPageContent' => $otherPageContent

        ]);
    }







    public function downloadTableForCreatedTable(Request $request)

    {
        // Fetch data
        $fromdate = $request->input('fromdate');
        $todate = $request->input('todate');
        $searchKey = $request->input('searchKey');

        $query = GotogoRegisteredParcel::where('franchise_id', Franchise::getFranchiseId())

            // ->where('insert_type', $request->insert_type)

            ->orderBy('created_at', 'desc');

        if ($searchKey) {

            $query->where(function ($q) use ($searchKey) {

                $q->where('pickup_name', 'LIKE', "%{$searchKey}%")

                    ->orWhere('pickup_mobile', 'LIKE', "%{$searchKey}%")

                    ->orWhere('pickup_email', 'LIKE', "%{$searchKey}%")

                    ->orWhere('pickup_pincode', 'LIKE', "%{$searchKey}%")

                    ->orWhere('pickup_city', 'LIKE', "%{$searchKey}%")

                    ->orWhere('pickup_state', 'LIKE', "%{$searchKey}%")

                    ->orWhere('pickup_address', 'LIKE', "%{$searchKey}%")

                    ->orWhere('consignee_name', 'LIKE', "%{$searchKey}%")

                    ->orWhere('consignee_mobile', 'LIKE', "%{$searchKey}%")

                    ->orWhere('consignee_email', 'LIKE', "%{$searchKey}%")

                    ->orWhere('consignee_pincode', 'LIKE', "%{$searchKey}%")

                    ->orWhere('consignee_city', 'LIKE', "%{$searchKey}%")

                    ->orWhere('consignee_state', 'LIKE', "%{$searchKey}%")

                    ->orWhere('consignee_address', 'LIKE', "%{$searchKey}%")

                    ->orWhere('barcode_no', 'LIKE', "%{$searchKey}%");
            });

            if (!empty($fromdate) || !empty($todate)) {
                if (!empty($fromdate) && !empty($todate)) {
                    $fromdate = Carbon::createFromFormat("d-m-Y", $fromdate)
                        ->startOfDay()
                        ->toDateString();
                    $todate = Carbon::createFromFormat("d-m-Y", $todate)
                        ->startOfDay()
                        ->toDateString();
                    $query->whereBetween("created_at", [$fromdate, $todate]);
                } elseif (!empty($fromdate)) {
                    // सिर्फ fromdate मिला
                    $fromdate = Carbon::createFromFormat(
                        "d-m-Y",
                        $fromdate
                    )->toDateString();
                    $query->whereDate("created_at", $fromdate);
                } elseif (!empty($todate)) {
                    // सिर्फ todate मिला
                    $todate = Carbon::createFromFormat(
                        "d-m-Y",
                        $todate
                    )->toDateString();
                    $query->whereDate("created_at", $todate);
                }
            }

            $parcels = $query->get();
            return $this->getExcel($request, $parcels);
        }

        if (!empty($fromdate) || !empty($todate)) {
            
            if (!empty($fromdate) && !empty($todate)) {
                $fromdate = Carbon::createFromFormat('d-m-Y', $fromdate)->startOfDay()->toDateString();
                $todate = Carbon::createFromFormat('d-m-Y', $todate)->startOfDay()->toDateString();
                $query->whereBetween('created_at', [$fromdate, $todate]);
            } elseif (!empty($fromdate)) {
            // सिर्फ fromdate मिला
              $fromdate = Carbon::createFromFormat('d-m-Y', $fromdate)->toDateString();
              $query->whereDate('created_at', $fromdate);
              } elseif (!empty($todate)) {
             // सिर्फ todate मिला
              $todate = Carbon::createFromFormat('d-m-Y', $todate)->toDateString();
              $query->whereDate('created_at', $todate);
             }
            $parcels = $query->get();
            return $this->getExcel($request, $parcels);
        }

          $query->whereDate('created_at', Carbon::today());
          $parcels = $query->get();
       return $this->getExcel($request, $parcels);

    }





    public function assignBarcode(Request $request)

    {



        $parcel = GotogoRegisteredParcel::where("barcode_no", $request->barcode)->first();



        if ($parcel) {

            return response()->json(['status' => 'success', 'data' => $parcel, "message" => "dublicate barcode"]);
        }



        if ($request->isMethod('post')) {

            try {



                $generator = new BarcodeGeneratorPNG();

                $barcode = $generator->getBarcode($request->barcode, $generator::TYPE_CODE_128);

                $barcode_image_src = base64_encode($barcode);

                $Model = GotogoRegisteredParcel::where("barcode_no", null)->orderBy('created_at', 'desc')->first();

                $Model->barcode_no = $request->barcode;

                $Model->barcode_image_src = $barcode_image_src;

                $Model->save();



                return response()->json(['status' => 'success', 'data' => $Model, 'message' => "barcode assigned successfully"]);
            } catch (\Throwable $th) {

                return response()->json(['status' => 'error', 'message' => $th->getMessage()], 500);
            }
        }



        return response()->json(['status' => 'error', 'message' => 'Invalid request method'], 400);
    }


    public function showReceivedParcelList(Request $request)
    {

        $searchKey = $request->input('searchKey');
        $date = $request->input('date');

        $userId = Franchise::getFranchiseId();
        // $insertType = $request->input('insert_type');
        $formattedDate = (!empty($date) && $date !== '') ? Carbon::parse($date)->format('Y-m-d') : Carbon::today()->toDateString();

        $scidData = GotogoRegisteredParcel::where('sfid_forfile_upload', $userId);

        if ($scidData) {
            $scidData->where('sfid_file_upload_date', '=', $formattedDate);
            if ($searchKey) {
                $scidData->where(function ($query) use ($searchKey) {
                    $query->where('pickup_name', 'LIKE', "%{$searchKey}%")
                        ->orWhere('pickup_mobile', 'LIKE', "%{$searchKey}%")
                        ->orWhere('pickup_email', 'LIKE', "%{$searchKey}%")
                        ->orWhere('pickup_pincode', 'LIKE', "%{$searchKey}%")
                        ->orWhere('pickup_city', 'LIKE', "%{$searchKey}%")
                        ->orWhere('pickup_state', 'LIKE', "%{$searchKey}%")
                        ->orWhere('pickup_address', 'LIKE', "%{$searchKey}%")
                        ->orWhere('consignee_name', 'LIKE', "%{$searchKey}%")
                        ->orWhere('consignee_mobile', 'LIKE', "%{$searchKey}%")
                        ->orWhere('consignee_email', 'LIKE', "%{$searchKey}%")
                        ->orWhere('consignee_pincode', 'LIKE', "%{$searchKey}%")
                        ->orWhere('consignee_city', 'LIKE', "%{$searchKey}%")
                        ->orWhere('consignee_state', 'LIKE', "%{$searchKey}%")
                        ->orWhere('consignee_address', 'LIKE', "%{$searchKey}%")
                        ->orWhere('barcode_no', 'LIKE', "%{$searchKey}%");
                });
            }

            $scidResults = $scidData->get();
        }

        $dcidData = GotogoRegisteredParcel::where('dfid_forfile_upload', $userId);

        if ($dcidData) {
            $dcidData->where('dfid_file_upload_date', '=', $formattedDate);

            if ($searchKey) {
                $dcidData->where(function ($query) use ($searchKey) {
                    $query->where('pickup_name', 'LIKE', "%{$searchKey}%")
                        ->orWhere('pickup_mobile', 'LIKE', "%{$searchKey}%")
                        ->orWhere('pickup_email', 'LIKE', "%{$searchKey}%")
                        ->orWhere('pickup_pincode', 'LIKE', "%{$searchKey}%")
                        ->orWhere('pickup_city', 'LIKE', "%{$searchKey}%")
                        ->orWhere('pickup_state', 'LIKE', "%{$searchKey}%")
                        ->orWhere('pickup_address', 'LIKE', "%{$searchKey}%")
                        ->orWhere('consignee_name', 'LIKE', "%{$searchKey}%")
                        ->orWhere('consignee_mobile', 'LIKE', "%{$searchKey}%")
                        ->orWhere('consignee_email', 'LIKE', "%{$searchKey}%")
                        ->orWhere('consignee_pincode', 'LIKE', "%{$searchKey}%")
                        ->orWhere('consignee_city', 'LIKE', "%{$searchKey}%")
                        ->orWhere('consignee_state', 'LIKE', "%{$searchKey}%")
                        ->orWhere('consignee_address', 'LIKE', "%{$searchKey}%")
                        ->orWhere('barcode_no', 'LIKE', "%{$searchKey}%");
                });
            }

            $dcidResults = $dcidData->get();
        }
        $mergedCollection = $scidResults->merge($dcidResults);

        return view('franchise.gotogoRegistered.showReceivedParcelList', ['datas' => $mergedCollection]);
    }


    public function excelUploadByFranchise(Request $request)

    {

        if (!$request->hasFile('file')) {

            return back()->with('error', 'No file uploaded.');
        }

        try {


            $file = $request->file('file');

            $destinationPath = public_path('tenancy/assets/franchise/RoleUser/');

            $fileName = uniqid() . '_' . $file->getClientOriginalName();

            $file->move($destinationPath, $fileName);



            // Load the Excel file

            $filePath = public_path("tenancy/assets/franchise/RoleUser/$fileName");

            if (!File::exists($filePath)) {

                return back()->with('error', 'File not found.');
            }



            $spreadsheet = IOFactory::load($filePath);

            $sheet = $spreadsheet->getActiveSheet();

            $data = [];

            $header = null;



            // Iterate through each row in the worksheet

            foreach ($sheet->getRowIterator() as $rowIndex => $row) {

                $rowData = [];

                $hasData = false;



                foreach ($row->getCellIterator() as $cellIndex => $cell) {

                    $cellValue = $cell->getValue();

                    $rowData[] = $cellValue;

                    $hyperlink = $cell->getHyperlink();

                    $hyperlinkUrl = $hyperlink ? $hyperlink->getUrl() : null;



                    if ($hyperlinkUrl) {

                        $rowData['Hyperlink'] = $hyperlinkUrl;
                    }

                    if (!is_null($cellValue) && $cellValue !== '') {

                        $hasData = true;
                    }
                }

                if ($hasData) {

                    if (is_null($header)) {

                        $header = $rowData;

                        $header[] = "Hyperlink";
                    } else {

                        if (count($rowData) < count($header)) {

                            $rowData = array_pad($rowData, count($header), null);
                        }

                        $data[] = array_combine($header, $rowData);
                    }
                }
            }

            $desiredBarcode = [];
            foreach ($data as $key => $value) {
                $desiredBarcode[] = $value['Barcode'];
            }


            GotogoRegisteredParcel::whereIn('barcode_no', $desiredBarcode)
                ->where(function ($query) {
                    $userId = Franchise::getFranchiseId();
                    $query->whereNull('scid_forfile_upload')
                        ->orWhere('scid_forfile_upload', $userId);
                })
                ->update(['sfid_forfile_upload' => Franchise::getFranchiseId(), 'sfid_file_upload_date' => Carbon::today()->toDateString()]);

            GotogoRegisteredParcel::whereIn('barcode_no', $desiredBarcode)
                ->whereNotNull('sfid_forfile_upload')
                ->where('sfid_forfile_upload', '!=', Franchise::getFranchiseId())
                ->update(['dfid_forfile_upload' => Franchise::getFranchiseId(), 'dfid_file_upload_date' => Carbon::today()->toDateString()]);


            $updatedParcels = GotogoRegisteredParcel::whereIn('barcode_no', $desiredBarcode)->get();

            return view('franchise.gotogoRegistered.showReceivedParcelList', ['datas' => $updatedParcels]);
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }


    public function downloadTableOfReceivedParcel(Request $request)

    {

        // Fetch data
        $searchKey = $request->input('searchKey');
        $date = $request->input('date');

        $userId = Franchise::getFranchiseId();
        // $insertType = $request->input('insert_type');
        $formattedDate = (!empty($date) && $date !== '') ? Carbon::parse($date)->format('Y-m-d') : Carbon::today()->toDateString();

        $scidData = GotogoRegisteredParcel::where('sfid_forfile_upload', $userId);

        if ($scidData) {
            $scidData->where('sfid_file_upload_date', '=', $formattedDate);
            if ($searchKey) {
                $scidData->where(function ($query) use ($searchKey) {
                    $query->where('pickup_name', 'LIKE', "%{$searchKey}%")
                        ->orWhere('pickup_mobile', 'LIKE', "%{$searchKey}%")
                        ->orWhere('pickup_email', 'LIKE', "%{$searchKey}%")
                        ->orWhere('pickup_pincode', 'LIKE', "%{$searchKey}%")
                        ->orWhere('pickup_city', 'LIKE', "%{$searchKey}%")
                        ->orWhere('pickup_state', 'LIKE', "%{$searchKey}%")
                        ->orWhere('pickup_address', 'LIKE', "%{$searchKey}%")
                        ->orWhere('consignee_name', 'LIKE', "%{$searchKey}%")
                        ->orWhere('consignee_mobile', 'LIKE', "%{$searchKey}%")
                        ->orWhere('consignee_email', 'LIKE', "%{$searchKey}%")
                        ->orWhere('consignee_pincode', 'LIKE', "%{$searchKey}%")
                        ->orWhere('consignee_city', 'LIKE', "%{$searchKey}%")
                        ->orWhere('consignee_state', 'LIKE', "%{$searchKey}%")
                        ->orWhere('consignee_address', 'LIKE', "%{$searchKey}%")
                        ->orWhere('barcode_no', 'LIKE', "%{$searchKey}%");
                });
            }

            $scidResults = $scidData->get();
        }

        $dcidData = GotogoRegisteredParcel::where('dfid_forfile_upload', $userId);

        if ($dcidData) {
            $dcidData->where('dfid_file_upload_date', '=', $formattedDate);

            if ($searchKey) {
                $dcidData->where(function ($query) use ($searchKey) {
                    $query->where('pickup_name', 'LIKE', "%{$searchKey}%")
                        ->orWhere('pickup_mobile', 'LIKE', "%{$searchKey}%")
                        ->orWhere('pickup_email', 'LIKE', "%{$searchKey}%")
                        ->orWhere('pickup_pincode', 'LIKE', "%{$searchKey}%")
                        ->orWhere('pickup_city', 'LIKE', "%{$searchKey}%")
                        ->orWhere('pickup_state', 'LIKE', "%{$searchKey}%")
                        ->orWhere('pickup_address', 'LIKE', "%{$searchKey}%")
                        ->orWhere('consignee_name', 'LIKE', "%{$searchKey}%")
                        ->orWhere('consignee_mobile', 'LIKE', "%{$searchKey}%")
                        ->orWhere('consignee_email', 'LIKE', "%{$searchKey}%")
                        ->orWhere('consignee_pincode', 'LIKE', "%{$searchKey}%")
                        ->orWhere('consignee_city', 'LIKE', "%{$searchKey}%")
                        ->orWhere('consignee_state', 'LIKE', "%{$searchKey}%")
                        ->orWhere('consignee_address', 'LIKE', "%{$searchKey}%")
                        ->orWhere('barcode_no', 'LIKE', "%{$searchKey}%");
                });
            }

            $dcidResults = $dcidData->get();
        }
        $parcels = $scidResults->merge($dcidResults);


        // Create a new Spreadsheet object

        $spreadsheet = new Spreadsheet();

        $sheet = $spreadsheet->getActiveSheet();



        // Set the headings

        $headings = [

            'Sl',
            'Barcode',
            'Ref',
            'From Address',
            'ADD1',

            'ADD2',
            'ADD3',

            'Pincode',
            'City',
            'State',
            'Mobile',
            'Email',
            'To Address',
            'ADD1',
            'ADD2',

            'ADD3',

            'Pincode',
            'City',

            'State',
            'Mobile',
            'Email',
            'Weight',

        ];



        $column = 'A';

        foreach ($headings as $heading) {

            $sheet->setCellValue($column . '1', $heading);

            $column++;
        }



        // Populate the data

        $row = 2; // Start from the second row

        foreach ($parcels as $key => $parcel) {

            // Process pickup address

            $pickupAddressParts = explode(',', $parcel->pickup_address);

            $PADD1 = $pickupAddressParts[0] ?? '';

            $PADD2 = $pickupAddressParts[1] ?? $PADD1;

            $PADD3 = $pickupAddressParts[2] ?? $PADD2;



            $consigneeAddressParts = explode(',', $parcel->consignee_address);

            $CADD1 = $consigneeAddressParts[0] ?? '';

            $CADD2 = $consigneeAddressParts[1] ?? $CADD1;

            $CADD3 = $consigneeAddressParts[2] ?? $CADD2;





            // Set cell values

            $sheet->setCellValue('A' . $row, $key + 1);

            $sheet->setCellValue('B' . $row, $parcel->barcode_no);

            $sheet->setCellValue('C' . $row, GotogoRegisteredParcel::getServiceType(GotogoRegisteredParcel::SERVICE_TYPE_GOTO_POST_REGISTERED));

            $sheet->setCellValue('D' . $row, $parcel->pickup_address);

            $sheet->setCellValue('E' . $row, $PADD1);

            $sheet->setCellValue('F' . $row, $PADD2);

            $sheet->setCellValue('G' . $row, $PADD3);

            $sheet->setCellValue('H' . $row, $parcel->pickup_pincode);

            $sheet->setCellValue('I' . $row, $parcel->pickup_city);

            $sheet->setCellValue('J' . $row, $parcel->pickup_state);

            $sheet->setCellValue('K' . $row, $parcel->pickup_mobile);

            $sheet->setCellValue('L' . $row, $parcel->pickup_email);

            $sheet->setCellValue('M' . $row, $parcel->consignee_address);

            $sheet->setCellValue('N' . $row, $CADD1);

            $sheet->setCellValue('O' . $row, $CADD2);

            $sheet->setCellValue('P' . $row, $CADD3);

            $sheet->setCellValue('Q' . $row, $parcel->consignee_pincode);

            $sheet->setCellValue('R' . $row, $parcel->consignee_city);

            $sheet->setCellValue('S' . $row, $parcel->consignee_state);

            $sheet->setCellValue('T' . $row, $parcel->consignee_mobile);

            $sheet->setCellValue('U' . $row, $parcel->consignee_email);

            $sheet->setCellValue('V' . $row, $parcel->package_weight);



            $row++;
        }



        // Create a Writer

        $writer = new Xlsx($spreadsheet);



        // Create a response to stream the file

        $response = new StreamedResponse(function () use ($writer) {

            $writer->save('php://output');
        });



        $response->headers->set('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');

        $response->headers->set('Content-Disposition', 'attachment;filename="parcels.xlsx"');

        $response->headers->set('Cache-Control', 'max-age=0');



        return $response;
    }


    public function shortPrintForReceivedParcel(Request $request)

    {

        $date = $request->input('date');
        $searchKey = $request->input('searchKey');
        $userId = Franchise::getFranchiseId();

        $franchise = Franchise::findOrFail($userId);
        $linkDetail = GotogoLink::where('franchise_no', $franchise->franchise_no)->first();

        $formattedDate = (!empty($date) && $date !== '') ? Carbon::parse($date)->format('Y-m-d') : Carbon::today()->toDateString();

        $scidData = GotogoRegisteredParcel::where('sfid_forfile_upload', $userId);

        if ($scidData) {
            $scidData->where('sfid_file_upload_date', '=', $formattedDate);
            if ($searchKey) {
                $scidData->where(function ($query) use ($searchKey) {
                    $query->where('pickup_name', 'LIKE', "%{$searchKey}%")
                        ->orWhere('pickup_mobile', 'LIKE', "%{$searchKey}%")
                        ->orWhere('pickup_email', 'LIKE', "%{$searchKey}%")
                        ->orWhere('pickup_pincode', 'LIKE', "%{$searchKey}%")
                        ->orWhere('pickup_city', 'LIKE', "%{$searchKey}%")
                        ->orWhere('pickup_state', 'LIKE', "%{$searchKey}%")
                        ->orWhere('pickup_address', 'LIKE', "%{$searchKey}%")
                        ->orWhere('consignee_name', 'LIKE', "%{$searchKey}%")
                        ->orWhere('consignee_mobile', 'LIKE', "%{$searchKey}%")
                        ->orWhere('consignee_email', 'LIKE', "%{$searchKey}%")
                        ->orWhere('consignee_pincode', 'LIKE', "%{$searchKey}%")
                        ->orWhere('consignee_city', 'LIKE', "%{$searchKey}%")
                        ->orWhere('consignee_state', 'LIKE', "%{$searchKey}%")
                        ->orWhere('consignee_address', 'LIKE', "%{$searchKey}%")
                        ->orWhere('barcode_no', 'LIKE', "%{$searchKey}%");
                });
            }

            $scidResults = $scidData->get();
        }

        $dcidData = GotogoRegisteredParcel::where('dfid_forfile_upload', $userId);

        if ($dcidData) {
            $dcidData->where('dfid_file_upload_date', '=', $formattedDate);

            if ($searchKey) {
                $dcidData->where(function ($query) use ($searchKey) {
                    $query->where('pickup_name', 'LIKE', "%{$searchKey}%")
                        ->orWhere('pickup_mobile', 'LIKE', "%{$searchKey}%")
                        ->orWhere('pickup_email', 'LIKE', "%{$searchKey}%")
                        ->orWhere('pickup_pincode', 'LIKE', "%{$searchKey}%")
                        ->orWhere('pickup_city', 'LIKE', "%{$searchKey}%")
                        ->orWhere('pickup_state', 'LIKE', "%{$searchKey}%")
                        ->orWhere('pickup_address', 'LIKE', "%{$searchKey}%")
                        ->orWhere('consignee_name', 'LIKE', "%{$searchKey}%")
                        ->orWhere('consignee_mobile', 'LIKE', "%{$searchKey}%")
                        ->orWhere('consignee_email', 'LIKE', "%{$searchKey}%")
                        ->orWhere('consignee_pincode', 'LIKE', "%{$searchKey}%")
                        ->orWhere('consignee_city', 'LIKE', "%{$searchKey}%")
                        ->orWhere('consignee_state', 'LIKE', "%{$searchKey}%")
                        ->orWhere('consignee_address', 'LIKE', "%{$searchKey}%")
                        ->orWhere('barcode_no', 'LIKE', "%{$searchKey}%");
                });
            }

            $dcidResults = $dcidData->get();
        }

        $mergedCollection = $scidResults->merge($dcidResults);

        // Pass data to the view
        $otherPageContent = View::make('print.gotogopost.shortPrint', ['data' => $mergedCollection, 'linkDetail' => $linkDetail, 'type' =>3])->render();


        return response()->json([

            'otherPageContent' => $otherPageContent

        ]);
    }

    public function trackOrder($id)

    {
        $trackingDetails = GotogoRegisteredTrackOrder::where('parcel_id', $id)->first();
        return response()->json([
            'trackingDetails' => $trackingDetails
        ]);
    }

    public function getNextBarcode()
    {
        $user = Auth::guard('franchise')->user();
        $state = $user->state; // Get the state of the franchise

        try {
            // Get the next available barcode based on state
            $barcode = IndiaPostBarcode::where('state', $state) // Filter by state
                ->where('availables', '>', 0)
                ->first();

            if ($barcode) {
                $nextBarcode = $barcode->getNextBarcode();

                // Increment range_from and decrement availables
                $barcode->increment('range_from');
                $barcode->decrement('availables');

                return response()->json([
                    'status' => 'success',
                    'barcode' => $nextBarcode
                ]);
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'No available barcodes for this state'
                ], 404);
            }
        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }


     public function allprint(Request $request){
        
        $franchiseId = Franchise::getFranchiseId();
        $franchise = Franchise::findOrFail($franchiseId);
        $linkDetail = GotogoLink::where('franchise_no', $franchise->franchise_no)->first();
        $parcels = GotogoRegisteredParcel::where('franchise_id', Franchise::getFranchiseId())->whereIn('id',$request->id)->get();
        
        $otherPageContent = View::make('print.gotogopost.shortPrint', ['data' => $parcels, 'linkDetail' => $linkDetail, 'type' =>3])->render();


        return response()->json([

            'otherPageContent' => $otherPageContent

        ]);
    }

       private function getOrCreateCodUser(Request $request, $franchise): ?int
{

    $codCheck = NoRegisterCustomer::Where('phone', $request->PickupMobile)
        ->where('type','franchise')
        ->first();

    if ($codCheck) {
        return $codCheck->id;
    }

    $password = substr(str_shuffle('0123456789'), 0, 10);

    $cod = NoRegisterCustomer::create([
        'name' => $request->PickupName,
        'type' => 'franchise',
        'franchise_id' => $franchise->id,
        'phone' => $request->PickupMobile,
        'gst_number' => $request->PickupGstNo ?? null,
        'email' => $request->PickupEmail,
        'cph_link' => $franchise->id,
        'location' => $franchise->city,
        'pincode' => $request->PickupPincode,
        'city' => $request->PickupCity,
        'state' => $request->PickupState,
        'address' => $request->PickupAddress,
        'password' => Hash::make($password),
    ]);
      

    return $cod->id;
}

protected function resolveCustomer($element, $franchiseId)
{
    // Case 1: Market Manager
    if (!empty($element['Market Manager _id'])) {
        $manager = MManager::where('mobile', $element['Market Manager _id'])
            ->where('franchise_id', $franchiseId)
            ->first();

        if (!$manager) {
            return back()->with('error', 'Market Manager Not Found');
        }

        $customer = NoRegisterCustomer::where([
            ['phone', $element['Pickup Phone']],
            ['type', 'manager'],
            ['market_id', $manager->id],
            ['franchise_id', $franchiseId]
        ])->first();

        if (!$customer) {
            return back()->with('error', 'Customer Not Found1');
        }

        return $customer;
    }

    // Case 2: Pickup Boy
    if (!empty($element['Pickup Boy_id'])) {
        $pickupBoy = PickupDetails::where('phone', $element['Pickup Boy_id'])
            ->where('franchise_id', $franchiseId)
            ->first();

        if (!$pickupBoy) {
            return back()->with('error', 'Pickup Boy Not Found');
        }

        $customer = NoRegisterCustomer::where([
            ['phone', $element['Pickup Phone']],
            ['type', 'pickup'],
            ['market_id', $pickupBoy->id],
            ['franchise_id', $franchiseId]
        ])->first();

        if (!$customer) {
            return back()->with('error', 'Customer Not Found2');
        }

        return $customer;
    }

    // Case 3: Neither Market Manager nor Pickup Boy → Franchise Type
    if (empty($element['Pickup Boy_id']) && empty($element['Market Manager _id'])) {

        $existing = NoRegisterCustomer::where([
            ['phone', $element['Pickup Phone']],
            ['type', 'franchise'],
            ['franchise_id', $franchiseId]
        ])->first();

        if ($existing) {
            return $existing; // Return if already exists
        }

        // Create new customer
        $password = substr(str_shuffle('0123456789'), 0, 10);

        return NoRegisterCustomer::create([
            'name'         => $element['Pickup Name'] ?? '',
            'type'         => 'franchise',
            'franchise_id' => $franchiseId,
            'phone'        => $element['Pickup Phone'] ?? '',
            'email'        => $element['Pickup Email'] ?? '',
            'gst_no'        => $element['Pickup gst'] ?? '',
            'pincode'      => $element['Pickup Pincode'] ?? '',
            'city'         => $element['Pickup City'] ?? '',
            'state'        => $element['Pickup State'] ?? '',
            'address'      => $element['Pickup Address'] ?? '',
            'password'     => Hash::make($password),
        ]);
    }

    return null; // Default fallback
}


public function getBookingDetails(Request $request)
{
    
    $html = "<select name='payment_type' id='payment_type' class='select form-select' data-tags='true' data-placeholder='Select an option'><option selected disabled>Select Option</option>";
    $pickupDetails = [];
 
    if ($request->select == 'franchise') {
        $pickupDetails = Franchise::where('id', Franchise::getFranchiseId())
    ->where('status', 1)
    ->select('id', 'name', 'mobile as phone')
    ->get();

    } 

   else if ($request->select == 'manager') {
         $pickupDetails = MManager::where('franchise_id', Franchise::getFranchiseId())
            ->where('status', 1)
            ->select('id', 'name', 'mobile as phone')
            ->get();
    } else {
        $pickupDetails = PickupDetails::where('franchise_id', Franchise::getFranchiseId())
            ->where('status', 1)
            ->get();
    }

    if ($pickupDetails->isNotEmpty()) {
        foreach ($pickupDetails as $list) {
            $html .= "<option value='{$list->id}'>{$list->name}, ({$list->phone})</option>";
        }
    } else {
        $html .= "<option disabled>No data found</option>";
    }

    $html .= "</select>";

    return response()->json(['html' => $html]);
}


public function emailDetails(Request $request)

    {

            $pickupDetails = NoRegisterCustomer::where('id', $request->select)->first();
          
        return response()->json($pickupDetails);
    }

    // End Email


    // #Mobile
        public function mobileDetails(Request $request)
{
    // return $request->all();
    // Use provided select value or fallback to franchise ID
    $id = filled($request->select) ? $request->select : Franchise::getFranchiseId();

    // Determine type based on 'select' value
   $type = $request->type === 'franchise' ? 'franchise' : ($request->type === 'manager' ? 'manager' : 'pickup');

// return $type;
    // Fetch data based on type and franchise ID
   $query = NoRegisterCustomer::where('franchise_id', Franchise::getFranchiseId());

if ($type == 'franchise') {
    $query->where('franchise_id', $id);
} else {
    $query->where('market_id', $id);
}

 $pickupDetails = $query->where('type', $type)->get();


    // Start building the HTML select element
    $html = "<select name='payment_method' id='paymentMethod' class='select form-select' data-tags='true' data-placeholder='Select an option'> <option selected disabled>Select Option</option>";

    if ($pickupDetails->isNotEmpty()) {
        foreach ($pickupDetails as $list) {
            $html .= "<option value='{$list->id}'>{$list->name}, ({$list->phone})</option>";
        }
    } else {
        // Show a default "no data found" option
        $html .= "<option disabled>No data found</option>";
    }

    $html .= "</select>";

    // Return both the HTML and the raw data as JSON
    return response()->json([
        'html' => $html,
        'data' => $pickupDetails->isNotEmpty() ? $pickupDetails : null
    ]);
}

 public function GetTotalAmount(Request $request)
{
    $totalParcels = 0;
    $totalAmount = 0;
    $CodtotalAmount = 0;

    // Build the query (don't run it yet)
    $dataQuery = GotogoRegisteredParcel::where('franchise_id', Franchise::getFranchiseId())
        ->whereIn('id', $request->id)
        ->orderBy('created_at', 'desc');

    // Clone and calculate totals
    $CodtotalAmount = (clone $dataQuery)->sum('cod_amount');
    $totalAmount = (clone $dataQuery)->sum('payment_amount');
    $totalParcels = (clone $dataQuery)->count();

    return response()->json([
        'status' => 'success',
        'CodtotalAmount' => $CodtotalAmount,
        'totalAmount' => $totalAmount,
        'totalParcels' => $totalParcels,
        'message' => "Amount fetched successfully"
    ]);
}

public function getExcel($request, $parcels)
    {
        $spreadsheet = new Spreadsheet();

        $sheet = $spreadsheet->getActiveSheet();



        // Set the headings

        $headings = [

            'Sl',
            'Barcode',
            'Ref',
            'From Address',
            'ADD1',

            'ADD2',
            'ADD3',

            'Pincode',
            'City',
            'State',
            'Mobile',
            'Email',
            'To Address',
            'ADD1',
            'ADD2',

            'ADD3',

            'Pincode',
            'City',

            'State',
            'Mobile',
            'Email',
            'Weight',

        ];



        $column = 'A';

        foreach ($headings as $heading) {

            $sheet->setCellValue($column . '1', $heading);

            $column++;
        }



        // Populate the data

        $row = 2; // Start from the second row

        foreach ($parcels as $key => $parcel) {

            // Process pickup address

            $pickupAddressParts = explode(',', $parcel->pickup_address);

            $PADD1 = $pickupAddressParts[0] ?? '';

            $PADD2 = $pickupAddressParts[1] ?? $PADD1;

            $PADD3 = $pickupAddressParts[2] ?? $PADD2;



            $consigneeAddressParts = explode(',', $parcel->consignee_address);

            $CADD1 = $consigneeAddressParts[0] ?? '';

            $CADD2 = $consigneeAddressParts[1] ?? $CADD1;

            $CADD3 = $consigneeAddressParts[2] ?? $CADD2;





            // Set cell values

            $sheet->setCellValue('A' . $row, $key + 1);

            $sheet->setCellValue('B' . $row, $parcel->barcode_no);

            $sheet->setCellValue('C' . $row, GotogoRegisteredParcel::getServiceType(GotogoRegisteredParcel::SERVICE_TYPE_GOTO_POST_REGISTERED));

            $sheet->setCellValue('D' . $row, $parcel->pickup_address);

            $sheet->setCellValue('E' . $row, $PADD1);

            $sheet->setCellValue('F' . $row, $PADD2);

            $sheet->setCellValue('G' . $row, $PADD3);

            $sheet->setCellValue('H' . $row, $parcel->pickup_pincode);

            $sheet->setCellValue('I' . $row, $parcel->pickup_city);

            $sheet->setCellValue('J' . $row, $parcel->pickup_state);

            $sheet->setCellValue('K' . $row, $parcel->pickup_mobile);

            $sheet->setCellValue('L' . $row, $parcel->pickup_email);

            $sheet->setCellValue('M' . $row, $parcel->consignee_address);

            $sheet->setCellValue('N' . $row, $CADD1);

            $sheet->setCellValue('O' . $row, $CADD2);

            $sheet->setCellValue('P' . $row, $CADD3);

            $sheet->setCellValue('Q' . $row, $parcel->consignee_pincode);

            $sheet->setCellValue('R' . $row, $parcel->consignee_city);

            $sheet->setCellValue('S' . $row, $parcel->consignee_state);

            $sheet->setCellValue('T' . $row, $parcel->consignee_mobile);

            $sheet->setCellValue('U' . $row, $parcel->consignee_email);

            $sheet->setCellValue('V' . $row, $parcel->package_weight);



            $row++;
        }



        // Create a Writer

        $writer = new Xlsx($spreadsheet);



        // Create a response to stream the file

        $response = new StreamedResponse(function () use ($writer) {

            $writer->save('php://output');
        });



        $response->headers->set('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');

        $response->headers->set('Content-Disposition', 'attachment;filename="parcels.xlsx"');

        $response->headers->set('Cache-Control', 'max-age=0');



        return $response;
    }

}
