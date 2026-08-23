<?php

namespace App\Http\Controllers\franchise;

use App\Http\Controllers\Controller;

use App\Models\IndiaPostSpeedPostParcel;
use App\Models\IndiaPostSpeedPostTrackOrder;
use App\Models\IndiaPostBarcode;
use App\Models\PickupDetails;
use App\Models\Franchise;
use App\Models\IndiaPostLink;
use App\Models\ECustomer;
use App\Models\GotogoLink;
use App\Models\FranchiseCommissionDetail;
use App\Models\NoRegisterCustomer;
use App\Models\MManager;
use App\Models\Student;
use App\Models\MManagerKyc;
use App\Models\ManagerCommissionDetail;
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

use App\Http\Controllers\franchise\RateCalculator;

use App\Notifications\FranchisePushNotification;
use App\Models\FranchiseBag;
use App\Models\User;

use Config;
use App\Notifications\SMSNotification;

use Illuminate\Support\Facades\Log;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\Font;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use App\Jobs\ProcessIndiaPostExcel;
use App\Jobs\PreviewIndiaPostExcel;
use Illuminate\Support\Facades\Bus;


class IndiaPostSpeedPostController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (Auth::guard("franchise")->check()) {
                $user = Auth::guard("franchise")->user();
            } elseif (Auth::guard("franchiseRoleUser")->check()) {
                $user = Auth::guard("franchiseRoleUser")->user();
            } else {
                return abort(403, "Unauthorized.");
            }

            $action = $request->route()->getActionMethod();

            $actionToPermissionMap = [
                "index" => "Bookings-view",
                "create" => "Bookings-create",
                "store" => "Bookings-create",
                "edit" => "Bookings-edit",
                "delete" => "Bookings-delete",
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
            $serviceStatuses = Franchise::checkServiceStatus(
                IndiaPostSpeedPostParcel::SERVICE_TYPE_INDIA_POST_SPEED
            );

            // Check if at least one service status is set to 1
            if (!$serviceStatuses) {
                return abort(403, "Service not available.");
            }

            return $next($request);
        });
    }

    public function getBarcodeAvailableCount()
    {
        $serviceType = IndiaPostSpeedPostParcel::SERVICE_TYPE_INDIA_POST_SPEED;
        $serviceTypeValue = IndiaPostSpeedPostParcel::getServiceTypeDB(
            $serviceType
        );

        $range_start_column = "parcel_barcode_range_start_{$serviceTypeValue}";
        $range_end_column = "parcel_barcode_range_end_{$serviceTypeValue}";
        $last_code_issued_column = "last_parcel_code_issued_{$serviceTypeValue}";

        $franchiseId = Franchise::getFranchiseId();
        $franchiseSeriesDetails = FranchiseBarcodeSeries::where(
            "franchise_id",
            $franchiseId
        )->first();

        if (
            !$franchiseSeriesDetails ||
            $franchiseSeriesDetails->{$range_end_column} === null
        ) {
            return 0; // No barcodes available
        }

        $start = (int) $franchiseSeriesDetails->{$range_start_column};
        $end = (int) $franchiseSeriesDetails->{$range_end_column};
        $lastIssued =
            (int) ($franchiseSeriesDetails->{$last_code_issued_column} ??
                $start - 1);

        // Calculate available barcodes
        return max(0, $end - $lastIssued);
    }

    public function index(Request $request, RateCalculator $rateCalculator)
    {

        $totalParcels = 0;
        $totalAmount = 0;
        $CodtotalAmount = 0;
        $type = $request->type;
        $searchKey = $request->input("searchKey");

        $fromdate = $request->input("fromdate");
        $todate = $request->input("todate");
        $franchise = Franchise::getFranchiseId();

        $query = IndiaPostSpeedPostParcel::where("franchise_id", Franchise::getFranchiseId())
       
            // ->where("insert_type", $request->insert_type)
            ->where("status", 0)
            ->orderBy("created_at", "desc");

        if ($request->type == "cod") {
            $query->where("payment_method", "cod");
        } else {
            $query->where("payment_method", "prepaid");
        }

        if ($searchKey) {
            $query->where(function ($q) use ($searchKey) {
                $q->where("pickup_name", "LIKE", "%{$searchKey}%")

                    ->orWhere("pickup_mobile", "LIKE", "%{$searchKey}%")

                    ->orWhere("pickup_email", "LIKE", "%{$searchKey}%")

                    ->orWhere("pickup_pincode", "LIKE", "%{$searchKey}%")

                    ->orWhere("pickup_city", "LIKE", "%{$searchKey}%")

                    ->orWhere("pickup_state", "LIKE", "%{$searchKey}%")

                    ->orWhere("pickup_address", "LIKE", "%{$searchKey}%")

                    ->orWhere("consignee_name", "LIKE", "%{$searchKey}%")

                    ->orWhere("consignee_mobile", "LIKE", "%{$searchKey}%")

                    ->orWhere("consignee_email", "LIKE", "%{$searchKey}%")

                    ->orWhere("consignee_pincode", "LIKE", "%{$searchKey}%")

                    ->orWhere("consignee_city", "LIKE", "%{$searchKey}%")

                    ->orWhere("consignee_state", "LIKE", "%{$searchKey}%")

                    ->orWhere("consignee_address", "LIKE", "%{$searchKey}%")

                    ->orWhere("barcode_no", "LIKE", "%{$searchKey}%");
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
                    $todate = Carbon::createFromFormat("d-m-Y",$todate)->toDateString();
                    $query->whereDate("created_at", $todate);
                }
            }

            $datas = $query->get();
            $CodtotalAmount = (clone $query)->sum("cod_amount");
            $totalAmount = (clone $query)->sum("payment_amount");
            $totalParcels = (clone $query)->count();

            return view(
                "franchise.indiaPost-speedPost.index",
                compact(
                    "datas",
                    "type",
                    "totalAmount",
                    "totalParcels",
                    "CodtotalAmount",
                    "franchise"
                )
            );
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
            $datas = $query->get();
            $CodtotalAmount = (clone $query)->sum("cod_amount");
            $totalAmount = (clone $query)->sum("payment_amount");
            $totalParcels = (clone $query)->count();
            return view(
                "franchise.indiaPost-speedPost.index",
                compact(
                    "datas",
                    "type",
                    "totalAmount",
                    "totalParcels",
                    "CodtotalAmount",
                    "franchise"
                )
            );
        } else {
            $query->whereDate("created_at", Carbon::today());
            $datas = $query->get();
            $CodtotalAmount = (clone $query)->sum("cod_amount");
            $totalAmount = (clone $query)->sum("payment_amount");
            $totalParcels = (clone $query)->count();

            return view(
                "franchise.indiaPost-speedPost.index",
                compact(
                    "datas",
                    "type",
                    "totalAmount",
                    "totalParcels",
                    "CodtotalAmount",
                    "franchise"
                )
            );
        }
    }

  public function getPrice($origin, $destination, $weight, $fuel_charge = 0, $pickup_charge = 0, $other_service_charge = 0, $register_amount = 0) {
    
    // ✅ Ensure weight is in correct format (grams)
    $weight = floatval($weight);
    
    // ✅ Get base price from rate calculator
    $request = new Request([
        "originPincode" => $origin,
        "destinationPincode" => $destination,
        "packageWeight" => $weight, // Weight in grams
        "service_type" => IndiaPostSpeedPostParcel::SERVICE_TYPE_INDIA_POST_SPEED,
    ]);
    
    $rateCalculator = new RateCalculator();
    $result = $rateCalculator->calculate($request);
    
    if ($result['status'] === 'fail') {
        return $result;
    }
    
    // ✅ Extract base price from result
    $basePrice = $result['price'] ?? 0;
    
    // ✅ Add additional charges BEFORE GST
    $subTotal = $basePrice + $fuel_charge + $pickup_charge + $other_service_charge + $register_amount;
    
    // ✅ Calculate GST (18% on base price + additional charges)
    $gst = $subTotal * 0.18;
    
    // ✅ Final total
    $total = $subTotal + $gst;
    
    return [
        'status' => 'success',
        'price' => round($basePrice, 2),
        'fuel_charge' => $fuel_charge,
        'pickup_charge' => $pickup_charge,
        'other_service_charge' => $other_service_charge,
        'register_amount' => $register_amount,
        'subtotal' => round($subTotal, 2),
        'gst' => round($gst, 2),
        'total' => round($total, 2),
        'weight_used' => $weight,
        'message' => 'Price calculated successfully'
    ];
}

    public function showPrice(Request $request, RateCalculator $rateCalculater)
    {
        
        if ($request->isMethod("post")) {
            try {
                // if ($request->amount) {
                //     $result = $rateCalculater->calculateByAmount($request);
                //     return response()->json(['status' => 'success', 'data' => $result, 'message' => "data fetched successfully"]);
                // } else {

                $from = $request->from;
                $to = $request->to;
                $weight = $request->weight;
                $fuel_charge = $request->fuel_charge ?? 0;
                $pickup_charge = $request->pickup_charge ?? 0;
                $other_service_charge = $request->other_service_charge ?? 0;
                $register_amount = $request->register_amount ?? 0;
                $result = $this->getPrice($from, $to, $weight, $fuel_charge, $pickup_charge, $other_service_charge, $register_amount);

                return response()->json([
                    "status" => "success",
                    "data" => $result,
                    "message" => "data fetched successfully",
                ]);
                // }
            } catch (\Throwable $th) {
                return response()->json(
                    ["status" => "error", "message" => $th->getMessage()],
                    500
                );
            }
        }

        return response()->json(
            ["status" => "error", "message" => "Invalid request method"],
            400
        );
    }

    public function create()
    {
        $franchise_details = Franchise::where("id", Franchise::getFranchiseId())
            ->select("franchise_no","india_credit_amount","credit_balance","indiapost_balance","gst_number")->first();
        $linkDetails = IndiaPostLink::where("franchise_no", $franchise_details->franchise_no)->first();
        $pickupDetails = ECustomer::where("cph_link", Franchise::getFranchiseId())
            ->where("status", 1)
            ->get();
        $barcodeAvailable = $this->getBarcodeAvailableCount();

        return view("franchise.indiaPost-speedPost.create", [
            "pickupDetails" => $pickupDetails,

            "franchise_details" => $franchise_details,
            "linkDetails" => $linkDetails,
            "barcodeAvailable" => $barcodeAvailable,
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
        $amount =
            $net_price -
            ($fuel_charge + $pickup_charge + $other_service_charge);
        $gst = number_format($net_price * 0.18, 2, ".", "");
        $net_price_formatted = number_format($net_price, 2, ".", "");

        $rateDetails = [
            "fuel_charge" => $fuel_charge,
            "pickup_charge" => $pickup_charge,
            "other_service_charge" => $other_service_charge,
            "total_payment_amount" => number_format(
                $total_payment_amount,
                2,
                ".",
                ""
            ),
            "net_price" => $net_price_formatted,
            "amount" => number_format($amount, 2, ".", ""),
            "gst" => $gst,
        ];

        $pdf = PDF::loadView("franchise.mail.pdfview", [
            "parcel" => $parcel,
            "barcode" => $barcode,
            "rateDetails" => $rateDetails,
        ]);
        $destinationPath = public_path("tenancy/assets/franchise/Pdf/");

        $fileName = uniqid() . "_parcel_" . ".pdf";
        $filePath = $destinationPath . $fileName;
        $pdf->save($filePath);

        return $filePath;
    }

    public function sendNotificationToUser($franchise, $parcel)
    {
        try {
            $service_type = IndiaPostSpeedPostParcel::SERVICE_TYPE_INDIA_POST_SPEED;
            $link = route("franchise.parcel.receipt.download", [$service_type,$parcel->id]);
            \Log::info("sendNotificationToUser function called", ["parcel_id" => $parcel->id ?? null, "franchise_id" => $franchise->id ?? null, "receipt_link" => $link, "type" => $service_type,]);

            \Log::info("Fetching user with phone: {$parcel->pickup_mobile}");

            $userPhone = $parcel->pickup_mobile;
            $user = User::where("phone", $userPhone)->first();

            if (!$user) {
                \Log::error("User not found for phone: {$userPhone}");
                return;
            }

            \Log::info("User found", ["user_id" => $user->id]);

            $token = $user->fcm_token;
            if ($token) {
                \Log::info("FCM token found", ["user_id" => $user->id, "fcm_token" => $token,]);

                $title = FranchiseBag::getServiceType(IndiaPostSpeedPostParcel::SERVICE_TYPE_INDIA_POST_SPEED);
                $body =
                    "✅ Order Placed: Article No. {$parcel->barcode_no}\n" .
                    "📅 Date: " .
                    date("M d, Y, h:i A", strtotime($parcel->created_at)) .
                    "\n" .
                    "📍 From: {$parcel->pickup_address}\n" .
                    "📍 To: {$parcel->consignee_address}\n" .
                    "💰 Amount: ₹{$parcel->payment_amount}\n" .
                    "🧾 Receipt: {$link}\n" .
                    "🚀 Your order is placed successfully and will be processed soon!";

                \Log::info("Preparing to send push notification", ["title" => $title, "body" => $body, "user_id" => $user->id, "parcel_id" => $parcel->id, "link" => $link,]);

                $notification = new FranchisePushNotification($token, $parcel, $title, $body);
                $notification->sendPushNotification();

                \Log::info("Push notification sent successfully", ["user_id" => $user->id,]);

                \Log::info("Saving user notification record");
                 $links = NULL;
                if($franchise->id == 238){
                    $links = NuLL;
                }else{
                    $links = $link;
                }

                $parcel->userNotifications()->create([
                    "user_id" => $user->id,
                    "service_type" => get_class($parcel),
                    "parcel_id" => $parcel->id,
                    "message" => "Order Placed successfully",
                    "barcode_no" => $parcel->barcode_no,
                    "body" => $body,
                    "current_location" => $franchise->address,
                    'link'             => $links,
                ]);

                \Log::info("User notification record created successfully");
            } else {
                \Log::error("FCM token not found for user", [
                    "user_id" => $user->id,
                ]);
            }
        } catch (\Exception $e) {
            \Log::error(
                "Error in sendNotificationToUser: " . $e->getMessage(),
                [
                    "parcel_id" => $parcel->id ?? null,
                    "user_phone" => $userPhone ?? null,
                    "exception" => $e,
                ]
            );
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
            $title = FranchiseBag::getServiceType(IndiaPostSpeedPostParcel::SERVICE_TYPE_INDIA_POST_SPEED);
            $body =
                "✅ Order Placed: Article No. {$parcel->barcode_no}\n" .
                "📅 Date: " .
                date("M d, Y, h:i A", strtotime($parcel->created_at)) .
                "\n" .
                "📍 From: {$parcel->pickup_address}\n" .
                "📍 To: {$parcel->consignee_address}\n" .
                "💰 Amount: ₹{$parcel->payment_amount}\n" .
                "🚀 Your order is placed successfully";

            // Send Push Notification

            $notification = new FranchisePushNotification($token, $parcel, $title, $body);
            $notification->sendPushNotification();
            \Log::info("notification  sent to franchise");

            $parcel->franchiseNotifications()->create([
                "franchise_id" => $franchiseId,
                "service_type" => get_class($parcel),
                "parcel_id" => $parcel->id,
                "message" => "Order Placed successfully",
                "barcode_no" => $parcel->barcode_no,
                "body" => $body,
                "current_location" => $franchise->address,
            ]);

            \Log::info("franchise notification created");
        } catch (\Exception $e) {
            \Log::error(
                "Error in sendNotificationToFranchise: " . $e->getMessage(),
                [
                    "parcel_id" => $parcel->id ?? "N/A",
                    "franchise_id" => $franchiseId ?? "N/A",
                    "exception" => $e,
                ]
            );
        }
    }

    public function store(Request $request, RateCalculator $rateCalculater)
{
    $payment_type = null;
    $requestData = $request->all();

    $this->validate($request, [
        "PickupName" => "required|string|max:191",
        "PickupMobile" => "required|string",
        "PickupPincode" => "required|string|max:6",
        "PickupCity" => "required|string|max:191",
        "PickupState" => "required|string|max:191",
        "PickupAddress" => "required|string|max:255",
        "ConsigneeName" => "required|string|max:191",
        "ConsigneePincode" => "required|string|max:6",
        "ConsigneeCity" => "required|string|max:191",
        "ConsigneeState" => "required|string|max:191",
        "ConsigneeAddress" => "required|string|max:255",
        "package_weight" => "required|numeric",
    ]);

    $print = $request->printNumber ?: 1;

    $franchiseId = Franchise::getFranchiseId();
    $franchise = Franchise::findOrFail($franchiseId);
    $linkDetail = IndiaPostLink::where("franchise_no", $franchise->franchise_no)->first();

    $from = $request->PickupPincode;
    $to = $request->ConsigneePincode;
    $weight = $request->total_weight;
    $register_amount = $request->register_amount ?? 0;
    
    $rateDetails = $this->getPrice($from, $to, $weight, 0, 0, 0, $register_amount);

    // Barcode Logic
    $todate = Carbon::today()->toDateString();
    $barcode_no = $request->scanned_barcode_no ?: $this->getNextBarcode();
    
    $existingParcel = IndiaPostSpeedPostParcel::where("barcode_no", $barcode_no)
        ->whereDate("created_at", $todate)
        ->first();

    if ($existingParcel) {
        return response()->json([
            "status" => 400,
            "message" => "Barcode Already Exist",
        ]);
    }

    $generator = new BarcodeGeneratorPNG();
    $barcode = $generator->getBarcode($barcode_no, $generator::TYPE_CODE_128);
    $barcode_image_src = base64_encode($barcode);

    // Cast payment amount to float for math safety
    $payment_amount = (float)($rateDetails["total"] ?? 0);
    $cod_amount = (float)($request->amount ?? 0);

    if ($rateDetails["status"] == "fail") {
        return response()->json([
            "status" => 400,
            "message" => $rateDetails["message"],
            "data" => $requestData,
        ]);
    }

    if ($payment_amount <= 0) {
        return response()->json([
            "status" => 400,
            "showMessage" => "1",
            "message" => "Service Not Available",
            "data" => $requestData,
        ]);
    }

    // Balance Checks - Cast to float
    $gotogo_balance = (float)($franchise->indiapost_balance ?? 0);
    $credit_balance = (float)($franchise->india_credit_amount ?? 0);
    $indiapost_balance = $gotogo_balance + $credit_balance;

    if ($indiapost_balance < $payment_amount) {
        return response()->json([
            "status" => 400,
            "showMessage" => "1",
            "message" => "Balance Low",
            "data" => $requestData,
        ]);
    }

    if (empty($barcode_no)) {
        return response()->json([
            "status" => 400,
            "showMessage" => "1",
            "message" => "Barcode Not Available",
            "data" => $requestData,
        ]);
    }

    try {
        $franchise_role_user_id = Auth::guard("franchiseRoleUser")->check() ? Auth::guard("franchiseRoleUser")->user()->id : null;

        $cod_parcel_id = null;
        if ($request->payment_method == "franchise" && $request->payment_type_cod == "prepaid") {
            $cod_parcel_id = $this->getOrCreateCodUser($request, $franchise);
        } elseif ($request->payment_method != "franchise" && $request->payment_type_cod == "prepaid") {
            $cod_parcel_id = $request->pickupDetails;
        }

        if (in_array($request->payment_method, ["manager", "pickup"])) {
            $payment_type = $request->payment_type;
        }

        $pickup_details = ($request->payment_type_cod == "cod") ? $request->pickupDetails : null;

        $parcel = IndiaPostSpeedPostParcel::create([
            "franchise_id" => $franchiseId,
            "franchise_role_users_id" => $franchise_role_user_id,
            "cod_customer_id" => $pickup_details,
            "no_r_customer_id" => $cod_parcel_id,
            "booking_type" => $request->payment_method,
            "pickup_name" => $request->PickupName,
            "pickup_mobile" => $request->PickupMobile,
            "pickup_gst_number" => $request->PickupGstNo,
            "pickup_email" => $request->PickupEmail ?? null,
            "pickup_pincode" => $request->PickupPincode,
            "pickup_city" => $request->PickupCity,
            "pickup_state" => $request->PickupState,
            "pickup_address" => $request->PickupAddress,
            "consignee_name" => $request->ConsigneeName,
            "consignee_mobile" => $request->ConsigneeMobile,
            "consignee_gst_number" => $request->ConsigneeGstNo ?? null,
            "consignee_email" => $request->ConsigneeEmail ?? null,
            "consignee_pincode" => $request->ConsigneePincode,
            "consignee_city" => $request->ConsigneeCity,
            "consignee_state" => $request->ConsigneeState,
            "consignee_address" => $request->ConsigneeAddress,
            "package_weight" => $request->package_weight,
            "package_length" => $request->package_length,
            "package_width" => $request->package_width,
            "package_height" => $request->package_height,
            "fuel_charge" => $request->fuel_charge ?? 0,
            "pickup_charge" => $request->pickup_charge ?? 0,
            "other_service_charge" => $request->other_service_charge ?? 0,
            "payment_amount" => $payment_amount,
            "cod_amount" => $cod_amount,
            "payment_method" => $request->payment_type_cod,
            "totalOtherAmount" => $request->totalOther ?? 0,
            "register_amount" => $request->register_amount ?? 0,
            "barcode_no" => $barcode_no,
            "barcode_image_src" => $barcode_image_src,
            "insert_type" => IndiaPostSpeedPostParcel::INSERT_TYPE_SINGLE,
        ]);

        IndiaPostSpeedPostTrackOrder::create([
            "parcel_id" => $parcel->id,
            "barcode_no" => $barcode_no,
            "source_franchise_id" => $franchiseId,
            "order_placed_datetime" => now(),
            "source_franchise_location" => $franchise->address,
        ]);

        // Commission Logic Fixes
        $commission = (float)$rateCalculater->calculateCommissionForIndiaPost($request->package_weight, $payment_amount, IndiaPostSpeedPostParcel::SERVICE_TYPE_INDIA_POST_SPEED);
        $marketcommission = 0.0;

        if (in_array($request->payment_method, ["manager", "pickup"])) {
            $marketcommission = (float)$rateCalculater->calculateCommissionForIndiaPostMarket($payment_type, $payment_amount);
            $commission = $commission - $marketcommission; // Safe subtraction
        }

        // Wallet Deduction
        if ($gotogo_balance > $payment_amount) {
            $franchise->decrement("indiapost_balance", $payment_amount);
        } else {
            $franchise->decrement("india_credit_amount", $payment_amount);
        }

        FranchiseCommissionDetail::create([
            "franchise_id" => $franchiseId,
            "service_type" => IndiaPostSpeedPostParcel::SERVICE_TYPE_INDIA_POST_SPEED,
            "amount" => $payment_amount / 1.18,
            "commission" => number_format($commission, 2, ".", ""),
            "payment_method" => $request->payment_type_cod,
        ]);

        if (in_array($request->payment_method, ["manager", "pickup"])) {
            ManagerCommissionDetail::create([
                "commission_id" => $payment_type,
                "servicetype" => IndiaPostSpeedPostParcel::SERVICE_TYPE_INDIA_POST_SPEED,
                "amount" => $payment_amount / 1.18,
                "commission" => number_format($marketcommission, 2, ".", ""),
                "type" => $request->payment_method,
                "payment_method" => $request->payment_type_cod,
            ]);
        }

        // Barcode Series Logic Fix (The common point of failure)
        $serviceTypeValue = IndiaPostSpeedPostParcel::getServiceTypeDB(IndiaPostSpeedPostParcel::SERVICE_TYPE_INDIA_POST_SPEED);
        $range_start_column = "parcel_barcode_range_start_{$serviceTypeValue}";
        $last_code_issued_column = "last_parcel_code_issued_{$serviceTypeValue}";

        $franchiseSeriesDetails = FranchiseBarcodeSeries::where("franchise_id", $franchiseId)->first();

        if (!empty($franchiseSeriesDetails->{$last_code_issued_column})) {
            $seriesNum = (int)$franchiseSeriesDetails->{$last_code_issued_column} + 1;
        } else {
            $seriesNum = (int)($franchiseSeriesDetails->{$range_start_column} ?? 0);
        }

        // Update Series
        $franchiseSeriesDetails->{$last_code_issued_column} = $seriesNum;
        $franchiseSeriesDetails->save();

        // Update Pickup Status if applicable
        if ($request["pickup-details"]) {
            PickupDetails::where("id", $request["pickup-details"])->update(["status" => 2]);
        }

        // Notifications & PDF (Silently Log Errors)
        try { $this->sendNotificationToUser($franchise, $parcel); } catch (\Exception $e) { \Log::error("Notification Error: " . $e->getMessage()); }
        
        $savedPdfFilePath = null;
        try { $savedPdfFilePath = $this->savePdf($parcel); } catch (\Exception $e) { \Log::error("PDF Error: " . $e->getMessage()); }

        if ($savedPdfFilePath && $request->PickupEmail) {
            try { Mail::to($request->PickupEmail)->send(new ParcelMail([$savedPdfFilePath, $parcel])); } catch (\Exception $e) { \Log::error("Mail Error: " . $e->getMessage()); }
        }

        try {
            $notification = new SMSNotification($request->ConsigneeMobile, "ORDER", [$barcode_no, now()]);
            $notification->sendMessage();
        } catch (\Exception $e) { \Log::error("SMS Error: " . $e->getMessage()); }

        // Save Barcode Record
        $franchiseBarcode = new FranchiseBarcodes();
        $franchiseBarcode->barcodes = $barcode_no;
        $franchiseBarcode->franchise_barcodeseries_id = $franchiseSeriesDetails->id;
        $franchiseBarcode->save();

        return response()->json([
            "status" => 200,
            "message" => "New Parcel Create",
            "parcel_id" => $parcel->id,
            "data" => $requestData,
            "barcodeImageSrc" => $barcode_image_src,
            "franchiseDetails" => $franchise,
            "barcode_available" => $this->getBarcodeAvailableCount(),
            "payment_method" => $request->payment_method,
            "customer_id" => $request->pickupDetails,
            "payment_type_cod" => $request->payment_type_cod,
            "payment_type" => $request->payment_type,
        ]);

    } catch (\Exception $th) {
        \Log::error("Store Exception: " . $th->getMessage() . " at " . $th->getLine());
        return response()->json([
            "status" => 500,
            "message" => $th->getMessage()
        ], 500);
    }
}
    public function view($id)
    {
        $data = IndiaPostSpeedPostParcel::findorfail($id);

        $fuel_charge = $data->fuel_charge;
        $pickup_charge = $data->pickup_charge;
        $other_service_charge = $data->other_service_charge;
        $total_payment_amount = $data->payment_amount;
        $net_price = $total_payment_amount / 1.18;
        $amount =
            $net_price -
            ($fuel_charge + $pickup_charge + $other_service_charge);
        $gst = number_format($net_price * 0.18, 2, ".", "");
        $net_price_formatted = number_format($net_price, 2, ".", "");

        $rateDetails = [
            "fuel_charge" => $fuel_charge,
            "pickup_charge" => $pickup_charge,
            "other_service_charge" => $other_service_charge,
            "total_payment_amount" => number_format(
                $total_payment_amount,
                2,
                ".",
                ""
            ),
            "net_price" => $net_price_formatted,
            "amount" => number_format($amount, 2, ".", ""),
            "gst" => $gst,
        ];

        return view(
            "franchise.indiaPost-speedPost.view",
            compact("data", "rateDetails")
        );
    }

    public function edit(Request $request, $id)
    {
        $post = IndiaPostSpeedPostParcel::findOrFail($id);

        if ($request->isMethod("POST")) {
            $this->validate($request, [
                // Pickup details validation rules

                "PickupName" => "required|string|max:191",

                "PickupMobile" => "required|string",

                // 'PickupEmail' => 'required|email|max:191',

                "PickupPincode" => "required|string|max:6",

                "PickupCity" => "required|string|max:191",

                "PickupState" => "required|string|max:191",

                "PickupAddress" => "required|string|max:255",

                // Consignee details validation rules

                "ConsigneeName" => "required|string|max:191",

                // 'ConsigneeMobile' => 'required|string',

                // 'ConsigneeEmail' => 'required|email|max:191',

                "ConsigneePincode" => "required|string|max:6",

                "ConsigneeCity" => "required|string|max:191",

                "ConsigneeState" => "required|string|max:191",

                "ConsigneeAddress" => "required|string|max:255",

                // Parcel details validation rules

                "package_weight" => "required|numeric",
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

                return redirect()
                    ->route("franchise.india-post-speed-post.index", [
                        "insert_type" => $post->insert_type,
                    ])
                    ->with("success", "Parcel updated successfully!");
            } catch (\Exception $th) {
                return back()
                    ->with("error", $th->getMessage())
                    ->withInput();
            }
        }

        $data = $post;

        $pickupDetails = PickupDetails::where(
            "franchise_id",
            Franchise::getFranchiseId()
        )
        ->get();

        return view(
            "franchise.indiaPost-speedPost.edit",
            compact("data", "pickupDetails")
        );
    }

   public function delete($id)
{
    try {
        $franchiseId = Franchise::getFranchiseId();
        $data = IndiaPostSpeedPostParcel::findOrFail($id);  // Find the parcel, throws error if not found
        
        // Find the franchise by ID
        $franchise = Franchise::findOrFail($franchiseId);
        
        // Increment the india_credit_amount with the payment amount from the parcel
        $franchise->increment("indiapost_balance", $data->payment_amount);
        
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
            $filePath = public_path("admin/assets/file/excelFomatFile.xlsx");

            if (!file_exists($filePath)) {
                return back()->with("error", "File not found");
            }

            return response()->download($filePath, "excelFomatFile.xlsx");
        } catch (\Exception $e) {
            return back()->with(
                "error",
                "Error downloading file: " . $e->getMessage()
            );
        }
    }
public function previewByFile(Request $request)
{
    try {

        if (!$request->hasFile('file')) {
            return response()->json([
                'status' => false,
                'message' => 'No file uploaded'
            ], 400);
        }

        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load(
            $request->file('file')->getPathname()
        );

        $sheet = $spreadsheet->getActiveSheet();

        $totalRows   = 0;
        $successRows = 0;
        $failedRows  = 0;
        $totalAmount = 0;

        $header = null;

        foreach ($sheet->getRowIterator() as $rowIndex => $row) {

            $cells = [];
            foreach ($row->getCellIterator() as $cell) {
                $cells[] = trim((string) $cell->getValue());
            }

            if ($rowIndex === 1) {
                $header = $cells;
                continue;
            }

            if (empty(array_filter($cells))) {
                continue;
            }

            $totalRows++;

            try {
                $data = array_combine($header, array_pad($cells, count($header), null));

                // 🔹 Basic validation example
                if (empty($data['Pickup Pincode']) || empty($data['Consignee Pincode'])) {
                    throw new \Exception('Pincode missing');
                }

                // 🔹 Amount calc example (optional)
                $totalAmount += (float) ($data['Amount'] ?? 0);

                $successRows++;

            } catch (\Exception $e) {
                $failedRows++;
            }
        }

        return response()->json([
            'status'  => true,
            'total'   => $totalRows,
            'success' => $successRows,
            'failed'  => $failedRows,
            'amount'  => number_format($totalAmount, 2)
        ]);

    } catch (\Throwable $e) {
        \Log::error('Preview error: '.$e->getMessage());

        return response()->json([
            'status' => false,
            'message' => $e->getMessage()
        ], 500);
    }
}




public function getPreviewResult($key)
{

    $data = Cache::get($key);
    if (!$data) {
        return response()->json([
            'status' => false,
            'message' => 'Preview not ready'
        ], 404);
    }

    return response()->json([
        'status' => true,
        'summary' => $data['summary'],
        'success_rows' => $data['success_rows'],
        'failed_rows' => $data['failed_rows'],
    ]);
}


public function storeByFile(Request $request, RateCalculator $rateCalculater) {
    if (!$request->hasFile("file")) {
        return response()->json(['status' => 'error', 'message' => 'No file uploaded.'], 400);
    }

    $franchiseId = Franchise::getFranchiseId();
    $franchise = Franchise::findOrFail($franchiseId);
    $barcodeOption = $request->barcode_option; 
    
    $successCount = 0;
    $failCount = 0;
    $failedRowsData = [];
    $successRows = [];
    $totalPaymentAmount = 0;

    try {
        $file = $request->file("file");
        $destinationPath = public_path("tenancy/assets/franchise/RoleUser/");
        $fileName = uniqid() . "_" . $file->getClientOriginalName();
        $file->move($destinationPath, $fileName);
        $filePath = $destinationPath . $fileName;

        $spreadsheet = IOFactory::load($filePath);
        $sheet = $spreadsheet->getActiveSheet();
        $data = [];
        $header = null;

        foreach ($sheet->getRowIterator() as $row) {
            $rowData = [];
            $hasData = false;
            foreach ($row->getCellIterator() as $cell) {
                $cellValue = $cell->getValue();
                $rowData[] = $cellValue;
                if (!is_null($cellValue) && $cellValue !== "") { $hasData = true; }
            }
            if ($hasData) {
                if (is_null($header)) { $header = $rowData; } 
                else { $data[] = array_combine($header, array_pad($rowData, count($header), null)); }
            }
        }

        // --- VALIDATE BARCODE LOGIC BEFORE PROCESSING ---
        foreach ($data as $index => $element) {
            if ($index === 0 && isset($element["Pickup Name"]) && $element["Pickup Name"] === "Pickup Name") continue;
            
            $excelBarcode = isset($element["Barcode"]) ? trim($element["Barcode"]) : '';
            
            // Strict validation - FIXED LOGIC
            if ($barcodeOption === "barcode_auto" && !empty($excelBarcode)) {
                throw new \Exception("Row " . ($index + 1) . ": Barcode '$excelBarcode' present in Excel. Please select 'Custom Barcode' option or remove barcodes from Excel file.");
            }
            
            if ($barcodeOption === "barcode_custom" && empty($excelBarcode)) {
                throw new \Exception("Row " . ($index + 1) . ": Barcode is missing in Excel. Please select 'Auto Generate' option or provide barcode in Excel.");
            }
        }

        foreach ($data as $index => $element) {
            try {
                if ($index === 0 && isset($element["Pickup Name"]) && $element["Pickup Name"] === "Pickup Name") continue;

                // Get barcode from Excel
                $excelBarcode = isset($element["Barcode"]) ? trim($element["Barcode"]) : '';
                
                // ✅ 1. BARCODE VALIDATION (redundant but safe)
                $code = null;
                if ($barcodeOption === "barcode_custom") {
                    // For custom barcode, use the Excel barcode
                    $code = $excelBarcode;
                    
                    // Validate custom barcode is not empty (should already be validated above)
                    if (empty($code)) {
                        throw new \Exception("Barcode is blank. Custom mode requires a value.");
                    }
                    
                    // Duplicate Check for Today
                    $existing = IndiaPostSpeedPostParcel::where("barcode_no", $code)
                                ->whereDate("created_at", Carbon::today())->first();
                    if ($existing) {
                        throw new \Exception("Barcode $code already used today.");
                    }
                } else {
                    // Auto generate barcode - Excel should be empty
                    $code = $this->getNextBarcode();
                }

                // 2. Field Validation
                if (empty($element["Pickup Pincode"])) throw new \Exception("Pickup Pincode required");
                if (empty($element["Consignee Pincode"])) throw new \Exception("Consignee Pincode required");
                if (empty($element["Package Weight"])) throw new \Exception("Weight required");

                // 3. Price Calculation
                $actualWeight = floatval($element["Package Weight"] ?? 0);
                $length = floatval($element["Package Length"] ?? 0);
                $width = floatval($element["Package Width"] ?? 0);
                $height = floatval($element["Package Height"] ?? 0);
                $volumetricWeight = ($length && $width && $height) ? (($length * $width * $height) / 6000) * 1000 : 0;
                $totalWeight = round(max($actualWeight, $volumetricWeight));

                $priceWithCharges = $this->getPrice(
                    trim($element["Pickup Pincode"]), trim($element["Consignee Pincode"]), $totalWeight, 
                    floatval($element["Fuel Charge"] ?? 0), floatval($element["Pickup Charge"] ?? 0), 
                    floatval($element["Other service Charge"] ?? 0), floatval($element["Register Fee"] ?? 0)
                );
                
                if ($priceWithCharges["status"] == "fail") throw new \Exception($priceWithCharges["message"]);

                $payment_amount = (float)$priceWithCharges["total"];

                // 4. Balance Check
                $indiapost_balance = (float)$franchise->indiapost_balance + (float)$franchise->india_credit_amount;
                if ($indiapost_balance < $payment_amount) throw new \Exception('Insufficient balance');

                // Success Data
                $element['barcode'] = $code;
                $element['calculated_weight'] = $totalWeight;
                $element['calculated_price'] = $payment_amount;
                
                $totalPaymentAmount += $payment_amount;
                $successRows[] = $element;
                $successCount++;

            } catch (\Exception $e) {
                $failCount++;
                $element["Error Reason"] = $e->getMessage();
                $failedRowsData[] = $element;
            }
        }

        session(['excel_preview_data' => [
            'success_rows' => $successRows,
            'barcode_option' => $barcodeOption,
            'franchise_id' => $franchiseId,
        ]]);

        return response()->json([
            'status' => 'success',
            'data' => [
                'success_rows' => $successRows,
                'failed_rows' => $failedRowsData,
                'summary' => [
                    'total_rows' => count($data),
                    'success' => $successCount,
                    'failed' => $failCount,
                    'total_amount' => round($totalPaymentAmount, 2),
                ]
            ]
        ]);
    } catch (\Exception $e) {
        return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
    }
}
public function confirmAndStoreExcel(Request $request, RateCalculator $rateCalculater) {
    $previewData = session('excel_preview_data');
    
    if (!$previewData) {
        return response()->json([
            'status' => 'error',
            'message' => 'No preview data found. Please upload file again.'
        ], 400);
    }

    $franchiseId = Franchise::getFranchiseId();
    $franchise = Franchise::findOrFail($franchiseId);
    $barcodeOption = $previewData['barcode_option'] ?? 'barcode_auto';
    
    $successCount = 0;
    $failCount = 0;
    $failedRows = [];

    foreach ($previewData['success_rows'] as $element) {
        try {
            // 1. Customer resolution
            $customer = $this->resolveCustomer($element, $franchiseId);
            if (!$customer) {
                throw new \Exception('Customer not found');
            }

            // 2. Pricing & Weight (Recalculated for safety)
            $actualWeight = floatval($element["Package Weight"] ?? 0);
            $length = floatval($element["Package Length"] ?? 0);
            $width = floatval($element["Package Width"] ?? 0);
            $height = floatval($element["Package Height"] ?? 0);
            
            $volumetricWeight = ($length && $width && $height) ? (($length * $width * $height) / 6000) * 1000 : 0;
            $totalWeight = round(max($actualWeight, $volumetricWeight));

            $payment_amount = floatval($element['calculated_price'] ?? 0);

            // 3. Balance check
            $indiapost_balance = floatval($franchise->indiapost_balance ?? 0) + floatval($franchise->india_credit_amount ?? 0);
            if ($indiapost_balance < $payment_amount) {
                throw new \Exception('Low balance');
            }

            // 4. BARCODE LOGIC - UPDATED FOR STRICT BLANK CHECK
            $code = null;
            if ($barcodeOption === "barcode_custom") {
                $code = trim($element["barcode"] ?? ''); // Use 'barcode' key from success_rows
                if (empty($code)) {
                    throw new \Exception('Custom barcode is missing or blank');
                }
            } else {
                $code = $this->getNextBarcode();
            }

            // Duplicate Check
            $existingParcel = IndiaPostSpeedPostParcel::where("barcode_no", $code)
                ->whereDate("created_at", Carbon::today())
                ->first();
                
            if ($existingParcel) {
                throw new \Exception("Barcode $code already exists for today");
            }

            // 5. Generate Barcode Image
            $generator = new BarcodeGeneratorPNG();
            $barcode_img = base64_encode($generator->getBarcode($code, $generator::TYPE_CODE_128));

            // 6. Create Parcel
            $parcel = IndiaPostSpeedPostParcel::create([
                "franchise_id" => $franchiseId,
                "franchise_role_users_id" => Auth::guard('franchiseRoleUser')->user()->id ?? null,
                "no_r_customer_id" => $customer->id,
                "booking_type" => "franchise",
                "pickup_name" => $element["Pickup Name"],
                "pickup_mobile" => $element["Pickup Phone"],
                "pickup_pincode" => $element["Pickup Pincode"],
                "pickup_address" => $element["Pickup Address"],
                "consignee_name" => $element["Consignee Name"],
                "consignee_mobile" => $element["Consignee Phone"],
                "consignee_pincode" => $element["Consignee Pincode"],
                "consignee_address" => $element["Consignee Address"],
                "package_weight" => $element["Package Weight"],
                "payment_amount" => $payment_amount,
                "barcode_no" => $code,
                "barcode_image_src" => $barcode_img,
                "insert_type" => IndiaPostSpeedPostParcel::INSERT_TYPE_BULK,
            ]);

            // 7. Deduct Balance
            if (floatval($franchise->indiapost_balance) >= $payment_amount) {
                $franchise->decrement("indiapost_balance", $payment_amount);
            } else {
                $franchise->decrement("india_credit_amount", $payment_amount);
            }

            // 8. Update Series if Auto-Generated
            if ($barcodeOption === "barcode_auto") {
                $serviceTypeValue = IndiaPostSpeedPostParcel::getServiceTypeDB(IndiaPostSpeedPostParcel::SERVICE_TYPE_INDIA_POST_SPEED);
                $last_code_col = "last_parcel_code_issued_{$serviceTypeValue}";
                $series = FranchiseBarcodeSeries::where("franchise_id", $franchiseId)->first();
                
                if ($series) {
                    $series->{$last_code_col} = (int)$series->{$last_code_col} + 1;
                    $series->save();
                    
                    FranchiseBarcodes::create([
                        'barcodes' => $code, 
                        'franchise_barcodeseries_id' => $series->id
                    ]);
                }
            }

            $successCount++;

        } catch (\Exception $e) {
            $failCount++;
            $element["Error Reason"] = $e->getMessage();
            $failedRows[] = $element;
            \Log::error("Confirm Store Error: " . $e->getMessage());
        }
    }

    session()->forget('excel_preview_data');

    return response()->json([
        'status' => 'success',
        'message' => 'Processing complete',
        'data' => [
            'success_count' => $successCount,
            'fail_count' => $failCount,
            'failed_rows' => $failedRows
        ]
    ]);
}


 public function checkPreview($franchiseId)
{
    // 1. Fetch from Cache
    $cache = Cache::get("preview_excel_{$franchiseId}");

    if ($cache) {
        return response()->json([
            'status' => true,
            'data' => $cache // This contains our 'failed_rows' with the Barcode error messages
        ]);
    }

    // 2. Fallback if the job is still running or hasn't started
    return response()->json([
        'status' => false,
        'message' => 'Preview not ready or still processing...'
    ]);
}
    // Optional: Delete preview cache after showing
    public function deletePreview($franchiseId)
    {
        Cache::forget("preview_excel_{$franchiseId}");
        return response()->json([
            'status' => true,
            'message' => 'Preview cache deleted'
        ]);
    }

   


//     private function readExcel($file)
// {
//     $reader = IOFactory::createReaderForFile($file->getRealPath());
//     $reader->setReadDataOnly(true); // ✅ memory save

//     $spreadsheet = $reader->load($file->getRealPath());
//     $sheet = $spreadsheet->getActiveSheet();

//     $header = null;
//     $rows   = [];

//     foreach ($sheet->getRowIterator() as $row) {

//         $cellIterator = $row->getCellIterator();
//         $cellIterator->setIterateOnlyExistingCells(true); // ✅ blank skip

//         $rowData = [];
//         $hasData = false;

//         foreach ($cellIterator as $cell) {
//             $value = $cell->getValue();
//             $rowData[] = $value;

//             if ($value !== null && $value !== '') {
//                 $hasData = true;
//             }
//         }

//         if (!$hasData) continue;

//         if (!$header) {
//             $header = $rowData;
//         } else {
//             $rowData = array_pad($rowData, count($header), null);
//             $rows[] = array_combine($header, $rowData);
//         }
//     }

//     return $rows;
// }


//     public function previewByFile(Request $request, RateCalculator $rateCalculater)
// {  
    
    
//     if (!$request->hasFile('file')) {
//         return response()->json(['status' => false, 'msg' => 'No file']);
//     }

//     $successRows = [];
//     $failedRows  = [];
//     $totalAmount = 0;

//     // ✅ Excel Read (Same as your code)
//      $data = $this->readExcel($request->file('file'));

//     foreach ($data as $row) {
//         try {

//             // ✅ Customer check
//             $customer = $this->resolveCustomer($row, Franchise::getFranchiseId());
//             if (!$customer) {
//                 throw new \Exception('Customer not found');
//             }

//             // ✅ Weight Calculation
//             $weight = (float)$row['Package Weight'];
//             $rate = $this->getPrice(
//                 $row['Pickup Pincode'],
//                 $row['Consignee Pincode'],
//                 $weight,
//                 0,0,0,0
//             );

//             if ($rate['status'] === 'fail') {
//                 throw new \Exception('Rate failed');
//             }

//             $row['calculated_amount'] = $rate['total'];
//             $totalAmount += $rate['total'];

//             $successRows[] = $row;

//         } catch (\Exception $e) {
//             $row['Error Reason'] = $e->getMessage();
//             $failedRows[] = $row;
//         }
//     }

//     return response()->json([
//         'status' => true,
//         'summary' => [
//             'total_rows'   => count($data),
//             'success'      => count($successRows),
//             'failed'       => count($failedRows),
//             'total_amount' => round($totalAmount),
//         ],
//         'success_rows' => $successRows,
//         'failed_rows'  => $failedRows
//     ]);
// }


//     public function storeByFile(Request $request, RateCalculator $rateCalculater) {
       
//         if (!$request->hasFile("file")) {
//             // return 122;
//             return back()->with("error", "No file uploaded.");
//         }
//         //  return 333;
//         $franchiseId = Franchise::getFranchiseId();
//         $franchise = Franchise::findOrFail($franchiseId);

//         $successCount = 0;
//         $failCount = 0;
//         $failedRows = []; // failed data store
//         $payment_type = null;
//         $managertype = null;
//         try {
//             // Move uploaded file
//             $file = $request->file("file");
//             $destinationPath = public_path("tenancy/assets/franchise/RoleUser/");
//             $fileName = uniqid() . "_" . $file->getClientOriginalName();
//             $file->move($destinationPath, $fileName);

//             $filePath = $destinationPath . $fileName;
//             if (!File::exists($filePath)) {
//                 return back()->with("error", "File not found.");
//             }

//             $spreadsheet = IOFactory::load($filePath);
//             $sheet = $spreadsheet->getActiveSheet();
//             $data = [];
//             $header = null;

//             // Extract Excel rows
//             foreach ($sheet->getRowIterator() as $row) {
//                 $rowData = [];
//                 $hasData = false;

//                 foreach ($row->getCellIterator() as $cell) {
//                     $cellValue = $cell->getValue();
//                     $rowData[] = $cellValue;

//                     $hyperlink = $cell->getHyperlink();
//                     if ($hyperlink && $hyperlink->getUrl()) {
//                         $rowData["Hyperlink"] = $hyperlink->getUrl();
//                     }

//                     if (!is_null($cellValue) && $cellValue !== "") {
//                         $hasData = true;
//                     }
//                 }

//                 if ($hasData) {
//                     if (is_null($header)) {
//                         $header = $rowData;
//                         $header[] = "Hyperlink";
//                     } else {
//                         if (count($rowData) < count($header)) {
//                             $rowData = array_pad(
//                                 $rowData,
//                                 count($header),
//                                 null
//                             );
//                         }
//                         $data[] = array_combine($header, $rowData);
//                     }
//                 }
//             }

//             $client = new Client();
//             $downloadDirectory = public_path("tenancy/assets/franchise/RoleUser/");

//             foreach ($data as $element) {
//                 try {
//                     // Customer resolve
//                     $customer = null;
//                     $customers = null;
//                     $bookingType = 'prepaid';
                    
//                     if ($bookingType == "prepaid") {
//                         $customer = $this->resolveCustomer($element, $franchiseId);
//                         if ($customer instanceof \Illuminate\Http\RedirectResponse) {
//                             Log::warning("Customer not found: " . json_encode($element));
//                             $failCount++;
//                             $element["Error Reason"] ="Customer not found (non-cod)";
//                             $failedRows[] = $element;
//                             continue;
//                         }
//                     } else {
//                         $customers = ECustomer::where("mobile", $element["Pickup Phone"])->where("cph_link", $franchiseId)->first();
//                         if (empty($customers)) {
//                             $failCount++;
//                             $element["Error Reason"] ="Customer not found (COD)";
//                             $failedRows[] = $element;
//                             continue;
//                         }
//                     }

//                    // Download hyperlink file if exists
//                     if (!empty($element["Hyperlink"])) {
//                         try {
//                             $downloadFileName = uniqid() . "_" . pathinfo($element["Hyperlink"], PATHINFO_BASENAME);
//                             $client->get($element["Hyperlink"], ["sink" => $downloadDirectory . $downloadFileName,]);
//                         } catch (\Exception $e) {
//                             Log::error(
//                                 "Error downloading file: " . $e->getMessage()
//                             );
//                         }
//                     }
//                     // Weight calculation
//                     $actualWeight = floatval($element["Package Weight"] ?? 0);
//                     $length = floatval($element["Package Length"] ?? 0);
//                     $width = floatval($element["Package Width"] ?? 0);
//                     $height = floatval($element["Package Height"] ?? 0);
//                     $totalWeight = $actualWeight;
//                     if ($length && $width && $height) {
//                         $totalWeight += (($length * $width * $height) / 6000) * 1000;
//                     }

//                     // Price calculation
//                     $from = $element["Pickup Pincode"];
//                     $to = $element["Consignee Pincode"];
//                     $fuel_charge = $element["Fuel Charge"] ?? 0;
//                     $pickup_charge = $element["Pickup Charge"] ?? 0;
//                     $other_service_charge = $element["Other service Charge"] ?? 0;
//                     $register_amount = $element["Register Fee"] ?? 0;

//                     $rateDetails = $this->getPrice($from, $to, $totalWeight, 0, 0, 0, $register_amount); 

//                     $totalAmount = $this->getPrice($from, $to, $totalWeight, $fuel_charge, $pickup_charge, $other_service_charge, $register_amount);

//                     if ($rateDetails["status"] == "fail") {
//                         Log::warning("Rate calculation failed: " . $rateDetails["message"]);
//                         $failCount++;
//                         $element["Error Reason"] = "Rate calculation failed";
//                         $failedRows[] = $element;
//                         continue;
//                     }

//                     $payment_amount = $rateDetails["total"];
//                     $amount = $element["Amount"] ?? 0;

//                     $gotogo_balance = $franchise->indiapost_balance;
//                     $credit_balance = $franchise->india_credit_amount;
//                     $indiapost_balance = $gotogo_balance + $credit_balance;

//                     if ($indiapost_balance < $payment_amount) {
//                         Log::warning("Low balance for parcel: " . json_encode($element));
//                         $failCount++;
//                         $element["Error Reason"] = "Low balance";
//                         $failedRows[] = $element;
//                         continue;
//                     }

//                     // Barcode
//                     $todate = Carbon::today()->toDateString();
//                     $code = $element["Barcode"] ?: $this->getNextBarcode();
//                      $existingParcel = IndiaPostSpeedPostParcel::where("barcode_no", $code)->whereDate("created_at", $todate)->first();
//                     //  ->where('service_type', IndiaPostSpeedPostParcel::SERVICE_TYPE_INDIA_POST_SPEED)->first();
//                      if ($existingParcel) {
//                        return response()->json([
//                              "status" => 400,
//                              "message" => "Barcode Already Exist",
//                         ]);
//                         }
//                         // $code = $this->getNextBarcode();
//                         $generator = new BarcodeGeneratorPNG();
//                         $barcode = base64_encode($generator->getBarcode($code, $generator::TYPE_CODE_128));


//                     if (!empty($element["Market Manager _id"])) {
//                         $manager = MManager::where("mobile", $element["Market Manager _id"])->where("franchise_id", $franchiseId)->first();
//                         $payment_type = $manager->id;
//                         $managertype = "manager";
//                     } elseif (!empty($element["Pickup Boy_id"])) {
//                         $pickupBoy = PickupDetails::where("phone", $element["Pickup Boy_id"])->where("franchise_id", $franchiseId)->first();
//                         $payment_type = $pickupBoy->id;
//                         $managertype = "pickup";
//                     }else{
//                         $managertype = "franchise";
//                     }
                      
//                     $franchise_role_user_id = null;
//                     if (Auth::guard('franchiseRoleUser')->user()) {
//                       $franchise_role_user_id = Auth::guard('franchiseRoleUser')->user()->id;
//                      }
             
//                     // Create parcel
//                     $parcel = IndiaPostSpeedPostParcel::create([
//                         "franchise_id" => $franchiseId,
//                         "franchise_role_users_id" => $franchise_role_user_id,
//                         "cod_customer_id" => $customers->id ?? null,
//                         "no_r_customer_id" => $customer->id ?? null,
//                         "booking_type" => $managertype,
//                         "pickup_name" => $element["Pickup Name"],
//                         "pickup_mobile" => $element["Pickup Phone"],
//                         "pickup_email" => $element["Pickup Email"],
//                         "pickup_pincode" => $element["Pickup Pincode"],
//                         "pickup_city" => $element["Pickup City"],
//                         "pickup_state" => $element["Pickup State"],
//                         "pickup_address" => $element["Pickup Address"],
//                         "consignee_name" => $element["Consignee Name"],
//                         "consignee_mobile" => $element["Consignee Phone"],
//                         "consignee_email" => $element["Consignee Email"],
//                         "consignee_pincode" => $element["Consignee Pincode"],
//                         "consignee_city" => $element["Consignee City"],
//                         "consignee_state" => $element["Consignee State"],
//                         "consignee_address" => $element["Consignee Address"],
//                         "package_weight" => $element["Package Weight"],
//                         "package_length" => $element["Package Length"],
//                         "package_width" => $element["Package Width"],
//                         "package_height" => $element["Package Height"],
//                         "payment_method" => $bookingType,
//                         "cod_amount" => $element["Cod Amount"] ?? 0,
//                         "fuel_charge" => $fuel_charge,
//                         "pickup_charge" => $pickup_charge,
//                         "other_service_charge" => $other_service_charge,
//                         "payment_amount" => $payment_amount,
//                         "totalOtherAmount" => $totalAmount["total"],
//                         "barcode_no" => $code,
//                         "barcode_image_src" => $barcode,
//                         "insert_type" =>IndiaPostSpeedPostParcel::INSERT_TYPE_BULK,
//                     ]);
//                     $prepaid = "";
//                     if ($bookingType == "prepaid") {
//                         $prepaid = "prepaid";
//                     } else {
//                         $prepaid = "cod";
//                     }

//                     IndiaPostSpeedPostTrackOrder::create([
//                         "parcel_id" => $parcel->id,
//                         "barcode_no" => $code,
//                         "source_franchise_id" => Franchise::getFranchiseId(),
//                         "order_placed_datetime" => now(),
//                         "source_franchise_location" => $franchise->address,
//                     ]);

//                     // Commission & balance update

//                     $commission = $rateCalculater->calculateCommissionForIndiaPost($element["Package Weight"], $payment_amount, IndiaPostSpeedPostParcel::SERVICE_TYPE_INDIA_POST_SPEED);
//                     if (!empty($element["Market Manager _id"]) || !empty($element["Pickup Boy_id"])) {
//                         $marketcommission = $rateCalculater->calculateCommissionForIndiaPostMarket($payment_type, $payment_amount);
//                     }

//                     if ($gotogo_balance > $payment_amount) {
//                         $franchise->decrement("indiapost_balance", $payment_amount);
//                     } else {
//                         $franchise->decrement("india_credit_amount", $payment_amount);
//                     }

//                     FranchiseCommissionDetail::create([
//                         "franchise_id" => $franchiseId,
//                         "service_type" => IndiaPostSpeedPostParcel::SERVICE_TYPE_INDIA_POST_SPEED,
//                         "amount" => $payment_amount / 1.18,
//                         "commission" => number_format($commission, 2, ".", ""),
//                         "payment_method" => $prepaid,
//                     ]);

//                     if (!empty($element["Market Manager _id"]) || !empty($element["Pickup Boy_id"])) {
//                         $datamanager = new ManagerCommissionDetail();
//                         $datamanager->commission_id = $payment_type;
//                         $datamanager->servicetype =IndiaPostSpeedPostParcel::SERVICE_TYPE_INDIA_POST_SPEED;
//                         $datamanager->amount = $payment_amount / 1.18;
//                         $datamanager->commission = number_format($marketcommission, 2, ".", "");
//                         $datamanager->type = $managertype;
//                         $datamanager->save();
//                     }

//                     // Barcode series save
//                     if ($request->barcode_option === "barcode_auto") {
//                         $serviceTypeValue = IndiaPostSpeedPostParcel::getServiceTypeDB(IndiaPostSpeedPostParcel::SERVICE_TYPE_INDIA_POST_SPEED);
//                         $last_code_issued_column = "last_parcel_code_issued_{$serviceTypeValue}";
//                         $range_start_column = "parcel_barcode_range_start_{$serviceTypeValue}";

//                         $series = FranchiseBarcodeSeries::where("franchise_id", $franchiseId)->first();
//                         $seriesNum = $series->{$last_code_issued_column} ? $series->{$last_code_issued_column} + 1 : $series->{$range_start_column};
//                         $series->{$last_code_issued_column} = $seriesNum;
//                         $series->save();

//                         $franchiseBarcode = new FranchiseBarcodes();
//                         $franchiseBarcode->barcodes = $code;
//                         $franchiseBarcode->franchise_barcodeseries_id = $series->id;
//                         $franchiseBarcode->save();
//                     }

//                     try {
//                     $this->sendNotificationToUser($franchise, $parcel);
//                  } catch (\Exception $e) {
//                 \Log::error(
//                     "Error sending user notification: " . $e->getMessage()
//                 );
//                 }

//                    // Send SMS to consignee mobile
//                     if (!empty($element["Consignee Phone"])) {
//                         try {
//                             $notification = new SMSNotification($element["Consignee Phone"],"ORDER", [$code, now()]);
//                             $consigneeResponse = $notification->sendMessage();
//                         } catch (\Exception $e) {
//                             \Log::error("Error sending SMS to consignee mobile: " . $e->getMessage());
//                         }
//                     }
                

//                     // Send email
//                     if ($element["Pickup Email"]) {
//                         $savedPdfFilePath = $this->savePdf($parcel);
//                         Mail::to($element["Pickup Email"])->send(new ParcelMail([$savedPdfFilePath, $parcel]));
//                     }
//                $successCount++;
//                 } catch (\Exception $e) {
//                     Log::error("Row failed: " . $e->getMessage() . " | Data: " . json_encode($element));
//                     $failCount++;
//                     $element["Error Reason"] = $e->getMessage();
//                     $failedRows[] = $element;
//                     continue;
//                 }
                
//             }

//             // fail data है तो Excel
//             $failedFileName = null;
//             if (!empty($failedRows)) {
//                 $failedSpreadsheet = new Spreadsheet();
//                 $failedSheet = $failedSpreadsheet->getActiveSheet();

//                 // Header
//                 $failedSheet->fromArray(array_keys($failedRows[0]), null, "A1");
//                 $rowIndex = 2;
//                 foreach ($failedRows as $row) {
//                     $failedSheet->fromArray(array_values($row), null, "A{$rowIndex}");
//                     $rowIndex++;
//                 }

//                 $failedFileName = "failed_rows_" . date("Ymd_His") . ".xlsx";
//                 $failedFilePath = storage_path($failedFileName);
//                 $writer = new Xlsx($failedSpreadsheet);
//                 $writer->save($failedFilePath);

//                 // return response()->download($failedFilePath)->deleteFileAfterSend(true);
//             }

//             // Old dowload

//             //         $failedFileName = NULL;
//             // if (!empty($failedRows)) {
//             //     $failedSpreadsheet = new Spreadsheet();
//             //     $failedSheet = $failedSpreadsheet->getActiveSheet();

//             //     $failedSheet->fromArray(array_keys($failedRows[0]), null, 'A1');
//             //     $rowIndex = 2;
//             //     foreach ($failedRows as $row) {
//             //         $failedSheet->fromArray(array_values($row), null, "A{$rowIndex}");
//             //         $rowIndex++;
//             //     }

//             //     $failedFileName = 'failed_rows_' . date('Ymd_His') . '.xlsx';
//             //     $failedFilePath = public_path('admin/failed/' . $failedFileName);

//             //     if (!file_exists(public_path('admin/failed'))) {
//             //         mkdir(public_path('admin/failed'), 0777, true);
//             //     }

//             //     // Save file
//             //     $writer = new Xlsx($failedSpreadsheet);
//             //     $writer->save($failedFilePath);
//             // }

//             // End old download

//             // return redirect()
//             //     ->route("franchise.india-post-speed-post.index")
//             //     ->with("success", "Upload completed. Success: {$successCount}, Failed: {$failCount}")
//             //     ->with("failed_file", $failedFileName);

//            return response()->json([
//     'status' => 200,
//     'message' => "Upload completed",
//     'successCount' => $successCount,
//     'failCount' => $failCount,
//     'redirect_url' => route('franchise.india-post-speed-post.index'),
//     'failed_file' => $failedFileName
// ]);


//         } catch (\Exception $e) {
//             return back()->with("error", $e->getMessage());
//         }
//     }

    // public function storeByFile(Request $request, RateCalculator $rateCalculater) {
    //     if (!$request->hasFile("file")) {
    //         return back()->with("error", "No file uploaded.");
    //     }

    //     $franchiseId = Franchise::getFranchiseId();
    //     $franchise = Franchise::findOrFail($franchiseId);

    //     $successCount = 0;
    //     $failCount = 0;
    //     $failedRows = []; // failed data store
    //     $payment_type = null;
    //     $managertype = null;
    //     try {
    //         // Move uploaded file
    //         $file = $request->file("file");
    //         $destinationPath = public_path("tenancy/assets/franchise/RoleUser/");
    //         $fileName = uniqid() . "_" . $file->getClientOriginalName();
    //         $file->move($destinationPath, $fileName);

    //         $filePath = $destinationPath . $fileName;
    //         if (!File::exists($filePath)) {
    //             return back()->with("error", "File not found.");
    //         }

    //         $spreadsheet = IOFactory::load($filePath);
    //         $sheet = $spreadsheet->getActiveSheet();
    //         $data = [];
    //         $header = null;

    //         // Extract Excel rows
    //         foreach ($sheet->getRowIterator() as $row) {
    //             $rowData = [];
    //             $hasData = false;

    //             foreach ($row->getCellIterator() as $cell) {
    //                 $cellValue = $cell->getValue();
    //                 $rowData[] = $cellValue;

    //                 $hyperlink = $cell->getHyperlink();
    //                 if ($hyperlink && $hyperlink->getUrl()) {
    //                     $rowData["Hyperlink"] = $hyperlink->getUrl();
    //                 }

    //                 if (!is_null($cellValue) && $cellValue !== "") {
    //                     $hasData = true;
    //                 }
    //             }

    //             if ($hasData) {
    //                 if (is_null($header)) {
    //                     $header = $rowData;
    //                     $header[] = "Hyperlink";
    //                 } else {
    //                     if (count($rowData) < count($header)) {
    //                         $rowData = array_pad(
    //                             $rowData,
    //                             count($header),
    //                             null
    //                         );
    //                     }
    //                     $data[] = array_combine($header, $rowData);
    //                 }
    //             }
    //         }

    //         $client = new Client();
    //         $downloadDirectory = public_path("tenancy/assets/franchise/RoleUser/");

    //         foreach ($data as $element) {
    //             try {
    //                 // Customer resolve
    //                 $customer = null;
    //                 $customers = null;
    //                 $bookingType = 'prepaid';
                    
    //                 if ($bookingType == "prepaid") {
    //                     $customer = $this->resolveCustomer($element, $franchiseId);
    //                     if ($customer instanceof \Illuminate\Http\RedirectResponse) {
    //                         Log::warning("Customer not found: " . json_encode($element));
    //                         $failCount++;
    //                         $element["Error Reason"] ="Customer not found (Prepaid)";
    //                         $failedRows[] = $element;
    //                         continue;
    //                     }
    //                 } else {
    //                     $customers = ECustomer::where("mobile", $element["Pickup Phone"])->where("cph_link", $franchiseId)->first();
    //                     if (empty($customers)) {
    //                         $failCount++;
    //                         $element["Error Reason"] ="Customer not found (COD)";
    //                         $failedRows[] = $element;
    //                         continue;
    //                     }
    //                 }

    //                // Download hyperlink file if exists
    //                 if (!empty($element["Hyperlink"])) {
    //                     try {
    //                         $downloadFileName = uniqid() . "_" . pathinfo($element["Hyperlink"], PATHINFO_BASENAME);
    //                         $client->get($element["Hyperlink"], ["sink" => $downloadDirectory . $downloadFileName,]);
    //                     } catch (\Exception $e) {
    //                         Log::error(
    //                             "Error downloading file: " . $e->getMessage()
    //                         );
    //                     }
    //                 }
    //                 // Weight calculation
    //                 $actualWeight = floatval($element["Package Weight"] ?? 0);
    //                 $length = floatval($element["Package Length"] ?? 0);
    //                 $width = floatval($element["Package Width"] ?? 0);
    //                 $height = floatval($element["Package Height"] ?? 0);
    //                 $totalWeight = $actualWeight;
    //                 if ($length && $width && $height) {
    //                     $totalWeight += (($length * $width * $height) / 6000) * 1000;
    //                 }

    //                 // Price calculation
    //                 $from = $element["Pickup Pincode"];
    //                 $to = $element["Consignee Pincode"];
    //                 $fuel_charge = $element["Fuel Charge"] ?? 0;
    //                 $pickup_charge = $element["Pickup Charge"] ?? 0;
    //                 $other_service_charge = $element["Other service Charge"] ?? 0;
    //                 $register_amount = $element["Register Fee"] ?? 0;

    //                 $rateDetails = $this->getPrice($from, $to, $totalWeight, 0, 0, 0, $register_amount);

    //                 $totalAmount = $this->getPrice($from, $to, $totalWeight, $fuel_charge, $pickup_charge, $other_service_charge, $register_amount);

    //                 if ($rateDetails["status"] == "fail") {
    //                     Log::warning("Rate calculation failed: " . $rateDetails["message"]);
    //                     $failCount++;
    //                     $element["Error Reason"] = "Rate calculation failed";
    //                     $failedRows[] = $element;
    //                     continue;
    //                 }

    //                 $payment_amount = $rateDetails["total"];
    //                 $amount = $element["Amount"] ?? 0;

    //                 $gotogo_balance = $franchise->indiapost_balance;
    //                 $credit_balance = $franchise->india_credit_amount;
    //                 $indiapost_balance = $gotogo_balance + $credit_balance;

    //                 if ($indiapost_balance < $payment_amount) {
    //                     Log::warning("Low balance for parcel: " . json_encode($element));
    //                     $failCount++;
    //                     $element["Error Reason"] = "Low balance";
    //                     $failedRows[] = $element;
    //                     continue;
    //                 }

    //                 // Barcode
    //                 $todate = Carbon::today()->toDateString();
    //                 $code = $element["Barcode"] ?: $this->getNextBarcode();
    //                  $existingParcel = IndiaPostSpeedPostParcel::where("barcode_no", $code)->whereDate("created_at", $todate)->first();
    //                 //  ->where('service_type', IndiaPostSpeedPostParcel::SERVICE_TYPE_INDIA_POST_SPEED)->first();
    //                  if ($existingParcel) {
    //                    return response()->json([
    //                          "status" => 400,
    //                          "message" => "Barcode Already Exist",
    //                     ]);
    //                     }
    //                     // $code = $this->getNextBarcode();
    //                     $generator = new BarcodeGeneratorPNG();
    //                     $barcode = base64_encode($generator->getBarcode($code, $generator::TYPE_CODE_128));


    //                 if (!empty($element["Market Manager _id"])) {
    //                     $manager = MManager::where("mobile", $element["Market Manager _id"])->where("franchise_id", $franchiseId)->first();
    //                     $payment_type = $manager->id;
    //                     $managertype = "manager";
    //                 } elseif (!empty($element["Pickup Boy_id"])) {
    //                     $pickupBoy = PickupDetails::where("phone", $element["Pickup Boy_id"])->where("franchise_id", $franchiseId)->first();
    //                     $payment_type = $pickupBoy->id;
    //                     $managertype = "pickup";
    //                 }else{
    //                     $managertype = "franchise";
    //                 }
                      
    //                 $franchise_role_user_id = null;
    //                 if (Auth::guard('franchiseRoleUser')->user()) {
    //                   $franchise_role_user_id = Auth::guard('franchiseRoleUser')->user()->id;
    //                  }
             
    //                 // Create parcel
    //                  $parcel = IndiaPostSpeedPostParcel::create([
    //                     "franchise_id" => $franchiseId,
    //                     "franchise_role_users_id" => $franchise_role_user_id,
    //                     "cod_customer_id" => $customers->id ?? null,
    //                     "no_r_customer_id" => $customer->id ?? null,
    //                     "booking_type" => $managertype,
    //                     "pickup_name" => $element["Pickup Name"],
    //                     "pickup_mobile" => $element["Pickup Phone"],
    //                     "pickup_email" => $element["Pickup Email"],
    //                     "pickup_pincode" => $element["Pickup Pincode"],
    //                     "pickup_city" => $element["Pickup City"],
    //                     "pickup_state" => $element["Pickup State"],
    //                     "pickup_address" => $element["Pickup Address"],
    //                     "consignee_name" => $element["Consignee Name"],
    //                     "consignee_mobile" => $element["Consignee Phone"],
    //                     "consignee_email" => $element["Consignee Email"],
    //                     "consignee_pincode" => $element["Consignee Pincode"],
    //                     "consignee_city" => $element["Consignee City"],
    //                     "consignee_state" => $element["Consignee State"],
    //                     "consignee_address" => $element["Consignee Address"],
    //                     "package_weight" => $element["Package Weight"],
    //                     "package_length" => $element["Package Length"],
    //                     "package_width" => $element["Package Width"],
    //                     "package_height" => $element["Package Height"],
    //                     "payment_method" => $bookingType,
    //                     "cod_amount" => $element["Cod Amount"] ?? 0,
    //                     "fuel_charge" => $fuel_charge,
    //                     "pickup_charge" => $pickup_charge,
    //                     "other_service_charge" => $other_service_charge,
    //                     "payment_amount" => $payment_amount,
    //                     "totalOtherAmount" => $totalAmount["total"],
    //                     "barcode_no" => $code,
    //                     "barcode_image_src" => $barcode,
    //                     "insert_type" =>IndiaPostSpeedPostParcel::INSERT_TYPE_BULK,
    //                 ]);
    //                 $prepaid = "";
    //                 if ($bookingType == "prepaid") {
    //                     $prepaid = "prepaid";
    //                 } else {
    //                     $prepaid = "cod";
    //                 }

    //                 IndiaPostSpeedPostTrackOrder::create([
    //                     "parcel_id" => $parcel->id,
    //                     "barcode_no" => $code,
    //                     "source_franchise_id" => Franchise::getFranchiseId(),
    //                     "order_placed_datetime" => now(),
    //                     "source_franchise_location" => $franchise->address,
    //                 ]);

    //                 // Commission & balance update

    //                 $commission = $rateCalculater->calculateCommissionForIndiaPost($element["Package Weight"], $payment_amount, IndiaPostSpeedPostParcel::SERVICE_TYPE_INDIA_POST_SPEED);
    //                 if (!empty($element["Market Manager _id"]) || !empty($element["Pickup Boy_id"])) {
    //                     $marketcommission = $rateCalculater->calculateCommissionForIndiaPostMarket($payment_type, $payment_amount);
    //                 }

    //                 if ($gotogo_balance > $payment_amount) {
    //                     $franchise->decrement("indiapost_balance", $payment_amount);
    //                 } else {
    //                     $franchise->decrement("india_credit_amount", $payment_amount);
    //                 }

    //                 FranchiseCommissionDetail::create([
    //                     "franchise_id" => $franchiseId,
    //                     "service_type" => IndiaPostSpeedPostParcel::SERVICE_TYPE_INDIA_POST_SPEED,
    //                     "amount" => $payment_amount / 1.18,
    //                     "commission" => number_format($commission, 2, ".", ""),
    //                     "payment_method" => $prepaid,
    //                 ]);

    //                 if (!empty($element["Market Manager _id"]) || !empty($element["Pickup Boy_id"])) {
    //                     $datamanager = new ManagerCommissionDetail();
    //                     $datamanager->commission_id = $payment_type;
    //                     $datamanager->servicetype =IndiaPostSpeedPostParcel::SERVICE_TYPE_INDIA_POST_SPEED;
    //                     $datamanager->amount = $payment_amount / 1.18;
    //                     $datamanager->commission = number_format($marketcommission, 2, ".", "");
    //                     $datamanager->type = $managertype;
    //                     $datamanager->save();
    //                 }

    //                 // Barcode series save
    //                 if ($request->barcode_option === "barcode_auto") {
    //                     $serviceTypeValue = IndiaPostSpeedPostParcel::getServiceTypeDB(IndiaPostSpeedPostParcel::SERVICE_TYPE_INDIA_POST_SPEED);
    //                     $last_code_issued_column = "last_parcel_code_issued_{$serviceTypeValue}";
    //                     $range_start_column = "parcel_barcode_range_start_{$serviceTypeValue}";

    //                     $series = FranchiseBarcodeSeries::where("franchise_id", $franchiseId)->first();
    //                     $seriesNum = $series->{$last_code_issued_column} ? $series->{$last_code_issued_column} + 1 : $series->{$range_start_column};
    //                     $series->{$last_code_issued_column} = $seriesNum;
    //                     $series->save();

    //                     $franchiseBarcode = new FranchiseBarcodes();
    //                     $franchiseBarcode->barcodes = $code;
    //                     $franchiseBarcode->franchise_barcodeseries_id = $series->id;
    //                     $franchiseBarcode->save();
    //                 }

    //                 try {
    //                 $this->sendNotificationToUser($franchise, $parcel);
    //              } catch (\Exception $e) {
    //             \Log::error(
    //                 "Error sending user notification: " . $e->getMessage()
    //             );
    //             }

    //                // Send SMS to consignee mobile
    //                 if (!empty($element["Consignee Phone"])) {
    //                     try {
    //                         $notification = new SMSNotification($element["Consignee Phone"],"ORDER", [$code, now()]);
    //                         $consigneeResponse = $notification->sendMessage();
    //                     } catch (\Exception $e) {
    //                         \Log::error("Error sending SMS to consignee mobile: " . $e->getMessage());
    //                     }
    //                 }
                

    //                 // Send email
    //                 if ($element["Pickup Email"]) {
    //                     $savedPdfFilePath = $this->savePdf($parcel);
    //                     Mail::to($element["Pickup Email"])->send(new ParcelMail([$savedPdfFilePath, $parcel]));
    //                 }
    //            $successCount++;
    //             } catch (\Exception $e) {
    //                 Log::error("Row failed: " . $e->getMessage() . " | Data: " . json_encode($element));
    //                 $failCount++;
    //                 $element["Error Reason"] = $e->getMessage();
    //                 $failedRows[] = $element;
    //                 continue;
    //             }
                
    //         }

    //         // fail data है तो Excel
    //         $failedFileName = null;
    //         if (!empty($failedRows)) {
    //             $failedSpreadsheet = new Spreadsheet();
    //             $failedSheet = $failedSpreadsheet->getActiveSheet();

    //             // Header
    //             $failedSheet->fromArray(array_keys($failedRows[0]), null, "A1");
    //             $rowIndex = 2;
    //             foreach ($failedRows as $row) {
    //                 $failedSheet->fromArray(array_values($row), null, "A{$rowIndex}");
    //                 $rowIndex++;
    //             }

    //             $failedFileName = "failed_rows_" . date("Ymd_His") . ".xlsx";
    //             $failedFilePath = storage_path($failedFileName);
    //             $writer = new Xlsx($failedSpreadsheet);
    //             $writer->save($failedFilePath);

    //             // return response()->download($failedFilePath)->deleteFileAfterSend(true);
    //         }

    //         // Old dowload

    //         //         $failedFileName = NULL;
    //         // if (!empty($failedRows)) {
    //         //     $failedSpreadsheet = new Spreadsheet();
    //         //     $failedSheet = $failedSpreadsheet->getActiveSheet();

    //         //     $failedSheet->fromArray(array_keys($failedRows[0]), null, 'A1');
    //         //     $rowIndex = 2;
    //         //     foreach ($failedRows as $row) {
    //         //         $failedSheet->fromArray(array_values($row), null, "A{$rowIndex}");
    //         //         $rowIndex++;
    //         //     }

    //         //     $failedFileName = 'failed_rows_' . date('Ymd_His') . '.xlsx';
    //         //     $failedFilePath = public_path('admin/failed/' . $failedFileName);

    //         //     if (!file_exists(public_path('admin/failed'))) {
    //         //         mkdir(public_path('admin/failed'), 0777, true);
    //         //     }

    //         //     // Save file
    //         //     $writer = new Xlsx($failedSpreadsheet);
    //         //     $writer->save($failedFilePath);
    //         // }

    //         // End old download

    //         return redirect()
    //             ->route("franchise.india-post-speed-post.index")
    //             ->with("success", "Upload completed. Success: {$successCount}, Failed: {$failCount}")
    //             ->with("failed_file", $failedFileName);
    //     } catch (\Exception $e) {
    //         return back()->with("error", $e->getMessage());
    //     }
    // }


    public function fullPrint($id)
    {
        $parcel = IndiaPostSpeedPostParcel::findorfail($id);

        $generator = new BarcodeGeneratorPNG();
        $code = $parcel->barcode_no;
        $barcode = $generator->getBarcode($code, $generator::TYPE_CODE_128);
        $barcode = base64_encode($barcode);

        $fuel_charge = $parcel->fuel_charge;
        $pickup_charge = $parcel->pickup_charge;
        $other_service_charge = $parcel->other_service_charge;
        $total_payment_amount = $parcel->payment_amount;
        $net_price = $total_payment_amount / 1.18;
        $amount =
            $net_price -
            ($fuel_charge + $pickup_charge + $other_service_charge);
        $gst = number_format($net_price * 0.18, 2, ".", "");
        $net_price_formatted = number_format($net_price, 2, ".", "");

        $rateDetails = [
            "fuel_charge" => $fuel_charge,
            "pickup_charge" => $pickup_charge,
            "other_service_charge" => $other_service_charge,
            "total_payment_amount" => number_format(
                $total_payment_amount,
                2,
                ".",
                ""
            ),
            "net_price" => $net_price_formatted,
            "amount" => number_format($amount, 2, ".", ""),
            "gst" => $gst,
        ];

        $title = \App\Models\Admin::INDIA_POST_SPEED;

        $otherPageContent = View::make("print.indiaPost.fullPrint", [
            "parcel" => $parcel,
            "barcode" => $barcode,
            "rateDetails" => $rateDetails,
            "title" => $title,
        ])->render();

        return response()->json([
            "otherPageContent" => $otherPageContent,
        ]);
    }

    public function shortPrintForCreatedParcel(Request $request)
    {
      
        $fromdate = $request->input("fromdate");
        $todate = $request->input("todate");
        $searchKey = $request->input("searchKey");
        $type = $request->input("type");

        if ($type === "cod") {
            $view = "print.indiaPost.codPrint";
        } else {
            $view = "print.indiaPost.prepaidRecipt";
            // $view = 'print.indiaPost.prepaidPrint';
        }

        $franchiseId = Franchise::getFranchiseId();
        $franchise = Franchise::findOrFail($franchiseId);
        $linkDetail = IndiaPostLink::where("franchise_no", $franchise->franchise_no)->first();
        $query = IndiaPostSpeedPostParcel::where("franchise_id", $franchiseId)

            // ->where("insert_type", $request->insert_type)
            ->where("status", 0)
            ->orderBy("created_at", "desc");
        $selectedIds = array_map("intval", $request->input("ids", []));
        if (!empty($selectedIds)) {
            $query->whereIn("id", $selectedIds);
        }
        // Filter by payment method
        if ($type === "cod") {
            $query->where("payment_method", "cod");
        } else {
            $query->where("payment_method", "prepaid");
        }

        if ($searchKey) {
            $query->where(function ($q) use ($searchKey) {
                $q->where("pickup_name", "LIKE", "%{$searchKey}%")

                    ->orWhere("pickup_mobile", "LIKE", "%{$searchKey}%")

                    ->orWhere("pickup_email", "LIKE", "%{$searchKey}%")

                    ->orWhere("pickup_pincode", "LIKE", "%{$searchKey}%")

                    ->orWhere("pickup_city", "LIKE", "%{$searchKey}%")

                    ->orWhere("pickup_state", "LIKE", "%{$searchKey}%")

                    ->orWhere("pickup_address", "LIKE", "%{$searchKey}%")

                    ->orWhere("consignee_name", "LIKE", "%{$searchKey}%")

                    ->orWhere("consignee_mobile", "LIKE", "%{$searchKey}%")

                    ->orWhere("consignee_email", "LIKE", "%{$searchKey}%")

                    ->orWhere("consignee_pincode", "LIKE", "%{$searchKey}%")

                    ->orWhere("consignee_city", "LIKE", "%{$searchKey}%")

                    ->orWhere("consignee_state", "LIKE", "%{$searchKey}%")

                    ->orWhere("consignee_address", "LIKE", "%{$searchKey}%")

                    ->orWhere("barcode_no", "LIKE", "%{$searchKey}%");
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

            $title = \App\Models\Admin::INDIA_POST_SPEED;

            $otherPageContent = View::make($view, [
                "data" => $parcels,
                "linkDetail" => $linkDetail,
                "title" => $title,
                "franchise" => $franchise,
                "franchise_no" => $franchise->franchise_no,
                "type" => 5,
            ])->render();
            return response()->json([
                "otherPageContent" => $otherPageContent,
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
            $query->whereDate("created_at", Carbon::today());
            $parcels = $query->get();
        }

        $title = \App\Models\Admin::INDIA_POST_SPEED;

        $otherPageContent = View::make($view, [
            "data" => $parcels,
            "linkDetail" => $linkDetail,
            "title" => $title,
            "franchise" => $franchise,
            "franchise_no" => $franchise->franchise_no,
            "type" => 5,
        ])->render();

        return response()->json([
            "otherPageContent" => $otherPageContent,
        ]);
    }

    public function shortLabelForCreatedParcel(Request $request)
    {
      
        $fromdate = $request->input("fromdate");
        $todate = $request->input("todate");
        $searchKey = $request->input("searchKey");
        $type = $request->input("type");

        if ($type === "cod") {
            $view = "print.indiaPost.codPrint";
        } else {
            // $view = "print.indiaPost.prepaidLabel2";
            if($request->printType == 1){
              $view = "print.indiaPost.prepaidLabel3";
            }else{
               
                $view = "print.indiaPost.prepaidLabel2";
            }
        }
        // prepaidLabel

        $franchiseId = Franchise::getFranchiseId();
        $franchise = Franchise::findOrFail($franchiseId);
        $linkDetail = IndiaPostLink::where(
            "franchise_no",
            $franchise->franchise_no
        )->first();
        $query = IndiaPostSpeedPostParcel::where(
            "franchise_id",
            Franchise::getFranchiseId()
        )

            // ->where("insert_type", $request->insert_type)
               ->where("status", 0)
            ->orderBy("created_at", "desc");
        $selectedIds = array_map("intval", $request->input("ids", []));
        if (!empty($selectedIds)) {
            $query->whereIn("id", $selectedIds);
        }
        // Filter by payment method
        if ($type === "cod") {
            $query->where("payment_method", "cod");
        } else {
            $query->where("payment_method", "prepaid");
        }

        if ($searchKey) {
            $query->where(function ($q) use ($searchKey) {
                $q->where("pickup_name", "LIKE", "%{$searchKey}%")

                    ->orWhere("pickup_mobile", "LIKE", "%{$searchKey}%")

                    ->orWhere("pickup_email", "LIKE", "%{$searchKey}%")

                    ->orWhere("pickup_pincode", "LIKE", "%{$searchKey}%")

                    ->orWhere("pickup_city", "LIKE", "%{$searchKey}%")

                    ->orWhere("pickup_state", "LIKE", "%{$searchKey}%")

                    ->orWhere("pickup_address", "LIKE", "%{$searchKey}%")

                    ->orWhere("consignee_name", "LIKE", "%{$searchKey}%")

                    ->orWhere("consignee_mobile", "LIKE", "%{$searchKey}%")

                    ->orWhere("consignee_email", "LIKE", "%{$searchKey}%")

                    ->orWhere("consignee_pincode", "LIKE", "%{$searchKey}%")

                    ->orWhere("consignee_city", "LIKE", "%{$searchKey}%")

                    ->orWhere("consignee_state", "LIKE", "%{$searchKey}%")

                    ->orWhere("consignee_address", "LIKE", "%{$searchKey}%")

                    ->orWhere("barcode_no", "LIKE", "%{$searchKey}%");
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

            $title = \App\Models\Admin::INDIA_POST_SPEED;

            $otherPageContent = View::make($view, [
                "data" => $parcels,
                "linkDetail" => $linkDetail,
                "title" => $title,
                "franchise" => $franchise,
                "type" => 5,
            ])->render();
            return response()->json([
                "otherPageContent" => $otherPageContent,
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
            $query->whereDate("created_at", Carbon::today());
            $parcels = $query->get();
        }

        $title = \App\Models\Admin::INDIA_POST_SPEED;

        $otherPageContent = View::make($view, [
            "data" => $parcels,
            "linkDetail" => $linkDetail,
            "title" => $title,
            "franchise" => $franchise,
            "type" => 5,
        ])->render();

        return response()->json([
            "otherPageContent" => $otherPageContent,
        ]);
    }

    public function downloadTableForCreatedTable(Request $request)
    {
        // Fetch data
        $fromdate = $request->input("fromdate");
        $todate = $request->input("todate");
        $searchKey = $request->input("searchKey");

        $type = $request->input("type");

        $query = IndiaPostSpeedPostParcel::where("franchise_id", Franchise::getFranchiseId())->where("status", 0)->orderBy("created_at", "desc");

        if ($type === "cod") {
            $query->where("payment_method", "cod");
        } else {
            $query->where("payment_method", "prepaid");
        }

        if ($searchKey) {
            $query->where(function ($q) use ($searchKey) {
                $q->where("pickup_name", "LIKE", "%{$searchKey}%")

                    ->orWhere("pickup_mobile", "LIKE", "%{$searchKey}%")

                    ->orWhere("pickup_email", "LIKE", "%{$searchKey}%")

                    ->orWhere("pickup_pincode", "LIKE", "%{$searchKey}%")

                    ->orWhere("pickup_city", "LIKE", "%{$searchKey}%")

                    ->orWhere("pickup_state", "LIKE", "%{$searchKey}%")

                    ->orWhere("pickup_address", "LIKE", "%{$searchKey}%")

                    ->orWhere("consignee_name", "LIKE", "%{$searchKey}%")

                    ->orWhere("consignee_mobile", "LIKE", "%{$searchKey}%")

                    ->orWhere("consignee_email", "LIKE", "%{$searchKey}%")

                    ->orWhere("consignee_pincode", "LIKE", "%{$searchKey}%")

                    ->orWhere("consignee_city", "LIKE", "%{$searchKey}%")

                    ->orWhere("consignee_state", "LIKE", "%{$searchKey}%")

                    ->orWhere("consignee_address", "LIKE", "%{$searchKey}%")

                    ->orWhere("barcode_no", "LIKE", "%{$searchKey}%");
            });

            if (!empty($fromdate) || !empty($todate)) {
                if (!empty($fromdate) && !empty($todate)) {
                    $fromdate = Carbon::createFromFormat("d-m-Y", $fromdate)->startOfDay()->toDateString();
                    $todate = Carbon::createFromFormat("d-m-Y", $todate)->startOfDay()->toDateString();
                    $query->whereBetween("created_at", [$fromdate, $todate]);
                } elseif (!empty($fromdate)) {
                    // सिर्फ fromdate मिला
                    $fromdate = Carbon::createFromFormat("d-m-Y", $fromdate)->toDateString();
                    $query->whereDate("created_at", $fromdate);
                } elseif (!empty($todate)) {
                    // सिर्फ todate मिला
                    $todate = Carbon::createFromFormat("d-m-Y", $todate)->toDateString();
                    $query->whereDate("created_at", $todate);
                }
            }
            $parcels = $query->get();
            return $this->getExcel($request, $parcels);
        }

        if (!empty($fromdate) || !empty($todate)) {
            if (!empty($fromdate) && !empty($todate)) {
                $fromdate = Carbon::createFromFormat("d-m-Y", $fromdate)->startOfDay()->toDateString();
                $todate = Carbon::createFromFormat("d-m-Y", $todate)->startOfDay()->toDateString();
                $query->whereBetween("created_at", [$fromdate, $todate]);
            } elseif (!empty($fromdate)) {
                // सिर्फ fromdate मिला
                $fromdate = Carbon::createFromFormat("d-m-Y", $fromdate)->toDateString();
                $query->whereDate("created_at", $fromdate);
            } elseif (!empty($todate)) {
                // सिर्फ todate मिला
                $todate = Carbon::createFromFormat("d-m-Y", $todate)->toDateString();
                $query->whereDate("created_at", $todate);
            }
            $parcels = $query->get();
            return $this->getExcel($request, $parcels);
        }

        $query->whereDate("created_at", Carbon::today());
        $parcels = $query->get();
        return $this->getExcel($request, $parcels);
    }

    public function assignBarcode(Request $request)
    {
        $parcel = IndiaPostSpeedPostParcel::where(
            "barcode_no",
            $request->barcode
        )->first();

        if ($parcel) {
            return response()->json([
                "status" => "success",
                "data" => $parcel,
                "message" => "dublicate barcode",
            ]);
        }

        if ($request->isMethod("post")) {
            try {
                $generator = new BarcodeGeneratorPNG();

                $barcode = $generator->getBarcode(
                    $request->barcode,
                    $generator::TYPE_CODE_128
                );

                $barcode_image_src = base64_encode($barcode);

                $Model = IndiaPostSpeedPostParcel::where("barcode_no", null)
                    ->orderBy("created_at", "desc")
                    ->first();

                $Model->barcode_no = $request->barcode;

                $Model->barcode_image_src = $barcode_image_src;

                $Model->save();

                return response()->json([
                    "status" => "success",
                    "data" => $Model,
                    "message" => "barcode assigned successfully",
                ]);
            } catch (\Throwable $th) {
                return response()->json(
                    ["status" => "error", "message" => $th->getMessage()],
                    500
                );
            }
        }

        return response()->json(
            ["status" => "error", "message" => "Invalid request method"],
            400
        );
    }

    public function showReceivedParcelList(Request $request)
    {
        $searchKey = $request->input("searchKey");
        $date = $request->input("date");

        $userId = Franchise::getFranchiseId();
        // $insertType = $request->input('insert_type');
        $formattedDate =
            !empty($date) && $date !== ""
                ? Carbon::parse($date)->format("Y-m-d")
                : Carbon::today()->toDateString();

        $scidData = IndiaPostSpeedPostParcel::where(
            "sfid_forfile_upload",
            $userId
        );

        if ($scidData) {
            $scidData->where("sfid_file_upload_date", "=", $formattedDate);
            if ($searchKey) {
                $scidData->where(function ($query) use ($searchKey) {
                    $query
                        ->where("pickup_name", "LIKE", "%{$searchKey}%")
                        ->orWhere("pickup_mobile", "LIKE", "%{$searchKey}%")
                        ->orWhere("pickup_email", "LIKE", "%{$searchKey}%")
                        ->orWhere("pickup_pincode", "LIKE", "%{$searchKey}%")
                        ->orWhere("pickup_city", "LIKE", "%{$searchKey}%")
                        ->orWhere("pickup_state", "LIKE", "%{$searchKey}%")
                        ->orWhere("pickup_address", "LIKE", "%{$searchKey}%")
                        ->orWhere("consignee_name", "LIKE", "%{$searchKey}%")
                        ->orWhere("consignee_mobile", "LIKE", "%{$searchKey}%")
                        ->orWhere("consignee_email", "LIKE", "%{$searchKey}%")
                        ->orWhere("consignee_pincode", "LIKE", "%{$searchKey}%")
                        ->orWhere("consignee_city", "LIKE", "%{$searchKey}%")
                        ->orWhere("consignee_state", "LIKE", "%{$searchKey}%")
                        ->orWhere("consignee_address", "LIKE", "%{$searchKey}%")
                        ->orWhere("barcode_no", "LIKE", "%{$searchKey}%");
                });
            }

            $scidResults = $scidData->get();
        }

        $dcidData = IndiaPostSpeedPostParcel::where(
            "dfid_forfile_upload",
            $userId
        );

        if ($dcidData) {
            $dcidData->where("dfid_file_upload_date", "=", $formattedDate);

            if ($searchKey) {
                $dcidData->where(function ($query) use ($searchKey) {
                    $query
                        ->where("pickup_name", "LIKE", "%{$searchKey}%")
                        ->orWhere("pickup_mobile", "LIKE", "%{$searchKey}%")
                        ->orWhere("pickup_email", "LIKE", "%{$searchKey}%")
                        ->orWhere("pickup_pincode", "LIKE", "%{$searchKey}%")
                        ->orWhere("pickup_city", "LIKE", "%{$searchKey}%")
                        ->orWhere("pickup_state", "LIKE", "%{$searchKey}%")
                        ->orWhere("pickup_address", "LIKE", "%{$searchKey}%")
                        ->orWhere("consignee_name", "LIKE", "%{$searchKey}%")
                        ->orWhere("consignee_mobile", "LIKE", "%{$searchKey}%")
                        ->orWhere("consignee_email", "LIKE", "%{$searchKey}%")
                        ->orWhere("consignee_pincode", "LIKE", "%{$searchKey}%")
                        ->orWhere("consignee_city", "LIKE", "%{$searchKey}%")
                        ->orWhere("consignee_state", "LIKE", "%{$searchKey}%")
                        ->orWhere("consignee_address", "LIKE", "%{$searchKey}%")
                        ->orWhere("barcode_no", "LIKE", "%{$searchKey}%");
                });
            }

            $dcidResults = $dcidData->get();
        }
        $mergedCollection = $scidResults->merge($dcidResults);

        return view("franchise.indiaPost-speedPost.showReceivedParcelList", [
            "datas" => $mergedCollection,
        ]);
    }

    public function excelUploadByFranchise(Request $request)
    {
        if (!$request->hasFile("file")) {
            return back()->with("error", "No file uploaded.");
        }

        try {
            $file = $request->file("file");

            $destinationPath = public_path(
                "tenancy/assets/franchise/RoleUser/"
            );

            $fileName = uniqid() . "_" . $file->getClientOriginalName();

            $file->move($destinationPath, $fileName);

            // Load the Excel file

            $filePath = public_path(
                "tenancy/assets/franchise/RoleUser/$fileName"
            );

            if (!File::exists($filePath)) {
                return back()->with("error", "File not found.");
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
                        $rowData["Hyperlink"] = $hyperlinkUrl;
                    }

                    if (!is_null($cellValue) && $cellValue !== "") {
                        $hasData = true;
                    }
                }

                if ($hasData) {
                    if (is_null($header)) {
                        $header = $rowData;

                        $header[] = "Hyperlink";
                    } else {
                        if (count($rowData) < count($header)) {
                            $rowData = array_pad(
                                $rowData,
                                count($header),
                                null
                            );
                        }

                        $data[] = array_combine($header, $rowData);
                    }
                }
            }

            $desiredBarcode = [];
            foreach ($data as $key => $value) {
                $desiredBarcode[] = $value["Barcode"];
            }

            IndiaPostSpeedPostParcel::whereIn("barcode_no", $desiredBarcode)
                ->where(function ($query) {
                    $userId = Franchise::getFranchiseId();
                    $query
                        ->whereNull("scid_forfile_upload")
                        ->orWhere("scid_forfile_upload", $userId);
                })
                ->update([
                    "sfid_forfile_upload" => Franchise::getFranchiseId(),
                    "sfid_file_upload_date" => Carbon::today()->toDateString(),
                ]);

            IndiaPostSpeedPostParcel::whereIn("barcode_no", $desiredBarcode)
                ->whereNotNull("sfid_forfile_upload")
                ->where(
                    "sfid_forfile_upload",
                    "!=",
                    Franchise::getFranchiseId()
                )
                ->update([
                    "dfid_forfile_upload" => Franchise::getFranchiseId(),
                    "dfid_file_upload_date" => Carbon::today()->toDateString(),
                ]);

            $updatedParcels = IndiaPostSpeedPostParcel::whereIn(
                "barcode_no",
                $desiredBarcode
            )->get();

            return view(
                "franchise.indiaPost-speedPost.showReceivedParcelList",
                ["datas" => $updatedParcels]
            );
        } catch (\Exception $e) {
            return back()->with("error", $e->getMessage());
        }
    }

    public function downloadTableOfReceivedParcel(Request $request)
    {
        // Fetch data
        $searchKey = $request->input("searchKey");
        $date = $request->input("date");

        $userId = Franchise::getFranchiseId();
        // $insertType = $request->input('insert_type');
        $formattedDate =
            !empty($date) && $date !== ""
                ? Carbon::parse($date)->format("Y-m-d")
                : Carbon::today()->toDateString();

        $scidData = IndiaPostSpeedPostParcel::where(
            "sfid_forfile_upload",
            $userId
        );

        if ($scidData) {
            $scidData->where("sfid_file_upload_date", "=", $formattedDate);
            if ($searchKey) {
                $scidData->where(function ($query) use ($searchKey) {
                    $query
                        ->where("pickup_name", "LIKE", "%{$searchKey}%")
                        ->orWhere("pickup_mobile", "LIKE", "%{$searchKey}%")
                        ->orWhere("pickup_email", "LIKE", "%{$searchKey}%")
                        ->orWhere("pickup_pincode", "LIKE", "%{$searchKey}%")
                        ->orWhere("pickup_city", "LIKE", "%{$searchKey}%")
                        ->orWhere("pickup_state", "LIKE", "%{$searchKey}%")
                        ->orWhere("pickup_address", "LIKE", "%{$searchKey}%")
                        ->orWhere("consignee_name", "LIKE", "%{$searchKey}%")
                        ->orWhere("consignee_mobile", "LIKE", "%{$searchKey}%")
                        ->orWhere("consignee_email", "LIKE", "%{$searchKey}%")
                        ->orWhere("consignee_pincode", "LIKE", "%{$searchKey}%")
                        ->orWhere("consignee_city", "LIKE", "%{$searchKey}%")
                        ->orWhere("consignee_state", "LIKE", "%{$searchKey}%")
                        ->orWhere("consignee_address", "LIKE", "%{$searchKey}%")
                        ->orWhere("barcode_no", "LIKE", "%{$searchKey}%");
                });
            }

            $scidResults = $scidData->get();
        }

        $dcidData = IndiaPostSpeedPostParcel::where(
            "dfid_forfile_upload",
            $userId
        );

        if ($dcidData) {
            $dcidData->where("dfid_file_upload_date", "=", $formattedDate);

            if ($searchKey) {
                $dcidData->where(function ($query) use ($searchKey) {
                    $query
                        ->where("pickup_name", "LIKE", "%{$searchKey}%")
                        ->orWhere("pickup_mobile", "LIKE", "%{$searchKey}%")
                        ->orWhere("pickup_email", "LIKE", "%{$searchKey}%")
                        ->orWhere("pickup_pincode", "LIKE", "%{$searchKey}%")
                        ->orWhere("pickup_city", "LIKE", "%{$searchKey}%")
                        ->orWhere("pickup_state", "LIKE", "%{$searchKey}%")
                        ->orWhere("pickup_address", "LIKE", "%{$searchKey}%")
                        ->orWhere("consignee_name", "LIKE", "%{$searchKey}%")
                        ->orWhere("consignee_mobile", "LIKE", "%{$searchKey}%")
                        ->orWhere("consignee_email", "LIKE", "%{$searchKey}%")
                        ->orWhere("consignee_pincode", "LIKE", "%{$searchKey}%")
                        ->orWhere("consignee_city", "LIKE", "%{$searchKey}%")
                        ->orWhere("consignee_state", "LIKE", "%{$searchKey}%")
                        ->orWhere("consignee_address", "LIKE", "%{$searchKey}%")
                        ->orWhere("barcode_no", "LIKE", "%{$searchKey}%");
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
            "Sl",
            "Barcode",
            "Ref",
            "From Address",
            "ADD1",

            "ADD2",
            "ADD3",

            "Pincode",
            "City",
            "State",
            "Mobile",
            "Email",
            "To Address",
            "ADD1",
            "ADD2",

            "ADD3",

            "Pincode",
            "City",

            "State",
            "Mobile",
            "Email",
            "Weight",
        ];

        $column = "A";

        foreach ($headings as $heading) {
            $sheet->setCellValue($column . "1", $heading);

            $column++;
        }

        // Populate the data

        $row = 2; // Start from the second row

        foreach ($parcels as $key => $parcel) {
            // Process pickup address

            $pickupAddressParts = explode(",", $parcel->pickup_address);

            $PADD1 = $pickupAddressParts[0] ?? "";

            $PADD2 = $pickupAddressParts[1] ?? $PADD1;

            $PADD3 = $pickupAddressParts[2] ?? $PADD2;

            $consigneeAddressParts = explode(",", $parcel->consignee_address);

            $CADD1 = $consigneeAddressParts[0] ?? "";

            $CADD2 = $consigneeAddressParts[1] ?? $CADD1;

            $CADD3 = $consigneeAddressParts[2] ?? $CADD2;

            // Set cell values

            $sheet->setCellValue("A" . $row, $key + 1);

            $sheet->setCellValue("B" . $row, $parcel->barcode_no);

            $sheet->setCellValue(
                "C" . $row,
                IndiaPostSpeedPostParcel::getServiceType(
                    IndiaPostSpeedPostParcel::SERVICE_TYPE_INDIA_POST_SPEED
                )
            );

            $sheet->setCellValue("D" . $row, $parcel->pickup_address);

            $sheet->setCellValue("E" . $row, $PADD1);

            $sheet->setCellValue("F" . $row, $PADD2);

            $sheet->setCellValue("G" . $row, $PADD3);

            $sheet->setCellValue("H" . $row, $parcel->pickup_pincode);

            $sheet->setCellValue("I" . $row, $parcel->pickup_city);

            $sheet->setCellValue("J" . $row, $parcel->pickup_state);

            $sheet->setCellValue("K" . $row, $parcel->pickup_mobile);

            $sheet->setCellValue("L" . $row, $parcel->pickup_email);

            $sheet->setCellValue("M" . $row, $parcel->consignee_address);

            $sheet->setCellValue("N" . $row, $CADD1);

            $sheet->setCellValue("O" . $row, $CADD2);

            $sheet->setCellValue("P" . $row, $CADD3);

            $sheet->setCellValue("Q" . $row, $parcel->consignee_pincode);

            $sheet->setCellValue("R" . $row, $parcel->consignee_city);

            $sheet->setCellValue("S" . $row, $parcel->consignee_state);

            $sheet->setCellValue("T" . $row, $parcel->consignee_mobile);

            $sheet->setCellValue("U" . $row, $parcel->consignee_email);

            $sheet->setCellValue("V" . $row, $parcel->package_weight);

            $row++;
        }

        // Create a Writer

        $writer = new Xlsx($spreadsheet);

        // Create a response to stream the file

        $response = new StreamedResponse(function () use ($writer) {
            $writer->save("php://output");
        });

        $response->headers->set(
            "Content-Type",
            "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet"
        );

        $response->headers->set(
            "Content-Disposition",
            'attachment;filename="parcels.xlsx"'
        );

        $response->headers->set("Cache-Control", "max-age=0");

        return $response;
    }

    public function shortPrintForReceivedParcel(Request $request)
    {
        $date = $request->input("date");
        $searchKey = $request->input("searchKey");
        $userId = Franchise::getFranchiseId();

        $franchise = Franchise::findOrFail($userId);
        $linkDetail = IndiaPostLink::where(
            "franchise_no",
            $franchise->franchise_no
        )->first();

        $formattedDate =
            !empty($date) && $date !== ""
                ? Carbon::parse($date)->format("Y-m-d")
                : Carbon::today()->toDateString();

        $scidData = IndiaPostSpeedPostParcel::where(
            "sfid_forfile_upload",
            $userId
        );

        if ($scidData) {
            $scidData->where("sfid_file_upload_date", "=", $formattedDate);
            if ($searchKey) {
                $scidData->where(function ($query) use ($searchKey) {
                    $query
                        ->where("pickup_name", "LIKE", "%{$searchKey}%")
                        ->orWhere("pickup_mobile", "LIKE", "%{$searchKey}%")
                        ->orWhere("pickup_email", "LIKE", "%{$searchKey}%")
                        ->orWhere("pickup_pincode", "LIKE", "%{$searchKey}%")
                        ->orWhere("pickup_city", "LIKE", "%{$searchKey}%")
                        ->orWhere("pickup_state", "LIKE", "%{$searchKey}%")
                        ->orWhere("pickup_address", "LIKE", "%{$searchKey}%")
                        ->orWhere("consignee_name", "LIKE", "%{$searchKey}%")
                        ->orWhere("consignee_mobile", "LIKE", "%{$searchKey}%")
                        ->orWhere("consignee_email", "LIKE", "%{$searchKey}%")
                        ->orWhere("consignee_pincode", "LIKE", "%{$searchKey}%")
                        ->orWhere("consignee_city", "LIKE", "%{$searchKey}%")
                        ->orWhere("consignee_state", "LIKE", "%{$searchKey}%")
                        ->orWhere("consignee_address", "LIKE", "%{$searchKey}%")
                        ->orWhere("barcode_no", "LIKE", "%{$searchKey}%");
                });
            }

            $scidResults = $scidData->get();
        }

        $dcidData = IndiaPostSpeedPostParcel::where(
            "dfid_forfile_upload",
            $userId
        );

        if ($dcidData) {
            $dcidData->where("dfid_file_upload_date", "=", $formattedDate);

            if ($searchKey) {
                $dcidData->where(function ($query) use ($searchKey) {
                    $query
                        ->where("pickup_name", "LIKE", "%{$searchKey}%")
                        ->orWhere("pickup_mobile", "LIKE", "%{$searchKey}%")
                        ->orWhere("pickup_email", "LIKE", "%{$searchKey}%")
                        ->orWhere("pickup_pincode", "LIKE", "%{$searchKey}%")
                        ->orWhere("pickup_city", "LIKE", "%{$searchKey}%")
                        ->orWhere("pickup_state", "LIKE", "%{$searchKey}%")
                        ->orWhere("pickup_address", "LIKE", "%{$searchKey}%")
                        ->orWhere("consignee_name", "LIKE", "%{$searchKey}%")
                        ->orWhere("consignee_mobile", "LIKE", "%{$searchKey}%")
                        ->orWhere("consignee_email", "LIKE", "%{$searchKey}%")
                        ->orWhere("consignee_pincode", "LIKE", "%{$searchKey}%")
                        ->orWhere("consignee_city", "LIKE", "%{$searchKey}%")
                        ->orWhere("consignee_state", "LIKE", "%{$searchKey}%")
                        ->orWhere("consignee_address", "LIKE", "%{$searchKey}%")
                        ->orWhere("barcode_no", "LIKE", "%{$searchKey}%");
                });
            }

            $dcidResults = $dcidData->get();
        }

        $mergedCollection = $scidResults->merge($dcidResults);

        $title = \App\Models\Admin::INDIA_POST_SPEED;
        // Pass data to the view
        $otherPageContent = View::make("print.indiaPost.shortPrint", [
            "data" => $mergedCollection,
            "linkDetail" => $linkDetail,
            "title" => $title,
        ])->render();

        return response()->json([
            "otherPageContent" => $otherPageContent,
        ]);
    }

    public function trackOrder($id)
    {
        $trackingDetails = IndiaPostSpeedPostTrackOrder::where(
            "parcel_id",
            $id
        )->first();
        return response()->json([
            "trackingDetails" => $trackingDetails,
        ]);
    }

    public function getNextBarcode()
    {
        $user = Auth::guard("franchise")->user();
        $state = $user->state; // Get the state of the franchise

        try {
            // Get the next available barcode based on state
            $barcode = IndiaPostBarcode::where("availables", ">", 0)
            ->where('service_type', IndiaPostSpeedPostParcel::SERVICE_TYPE_INDIA_POST_SPEED)->first();
            if ($barcode) {
                $nextBarcode = $barcode->getNextBarcode();
                // Increment range_from and decrement availables
                $barcode->increment("range_from");
                $barcode->decrement("availables");

                return $nextBarcode;
            } else {
                return "barcode not found";
            }
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function allprint(Request $request)
    {
        //    return  $request->type;
        $ids = is_array($request->id)
            ? $request->id
            : explode(",", $request->id);
        $franchiseId = Franchise::getFranchiseId();
        $franchise = Franchise::findOrFail($franchiseId);
        //    return $linkDetail = GotogoLink::where('franchise_no', $franchise->franchise_no)->first();
        $linkDetail = IndiaPostLink::where(
            "franchise_no",
            $franchise->franchise_no
        )->first();
        if ($request->type == "cod") {
            $parcels = IndiaPostSpeedPostParcel::where(
                "franchise_id",
                Franchise::getFranchiseId()
            )
                ->whereIn("id", $ids)
                ->where("payment_method", "cod")
                ->get();
            $otherPageContent = View::make("print.indiaPost.codPrint", [
                "data" => $parcels,
                "linkDetail" => $linkDetail,
                "franchise" => $franchise,
                "type" => 5,
            ])->render();
        } else {
            $parcels = IndiaPostSpeedPostParcel::where(
                "franchise_id",
                Franchise::getFranchiseId()
            )
                ->whereIn("id", $ids)
                ->where("payment_method", "prepaid")
                ->get();
            $otherPageContent = View::make("print.indiaPost.prepaidLabel3", [
                "data" => $parcels,
                "linkDetail" => $linkDetail,
                "franchise" => $franchise,
                "type" => 5,
            ])->render();
        }

        return response()->json([
            "otherPageContent" => $otherPageContent,
        ]);
    }

    private function getOrCreateCodUser(Request $request, $franchise): ?int
    {
        // Check if COD user already exists
        $codCheck = NoRegisterCustomer::where("phone", $request->PickupMobile)
            ->where("franchise_id", $franchise->id) // Corrected field name
            ->where("type", "franchise")
            ->first();

        if ($codCheck) {
            return $codCheck->id;
        }

        // Generate a random 10-digit password
        $password = substr(str_shuffle("0123456789"), 0, 10);

        // Create new COD user
        $cod = new NoRegisterCustomer();
        $cod->type = "franchise";
        $cod->franchise_id = $franchise->id;
        $cod->name = $request->PickupName;
        $cod->phone = $request->PickupMobile;
        $cod->gst_no = $request->PickupGstNo;
        $cod->email = $request->PickupEmail;
        $cod->pincode = $request->PickupPincode;
        $cod->city = $request->PickupCity;
        $cod->state = $request->PickupState;
        $cod->address = $request->PickupAddress;
        $cod->password = Hash::make($password);
        $cod->save();

        return $cod->id;
    }

    public function getExcel($request, $parcels)
    {
        $spreadsheet = new Spreadsheet();

        $sheet = $spreadsheet->getActiveSheet();

        // Set the headings

        $headings = [
            "SERIAL NUMBER",
            "BARCODE NO",
            "PHYSICAL WEIGHT",
            "RECEIVER CITY",
            "RECEIVER PINCODE",
            "RECEIVER NAME",
            "RECEIVER ADD LINE 1",
            "RECEIVER ADD LINE 2",
            "RECEIVER ADD LINE 3",
            "FALSE",
            "SENDER MOBILE NO",
            "RECEIVER MOBILE NO",
            "PREPAYMENT CODE",
            "VALUE OF PREPAYMENT",
            "CODR/COD",
            "VALUE FOR CODR/COD",
            "INSURANCE TYPE",
            "VALUE OF INSURANCE",
            "SHAPE OF ARTICLE",
            "LENGTH",
            "BREADTH/DIAMETER",
            "HEIGHT",
            "PRIORITY FLAG",
            "DELIVERY INSTRUCTION",
            "DELIVERY SLOT",
            "INSTRUCTION RTS",
            "SENDER NAME",
            "SENDER COMPANY NAME",
            "SENDER CITY",
            "SENDER STATE/UT",
            "SENDER PINCODE",
            "SENDER EMAILID",
            "SENDER ALT CONTACT",
            "SENDER KYC",
            "SENDER TAX",
            "RECEIVER COMPANY NAME",
            "RECEIVER STATE/UT",
            "RECEIVER EMAILID",
            "RECEIVER ALT CONTACT",
            "RECEIVER KYC",
            "RECEIVER TAX REF",
            "ALT ADDRESS FLAG",
            "BULK REFERENCE",
            "SENDER ADD LINE 1",
            "SENDER ADD LINE 2",
            "SENDER ADD LINE 3",
        ];

        $redHeaders = [
            "SERIAL NUMBER",
            "PHYSICAL WEIGHT",
            "RECEIVER CITY",
            "RECEIVER PINCODE",
            "RECEIVER NAME",
            "RECEIVER ADD LINE 1",
            "RECEIVER ADD LINE 2",
            "FALSE",
            "SENDER MOBILE NO",
            "RECEIVER MOBILE NO",
            "SENDER NAME",
            "SENDER CITY",
            "SENDER STATE/UT",
            "SENDER PINCODE",
            "RECEIVER STATE/UT",
            "ALT ADDRESS FLAG",
            "SENDER ADD LINE 1",
            "SENDER ADD LINE 2",
        ];

        $column = "A";
        foreach ($headings as $heading) {
            $cell = $column . "1";
            $sheet->setCellValue($cell, $heading);

            // Apply red font color if in list
            if (in_array(strtoupper(trim($heading)), $redHeaders)) {
                $sheet
                    ->getStyle($cell)
                    ->getFont()
                    ->getColor()
                    ->setRGB(Color::COLOR_RED); // FF0000
            }

            // Make header bold
            $sheet
                ->getStyle($cell)
                ->getFont()
                ->setBold(true);

            $column++;
        }

        // Populate the data

        $row = 2; // Start from the second row

        foreach ($parcels as $key => $parcel) {
            // Process pickup address

            $pickupAddressParts = explode(",", $parcel->pickup_address);

            $PADD1 = $pickupAddressParts[0] ?? "";

            $PADD2 = $pickupAddressParts[1] ?? $PADD1;

            $PADD3 = $pickupAddressParts[2] ?? $PADD2;

            $consigneeAddressParts = explode(",", $parcel->consignee_address);

            $CADD1 = $consigneeAddressParts[0] ?? "";

            $CADD2 = $consigneeAddressParts[1] ?? $CADD1;

            $CADD3 = $consigneeAddressParts[2] ?? $CADD2;

            // Set cell values

            $sheet->setCellValue("A" . $row, $key + 1);

            $sheet->setCellValue("B" . $row, $parcel->barcode_no);

            // $sheet->setCellValue('C' . $row, IndiaPostSpeedPostParcel::getServiceType(IndiaPostSpeedPostParcel::SERVICE_TYPE_INDIA_POST_SPEED));
            $sheet->setCellValue("C" . $row, $parcel->package_weight);
            $sheet->setCellValue("D" . $row, $parcel->consignee_city);
            $sheet->setCellValue("E" . $row, $parcel->consignee_pincode);
            $sheet->setCellValue("F" . $row, $parcel->consignee_name);
            $sheet->setCellValue("G" . $row, $CADD1);
            $sheet->setCellValue("H" . $row, $CADD2);
            $sheet->setCellValue("I" . $row, $CADD3);
            $sheet->setCellValue("J" . $row, "FALSE");
            $sheet->setCellValue("K" . $row, $parcel->pickup_mobile);
            $sheet->setCellValue("L" . $row, $parcel->consignee_mobile);
            $sheet->setCellValue("M" . $row, "");
            $sheet->setCellValue("N" . $row, "");
            $sheet->setCellValue("O" . $row, "");
            $sheet->setCellValue("P" . $row, "");
            $sheet->setCellValue("Q" . $row, "");
            $sheet->setCellValue("R" . $row, "");
            $sheet->setCellValue("S" . $row, "");
            $sheet->setCellValue("T" . $row, "");
            $sheet->setCellValue("U" . $row, "");
            $sheet->setCellValue("V" . $row, "");
            $sheet->setCellValue("W" . $row, "");
            $sheet->setCellValue("X" . $row, "");
            $sheet->setCellValue("Y" . $row, "");
            $sheet->setCellValue("Z" . $row, "");

            $sheet->setCellValue("AA" . $row, $parcel->pickup_name);
            $sheet->setCellValue("AB" . $row, "");
            $sheet->setCellValue("AC" . $row, $parcel->pickup_city);
            $sheet->setCellValue("AD" . $row, $parcel->pickup_state);
            $sheet->setCellValue("AE" . $row, $parcel->pickup_pincode);
            $sheet->setCellValue("AF" . $row, $parcel->pickup_email);
            $sheet->setCellValue("AG" . $row, "");
            $sheet->setCellValue("AH" . $row, "");
            $sheet->setCellValue("AI" . $row, "");
            $sheet->setCellValue("AJ" . $row, "");
            $sheet->setCellValue("AK" . $row, $parcel->consignee_state);
            $sheet->setCellValue("AL" . $row, $parcel->consignee_email);
            $sheet->setCellValue("AM" . $row, "");
            $sheet->setCellValue("AN" . $row, "");
            $sheet->setCellValue("AO" . $row, "");
            $sheet->setCellValue("AP" . $row, "FALSE");
            $sheet->setCellValue("AQ" . $row, "");
            $sheet->setCellValue("AR" . $row, $PADD1);
            $sheet->setCellValue("AS" . $row, $PADD2);
            $sheet->setCellValue("AT" . $row, $PADD3);

            $row++;
        }

        // Create a Writer

        $writer = new Xlsx($spreadsheet);

        // Create a response to stream the file

        $response = new StreamedResponse(function () use ($writer) {
            $writer->save("php://output");
        });

        $response->headers->set(
            "Content-Type",
            "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet"
        );

        $response->headers->set(
            "Content-Disposition",
            'attachment;filename="parcels.xlsx"'
        );

        $response->headers->set("Cache-Control", "max-age=0");

        return $response;
    }

    public function getBookingDetails(Request $request)
    {
        $html =
            "<select name='payment_type' id='payment_type' class='select form-select' data-tags='true' data-placeholder='Select an option'><option selected disabled>Select Option</option>";
        $pickupDetails = [];

        if ($request->select == "franchise") {
            $pickupDetails = Franchise::where("id", Franchise::getFranchiseId())
                ->where("status", 1)
                ->select("id", "name", "mobile as phone")
                ->get();
        } elseif ($request->select == "manager") {
            $pickupDetails = MManager::where(
                "franchise_id",
                Franchise::getFranchiseId()
            )
                ->where("status", 1)
                ->select("id", "name", "mobile as phone")
                ->get();
        } else {
            $pickupDetails = PickupDetails::where(
                "franchise_id",
                Franchise::getFranchiseId()
            )
                ->where("status", 1)
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

        return response()->json(["html" => $html]);
    }

    public function resolveCustomer($element, $franchiseId)
{
    // Case 1: Market Manager
    if (!empty($element["Market Manager _id"])) {
        $manager = MManager::where("mobile", $element["Market Manager _id"])
            ->where("franchise_id", $franchiseId)
            ->first();

        if (!$manager) {
            throw new \Exception("Market Manager Not Found (" . $element["Market Manager _id"] . ")");
        }

        $customer = NoRegisterCustomer::where([
            ["phone", $element["Pickup Phone"]],
            ["type", "manager"],
            ["market_id", $manager->id],
            ["franchise_id", $franchiseId],
        ])->first();

        if (!$customer) {
            throw new \Exception("Customer associated with Market Manager Not Found");
        }

        return $customer;
    }

    // Case 2: Pickup Boy
    if (!empty($element["Pickup Boy_id"])) {
        $pickupBoy = PickupDetails::where("phone", $element["Pickup Boy_id"])
            ->where("franchise_id", $franchiseId)
            ->first();

        if (!$pickupBoy) {
            throw new \Exception("Pickup Boy Not Found (" . $element["Pickup Boy_id"] . ")");
        }

        $customer = NoRegisterCustomer::where([
            ["phone", $element["Pickup Phone"]],
            ["type", "pickup"],
            ["market_id", $pickupBoy->id],
            ["franchise_id", $franchiseId],
        ])->first();

        if (!$customer) {
            throw new \Exception("Customer associated with Pickup Boy Not Found");
        }

        return $customer;
    }

    // Case 3: Neither Market Manager nor Pickup Boy → Franchise Type
    if (empty($element["Pickup Boy_id"]) && empty($element["Market Manager _id"])) {
        $existing = NoRegisterCustomer::where([
            ["phone", $element["Pickup Phone"]],
            ["type", "franchise"],
            ["franchise_id", $franchiseId],
        ])->first();

        if ($existing) {
            return $existing; 
        }

        // Create new customer
        $password = substr(str_shuffle("0123456789"), 0, 10);

        return NoRegisterCustomer::create([
            "name" => $element["Pickup Name"] ?? "",
            "type" => "franchise",
            "franchise_id" => $franchiseId,
            "phone" => $element["Pickup Phone"] ?? "",
            "email" => $element["Pickup Email"] ?? "",
            "gst_no" => $element["Pickup gst"] ?? "",
            "pincode" => $element["Pickup Pincode"] ?? "",
            "city" => $element["Pickup City"] ?? "",
            "state" => $element["Pickup State"] ?? "",
            "address" => $element["Pickup Address"] ?? "",
            "password" => Hash::make($password),
        ]);
    }

    throw new \Exception("Unable to resolve customer type");
}
    public function expressDetails(Request $request)
    {
        // return Franchise::getFranchiseId();
        // return $request->all();
        // Use provided select value or fallback to franchise ID
        $id = filled($request->select)
            ? $request->select
            : Franchise::getFranchiseId();

        // Determine type based on 'select' value
        $type =
            $request->type === "franchise"
                ? "franchise"
                : ($request->type === "manager"
                    ? "manager"
                    : "pickup");

        // Fetch data based on type and franchise ID
        if ($request->cod == "cod") {
            $pickupDetails = ECustomer::where("cph_link", $request->id)
                ->select("id", "name", "mobile as phone")
                ->get();
        } else {
            $query = NoRegisterCustomer::where(
                "franchise_id",
                Franchise::getFranchiseId()
            );

            if ($type == "franchise") {
                $query->where("franchise_id", $id);
            } else {
                $query->where("market_id", $id);
            }

            $pickupDetails = $query->where("type", $type)->get();
        }
        // Start building the HTML select element
        $html =
            "<select name='payment_method' id='paymentMethod' class='select form-select' data-tags='true' data-placeholder='Select an option'> <option selected disabled>Select Option</option>";

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
            "html" => $html,
            "data" => $pickupDetails->isNotEmpty() ? $pickupDetails : null,
        ]);
    }

    public function GetTotalAmount(Request $request)
    {
        $totalParcels = 0;
        $totalAmount = 0;
        $CodtotalAmount = 0;

        // Build the query (don't run it yet)
        $dataQuery = IndiaPostSpeedPostParcel::where(
            "franchise_id",
            Franchise::getFranchiseId()
        )
            ->whereIn("id", $request->id)
            ->orderBy("created_at", "desc");

        // Clone and calculate totals
        $CodtotalAmount = (clone $dataQuery)->sum("cod_amount");
        $totalAmount = (clone $dataQuery)->sum("payment_amount");
        $totalParcels = (clone $dataQuery)->count();

        return response()->json([
            "status" => "success",
            "CodtotalAmount" => $CodtotalAmount,
            "totalAmount" => $totalAmount,
            "totalParcels" => $totalParcels,
            "message" => "Amount fetched successfully",
        ]);
    }
    //  Start cancel
public function cancel(Request $request, $id)
{
    $request->validate([
        'cancel_reason' => 'required|string',
    ]);

    DB::transaction(function () use ($request, $id) {
        $franchiseId = Franchise::getFranchiseId(); 
        $franchise = Franchise::findOrFail($franchiseId);

        $parcel = IndiaPostSpeedPostParcel::findOrFail($id);

        // Increment franchise balance
        $franchise->increment("indiapost_balance", $parcel->payment_amount);

        // Update parcel
        $parcel->status = 2; // 2 = Cancelled
        $parcel->cancel = $request->cancel_reason;
        $parcel->cancel_date = Carbon::today();
        $parcel->save();
    });

    return redirect()->route('franchise.india-post-speed-post.cancel.index')->with('success', 'Parcel has been cancelled successfully.');

    // return redirect()->back()->with('success', 'Parcel has been cancelled successfully.');
}

 public function cancel_index(Request $request, RateCalculator $rateCalculator){
   $totalParcels = 0;
        $totalAmount = 0;
        $CodtotalAmount = 0;
        $type = $request->type;
        $searchKey = $request->input("searchKey");

        $fromdate = $request->input("fromdate");
        $todate = $request->input("todate");

        $franchise = Franchise::getFranchiseId();

        $query = IndiaPostSpeedPostParcel::where(
            "franchise_id",
            Franchise::getFranchiseId()
        )

            // ->where("insert_type", $request->insert_type)
             ->where("status", 2)

            ->orderBy("cancel_date", "desc");

        if ($request->type == "cod") {
            $query->where("payment_method", "cod");
        } else {
            $query->where("payment_method", "prepaid");
        }

        if ($searchKey) {
            $query->where(function ($q) use ($searchKey) {
                $q->where("pickup_name", "LIKE", "%{$searchKey}%")

                    ->orWhere("pickup_mobile", "LIKE", "%{$searchKey}%")

                    ->orWhere("pickup_email", "LIKE", "%{$searchKey}%")

                    ->orWhere("pickup_pincode", "LIKE", "%{$searchKey}%")

                    ->orWhere("pickup_city", "LIKE", "%{$searchKey}%")

                    ->orWhere("pickup_state", "LIKE", "%{$searchKey}%")

                    ->orWhere("pickup_address", "LIKE", "%{$searchKey}%")

                    ->orWhere("consignee_name", "LIKE", "%{$searchKey}%")

                    ->orWhere("consignee_mobile", "LIKE", "%{$searchKey}%")

                    ->orWhere("consignee_email", "LIKE", "%{$searchKey}%")

                    ->orWhere("consignee_pincode", "LIKE", "%{$searchKey}%")

                    ->orWhere("consignee_city", "LIKE", "%{$searchKey}%")

                    ->orWhere("consignee_state", "LIKE", "%{$searchKey}%")

                    ->orWhere("consignee_address", "LIKE", "%{$searchKey}%")

                    ->orWhere("barcode_no", "LIKE", "%{$searchKey}%");
            });

            if (!empty($fromdate) || !empty($todate)) {
                if (!empty($fromdate) && !empty($todate)) {
                    $fromdate = Carbon::createFromFormat("d-m-Y", $fromdate)
                        ->startOfDay()
                        ->toDateString();
                    $todate = Carbon::createFromFormat("d-m-Y", $todate)
                        ->startOfDay()
                        ->toDateString();
                    $query->whereBetween("cancel_date", [$fromdate, $todate]);
                } elseif (!empty($fromdate)) {
                    // सिर्फ fromdate मिला
                    $fromdate = Carbon::createFromFormat(
                        "d-m-Y",
                        $fromdate
                    )->toDateString();
                    $query->whereDate("cancel_date", $fromdate);
                } elseif (!empty($todate)) {
                    // सिर्फ todate मिला
                    $todate = Carbon::createFromFormat(
                        "d-m-Y",
                        $todate
                    )->toDateString();
                    $query->whereDate("cancel_date", $todate);
                }
            }

            $datas = $query->get();
            $CodtotalAmount = (clone $query)->sum("cod_amount");
            $totalAmount = (clone $query)->sum("payment_amount");
            $totalParcels = (clone $query)->count();

            return view(
                "franchise.indiaPost-speedPost.cancelParcel",
                compact(
                    "datas",
                    "type",
                    "totalAmount",
                    "totalParcels",
                    "CodtotalAmount",
                    "franchise"
                )
            );
        }

        if (!empty($fromdate) || !empty($todate)) {
            if (!empty($fromdate) && !empty($todate)) {
                $fromdate = Carbon::createFromFormat("d-m-Y", $fromdate)
                    ->startOfDay()
                    ->toDateString();
                $todate = Carbon::createFromFormat("d-m-Y", $todate)
                    ->startOfDay()
                    ->toDateString();
                $query->whereBetween("cancel_date", [$fromdate, $todate]);
            } elseif (!empty($fromdate)) {
                // सिर्फ fromdate मिला
                $fromdate = Carbon::createFromFormat(
                    "d-m-Y",
                    $fromdate
                )->toDateString();
                $query->whereDate("cancel_date", $fromdate);
            } elseif (!empty($todate)) {
                // सिर्फ todate मिला
                $todate = Carbon::createFromFormat(
                    "d-m-Y",
                    $todate
                )->toDateString();
                $query->whereDate("cancel_date", $todate);
            }
            $datas = $query->get();
            $CodtotalAmount = (clone $query)->sum("cod_amount");
            $totalAmount = (clone $query)->sum("payment_amount");
            $totalParcels = (clone $query)->count();
            return view(
                "franchise.indiaPost-speedPost.cancelParcel",
                compact(
                    "datas",
                    "type",
                    "totalAmount",
                    "totalParcels",
                    "CodtotalAmount",
                    "franchise"
                )
            );
        } else {
            $query->whereDate("cancel_date", Carbon::today());
            $datas = $query->get();
            $CodtotalAmount = (clone $query)->sum("cod_amount");
            $totalAmount = (clone $query)->sum("payment_amount");
            $totalParcels = (clone $query)->count();

            return view(
                "franchise.indiaPost-speedPost.cancelParcel",
                compact(
                    "datas",
                    "type",
                    "totalAmount",
                    "totalParcels",
                    "CodtotalAmount",
                    "franchise"
                )
            );
        }
 }

     public function shortPrintForCreatedParcelCancel(Request $request)
    {

    //   return $request->all();
        $fromdate = $request->input("fromdate");
        $todate = $request->input("todate");
        $searchKey = $request->input("searchKey");
        $type = $request->input("type");

        if ($type === "cod") {
            $view = "print.indiaPost.codPrint";
        } else {
            $view = "print.indiaPost.prepaidRecipt";
            // $view = 'print.indiaPost.prepaidPrint';
        }

        $franchiseId = Franchise::getFranchiseId();
        $franchise = Franchise::findOrFail($franchiseId);
        $linkDetail = IndiaPostLink::where("franchise_no", $franchise->franchise_no)->first();
        $query = IndiaPostSpeedPostParcel::where("franchise_id", $franchiseId)

            // ->where("insert_type", $request->insert_type)
             ->where("status", 2)
            ->orderBy("cancel_date", "desc");
        $selectedIds = array_map("intval", $request->input("ids", []));
        if (!empty($selectedIds)) {
            $query->whereIn("id", $selectedIds);
        }
        // Filter by payment method
        if ($type === "cod") {
            $query->where("payment_method", "cod");
        } else {
            $query->where("payment_method", "prepaid");
        }

        if ($searchKey) {
            $query->where(function ($q) use ($searchKey) {
                $q->where("pickup_name", "LIKE", "%{$searchKey}%")

                    ->orWhere("pickup_mobile", "LIKE", "%{$searchKey}%")

                    ->orWhere("pickup_email", "LIKE", "%{$searchKey}%")

                    ->orWhere("pickup_pincode", "LIKE", "%{$searchKey}%")

                    ->orWhere("pickup_city", "LIKE", "%{$searchKey}%")

                    ->orWhere("pickup_state", "LIKE", "%{$searchKey}%")

                    ->orWhere("pickup_address", "LIKE", "%{$searchKey}%")

                    ->orWhere("consignee_name", "LIKE", "%{$searchKey}%")

                    ->orWhere("consignee_mobile", "LIKE", "%{$searchKey}%")

                    ->orWhere("consignee_email", "LIKE", "%{$searchKey}%")

                    ->orWhere("consignee_pincode", "LIKE", "%{$searchKey}%")

                    ->orWhere("consignee_city", "LIKE", "%{$searchKey}%")

                    ->orWhere("consignee_state", "LIKE", "%{$searchKey}%")

                    ->orWhere("consignee_address", "LIKE", "%{$searchKey}%")

                    ->orWhere("barcode_no", "LIKE", "%{$searchKey}%");
            });

            if (!empty($fromdate) || !empty($todate)) {
                if (!empty($fromdate) && !empty($todate)) {
                    $fromdate = Carbon::createFromFormat("d-m-Y", $fromdate)
                        ->startOfDay()
                        ->toDateString();
                    $todate = Carbon::createFromFormat("d-m-Y", $todate)
                        ->startOfDay()
                        ->toDateString();
                    $query->whereBetween("cancel_date", [$fromdate, $todate]);
                } elseif (!empty($fromdate)) {
                    // सिर्फ fromdate मिला
                    $fromdate = Carbon::createFromFormat(
                        "d-m-Y",
                        $fromdate
                    )->toDateString();
                    $query->whereDate("cancel_date", $fromdate);
                } elseif (!empty($todate)) {
                    // सिर्फ todate मिला
                    $todate = Carbon::createFromFormat(
                        "d-m-Y",
                        $todate
                    )->toDateString();
                    $query->whereDate("cancel_date", $todate);
                }
            }
             

            $parcels = $query->get();

            $title = \App\Models\Admin::INDIA_POST_SPEED;

            $otherPageContent = View::make($view, [
                "data" => $parcels,
                "linkDetail" => $linkDetail,
                "title" => $title,
                "franchise" => $franchise,
                "franchise_no" => $franchise->franchise_no,
                "type" => 5,
            ])->render();
            return response()->json([
                "otherPageContent" => $otherPageContent,
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
                $query->whereBetween("cancel_date", [$fromdate, $todate]);
            } elseif (!empty($fromdate)) {
                // सिर्फ fromdate मिला
                $fromdate = Carbon::createFromFormat(
                    "d-m-Y",
                    $fromdate
                )->toDateString();
                $query->whereDate("cancel_date", $fromdate);
            } elseif (!empty($todate)) {
                // सिर्फ todate मिला
                $todate = Carbon::createFromFormat(
                    "d-m-Y",
                    $todate
                )->toDateString();
                $query->whereDate("cancel_date", $todate);
            }
            $parcels = $query->get();
        } else {
            $query->whereDate("cancel_date", Carbon::today());
            $parcels = $query->get();
        }

        $title = \App\Models\Admin::INDIA_POST_SPEED;

        $otherPageContent = View::make($view, [
            "data" => $parcels,
            "linkDetail" => $linkDetail,
            "title" => $title,
            "franchise" => $franchise,
            "franchise_no" => $franchise->franchise_no,
            "type" => 5,
        ])->render();

        return response()->json([
            "otherPageContent" => $otherPageContent,
        ]);
    }

    public function shortLabelForCreatedParcelCancel(Request $request)
    {
      
        $fromdate = $request->input("fromdate");
        $todate = $request->input("todate");
        $searchKey = $request->input("searchKey");
        $type = $request->input("type");

        if ($type === "cod") {
            $view = "print.indiaPost.codPrint";
        } else {
            $view = "print.indiaPost.prepaidLabel";
            // $view = 'print.indiaPost.prepaidPrint';
        }

        $franchiseId = Franchise::getFranchiseId();
        $franchise = Franchise::findOrFail($franchiseId);
        $linkDetail = IndiaPostLink::where(
            "franchise_no",
            $franchise->franchise_no
        )->first();
        $query = IndiaPostSpeedPostParcel::where(
            "franchise_id",
            Franchise::getFranchiseId()
        )

            // ->where("insert_type", $request->insert_type)
           ->where("status", 2)
            ->orderBy("cancel_date", "desc");
        $selectedIds = array_map("intval", $request->input("ids", []));
        if (!empty($selectedIds)) {
            $query->whereIn("id", $selectedIds);
        }
        // Filter by payment method
        if ($type === "cod") {
            $query->where("payment_method", "cod");
        } else {
            $query->where("payment_method", "prepaid");
        }

        if ($searchKey) {
            $query->where(function ($q) use ($searchKey) {
                $q->where("pickup_name", "LIKE", "%{$searchKey}%")

                    ->orWhere("pickup_mobile", "LIKE", "%{$searchKey}%")

                    ->orWhere("pickup_email", "LIKE", "%{$searchKey}%")

                    ->orWhere("pickup_pincode", "LIKE", "%{$searchKey}%")

                    ->orWhere("pickup_city", "LIKE", "%{$searchKey}%")

                    ->orWhere("pickup_state", "LIKE", "%{$searchKey}%")

                    ->orWhere("pickup_address", "LIKE", "%{$searchKey}%")

                    ->orWhere("consignee_name", "LIKE", "%{$searchKey}%")

                    ->orWhere("consignee_mobile", "LIKE", "%{$searchKey}%")

                    ->orWhere("consignee_email", "LIKE", "%{$searchKey}%")

                    ->orWhere("consignee_pincode", "LIKE", "%{$searchKey}%")

                    ->orWhere("consignee_city", "LIKE", "%{$searchKey}%")

                    ->orWhere("consignee_state", "LIKE", "%{$searchKey}%")

                    ->orWhere("consignee_address", "LIKE", "%{$searchKey}%")

                    ->orWhere("barcode_no", "LIKE", "%{$searchKey}%");
            });

            if (!empty($fromdate) || !empty($todate)) {
                if (!empty($fromdate) && !empty($todate)) {
                    $fromdate = Carbon::createFromFormat("d-m-Y", $fromdate)
                        ->startOfDay()
                        ->toDateString();
                    $todate = Carbon::createFromFormat("d-m-Y", $todate)
                        ->startOfDay()
                        ->toDateString();
                    $query->whereBetween("cancel_date", [$fromdate, $todate]);
                } elseif (!empty($fromdate)) {
                    // सिर्फ fromdate मिला
                    $fromdate = Carbon::createFromFormat(
                        "d-m-Y",
                        $fromdate
                    )->toDateString();
                    $query->whereDate("cancel_date", $fromdate);
                } elseif (!empty($todate)) {
                    // सिर्फ todate मिला
                    $todate = Carbon::createFromFormat(
                        "d-m-Y",
                        $todate
                    )->toDateString();
                    $query->whereDate("cancel_date", $todate);
                }
            }

            $parcels = $query->get();

            $title = \App\Models\Admin::INDIA_POST_SPEED;

            $otherPageContent = View::make($view, [
                "data" => $parcels,
                "linkDetail" => $linkDetail,
                "title" => $title,
                "franchise" => $franchise,
                "type" => 5,
            ])->render();
            return response()->json([
                "otherPageContent" => $otherPageContent,
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
                $query->whereBetween("cancel_date", [$fromdate, $todate]);
            } elseif (!empty($fromdate)) {
                // सिर्फ fromdate मिला
                $fromdate = Carbon::createFromFormat(
                    "d-m-Y",
                    $fromdate
                )->toDateString();
                $query->whereDate("cancel_date", $fromdate);
            } elseif (!empty($todate)) {
                // सिर्फ todate मिला
                $todate = Carbon::createFromFormat(
                    "d-m-Y",
                    $todate
                )->toDateString();
                $query->whereDate("cancel_date", $todate);
            }

            $parcels = $query->get();
        } else {
            $query->whereDate("cancel_date", Carbon::today());
            $parcels = $query->get();
        }

        $title = \App\Models\Admin::INDIA_POST_SPEED;

        $otherPageContent = View::make($view, [
            "data" => $parcels,
            "linkDetail" => $linkDetail,
            "title" => $title,
            "franchise" => $franchise,
            "type" => 5,
        ])->render();

        return response()->json([
            "otherPageContent" => $otherPageContent,
        ]);
    }

    public function downloadTableForCreatedTableCancel(Request $request)
    {
        // Fetch data
        $fromdate = $request->input("fromdate");
        $todate = $request->input("todate");
        $searchKey = $request->input("searchKey");

        $type = $request->input("type");

        $query = IndiaPostSpeedPostParcel::where(
            "franchise_id",
            Franchise::getFranchiseId()
        )

            // ->where("insert_type", $request->insert_type)
           ->where("status", 2)
            ->orderBy("cancel_date", "desc");
            
        if ($type === "cod") {
            $query->where("payment_method", "cod");
        } else {
            $query->where("payment_method", "prepaid");
        }

        if ($searchKey) {
            $query->where(function ($q) use ($searchKey) {
                $q->where("pickup_name", "LIKE", "%{$searchKey}%")

                    ->orWhere("pickup_mobile", "LIKE", "%{$searchKey}%")

                    ->orWhere("pickup_email", "LIKE", "%{$searchKey}%")

                    ->orWhere("pickup_pincode", "LIKE", "%{$searchKey}%")

                    ->orWhere("pickup_city", "LIKE", "%{$searchKey}%")

                    ->orWhere("pickup_state", "LIKE", "%{$searchKey}%")

                    ->orWhere("pickup_address", "LIKE", "%{$searchKey}%")

                    ->orWhere("consignee_name", "LIKE", "%{$searchKey}%")

                    ->orWhere("consignee_mobile", "LIKE", "%{$searchKey}%")

                    ->orWhere("consignee_email", "LIKE", "%{$searchKey}%")

                    ->orWhere("consignee_pincode", "LIKE", "%{$searchKey}%")

                    ->orWhere("consignee_city", "LIKE", "%{$searchKey}%")

                    ->orWhere("consignee_state", "LIKE", "%{$searchKey}%")

                    ->orWhere("consignee_address", "LIKE", "%{$searchKey}%")

                    ->orWhere("barcode_no", "LIKE", "%{$searchKey}%");
            });

            if (!empty($fromdate) || !empty($todate)) {
                if (!empty($fromdate) && !empty($todate)) {
                    $fromdate = Carbon::createFromFormat("d-m-Y", $fromdate)
                        ->startOfDay()
                        ->toDateString();
                    $todate = Carbon::createFromFormat("d-m-Y", $todate)
                        ->startOfDay()
                        ->toDateString();
                    $query->whereBetween("cancel_date", [$fromdate, $todate]);
                } elseif (!empty($fromdate)) {
                    // सिर्फ fromdate मिला
                    $fromdate = Carbon::createFromFormat(
                        "d-m-Y",
                        $fromdate
                    )->toDateString();
                    $query->whereDate("cancel_date", $fromdate);
                } elseif (!empty($todate)) {
                    // सिर्फ todate मिला
                    $todate = Carbon::createFromFormat(
                        "d-m-Y",
                        $todate
                    )->toDateString();
                    $query->whereDate("cancel_date", $todate);
                }
            }
            $parcels = $query->get();
            return $this->getExcel($request, $parcels);
        }

        if (!empty($fromdate) || !empty($todate)) {
            if (!empty($fromdate) && !empty($todate)) {
                $fromdate = Carbon::createFromFormat("d-m-Y", $fromdate)
                    ->startOfDay()
                    ->toDateString();
                $todate = Carbon::createFromFormat("d-m-Y", $todate)
                    ->startOfDay()
                    ->toDateString();
                $query->whereBetween("cancel_date", [$fromdate, $todate]);
            } elseif (!empty($fromdate)) {
                // सिर्फ fromdate मिला
                $fromdate = Carbon::createFromFormat(
                    "d-m-Y",
                    $fromdate
                )->toDateString();
                $query->whereDate("cancel_date", $fromdate);
            } elseif (!empty($todate)) {
                // सिर्फ todate मिला
                $todate = Carbon::createFromFormat(
                    "d-m-Y",
                    $todate
                )->toDateString();
                $query->whereDate("cancel_date", $todate);
            }
            $parcels = $query->get();
            return $this->getExcel($request, $parcels);
        }

        $query->whereDate("cancel_date", Carbon::today());
        $parcels = $query->get();
        return $this->getExcel($request, $parcels);
    }

    public function excel_import_index(){
         $data['data'] =  Student::all();
        return view('franchise.indiaPost-speedPost.excel_import',$data);
    }

// public function excel_import_store(Request $req)
// {
//     // Check if the file is uploaded
//     if ($req->hasFile('excel_file')) {

//         $file = $req->file('excel_file');
//         $filename = time() . '_' . $file->getClientOriginalName();
//         $filePath = $file->storeAs('uploads', $filename, 'public'); // stores in storage/app/public/uploads

//         // Load the Excel file using PhpSpreadsheet
//         $spreadsheet = IOFactory::load(storage_path('app/public/' . $filePath));
//         $worksheet = $spreadsheet->getActiveSheet();
//         $rows = $worksheet->toArray();

//         // Skip the header row if present
//         foreach ($rows as $index => $row) {
//             if ($index == 0) continue; // skip header

//             $roll   = $row[0] ?? null;
//             $name   = $row[1] ?? null;
//             $email  = $row[2] ?? null;
//             $mobile = $row[3] ?? null;

//             // Insert into database using Eloquent
//             Student::create([
//                 'roll_no' => $roll,
//                 'name'    => $name,
//                 'email'   => $email,
//                 'mobile'  => $mobile,
//             ]);
//         }

//         return view('franchise.indiaPost-speedPost.excel_import')->with('success', 'Excel imported successfully!');
//     }

//     return back()->with('error', 'No file uploaded!');
// }

public function excel_import_store(Request $req)
{
    if (!$req->hasFile('excel_file')) {
        return back()->with('error', 'No file uploaded!');
    }

    $file = $req->file('excel_file');
    $filename = time() . '_' . $file->getClientOriginalName();
    $filePath = $file->storeAs('uploads', $filename, 'public');

    // Load the Excel file
    $spreadsheet = IOFactory::load(storage_path('app/public/' . $filePath));
    $worksheet = $spreadsheet->getActiveSheet();
    $rows = $worksheet->toArray();

    // Loop through each row, skipping header
    foreach ($rows as $index => $element) {
        if ($index === 0) continue; // skip header

        // Example: fetch related data if needed
        $franchiseId = Auth::user()->franchise_id ?? null;
        $franchise_role_user_id = Auth::id();

        // Example placeholders – replace with real calculations or lookups
        $customers = null; // Replace with actual customer lookup
        $customer = null;  // Replace with actual NoRegisterCustomer lookup
        $managertype = 'prepaid'; // Example
        $bookingType = 'online'; // Example
        $fuel_charge = 0;
        $pickup_charge = 0;
        $other_service_charge = 0;
        $payment_amount = 0;
        $totalAmount = ["total" => 0];
        $code = 'null'; // Generate barcode if needed
        $barcode = 'null'; // Path to barcode image

        // Create parcel record
        $parcel = IndiaPostSpeedPostParcel::create([
            "franchise_id" => $franchiseId,
            "franchise_role_users_id" => $franchise_role_user_id,
            "cod_customer_id" => $customers->id ?? null,
            "no_r_customer_id" => $customer->id ?? null,
            "booking_type" => $managertype,
            "pickup_name" => $element[0] ?? null, // Adjust indexes according to your Excel
            "pickup_mobile" => $element[1] ?? null,
            "pickup_email" => $element[2] ?? null,
            "pickup_pincode" => $element[3] ?? null,
            "pickup_city" => $element[4] ?? null,
            "pickup_state" => $element[5] ?? null,
            "pickup_address" => $element[6] ?? null,
            "consignee_name" => $element[7] ?? null,
            "consignee_mobile" => $element[8] ?? null,
            "consignee_email" => $element[9] ?? null,
            "consignee_pincode" => $element[10] ?? null,
            "consignee_city" => $element[11] ?? null,
            "consignee_state" => $element[12] ?? null,
            "consignee_address" => $element[13] ?? null,
            "package_weight" => $element[14] ?? null,
            "package_length" => $element[15] ?? null,
            "package_width" => $element[16] ?? null,
            "package_height" => $element[17] ?? null,
            "payment_method" => $bookingType,
            "cod_amount" => $element[18] ?? 0,
            "fuel_charge" => $fuel_charge,
            "pickup_charge" => $pickup_charge,
            "other_service_charge" => $other_service_charge,
            "payment_amount" => $payment_amount,
            "totalOtherAmount" => $totalAmount["total"],
            "barcode_no" => $code,
            "barcode_image_src" => $barcode,
            "insert_type" => IndiaPostSpeedPostParcel::INSERT_TYPE_BULK,
        ]);
    }

    return view('franchise.indiaPost-speedPost.excel_import')->with('success', 'Excel imported successfully!');
}


}
