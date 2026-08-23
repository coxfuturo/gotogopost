<?php

namespace App\Http\Controllers\api\delivered;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\GotogoSpeedPostParcel;
use App\Models\DeliveryBoy;
use App\Models\GotogoBusinessParcel;
use App\Models\GotogoRegisteredParcel;
use Illuminate\Support\Facades\Auth;
use App\Models\FranchiseBarcodeSeries;
use Picqer\Barcode\BarcodeGeneratorPNG;
use App\Models\FranchiseBarcodes;
use Illuminate\Support\Facades\View;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use  App\Http\Controllers\franchise\RateCalculator;
use App\Models\PickupDetails;
use App\Models\Franchise;
use App\Models\GotogoLink;
use App\Models\GotogoSpeedPostTrackOrder;
use App\Mail\ParcelMail;
use Illuminate\Support\Facades\Mail;
use App\Notifications\FranchisePushNotification;
use App\Models\FranchiseCommissionDetail;
use App\Models\DeliveryBoyCommissionDetail;
use Illuminate\Support\Facades\File;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\StreamedResponse;
use GuzzleHttp\Client;
use PDF;
use App\Models\FranchiseNotification;
use App\Models\UserNotification;
use App\Models\FranchiseBag;
use App\Models\User;
use App\Models\IndiaPostBarcode;
use App\Notifications\SMSNotification;

use DB;


class DeliveredGotogoSpeedPostController extends Controller
{



    public function index(Request $request)
    {
        $userGeneratedId = Auth::guard('apidelboy')->user()->generated_id;
        $parentFranchiseId = Auth::guard('apidelboy')->user()->franchise_id;

        $query = GotogoSpeedPostParcel::whereHas('roleUser', function ($query) use ($userGeneratedId, $parentFranchiseId) {
            $query->where('email', $userGeneratedId)
                ->where('franchise_id', $parentFranchiseId);
        });

        $query->orderBy('created_at', 'desc');

        $datas = $query->get();

        if ($datas->isEmpty()) {
            return response()->json([
                'success' => false,
                'data' => $datas,
                'message' => 'Empty data!',
            ], 400);
        }

        return response()->json([
            'success' => true,
            'data' => $datas,
            'message' => 'Data retrieved successfully 1',
        ], 200);
    }


    public function businessparcel(Request $request)

    {


        $userGeneratedId = Auth::guard('apidelboy')->user()->generated_id;
        $parentFranchiseId = Auth::guard('apidelboy')->user()->franchise_id;

        $query = GotogoBusinessParcel::whereHas('roleUser', function ($query) use ($userGeneratedId, $parentFranchiseId) {
            $query->where('email', $userGeneratedId);
            $query->where('franchise_id', $parentFranchiseId);
        });


        $query->orderBy('created_at', 'desc');


        $datas = $query->get();

        if ($datas->isEmpty()) {
            return response()->json([
                'success' => false,
                'data' => $datas,
                'message' => 'Empty data!',
            ], 400);
        }

        return response()->json([
            'success' => true,
            'data' => $datas,
            'message' => 'Data retrieved successfully 2',
        ], 200);
    }



    public function goregistered(Request $request)
    {


        $userGeneratedId = Auth::guard('apidelboy')->user()->generated_id;
        $parentFranchiseId = Auth::guard('apidelboy')->user()->franchise_id;

        $query = GotogoRegisteredParcel::whereHas('roleUser', function ($query) use ($userGeneratedId, $parentFranchiseId) {
            $query->where('email', $userGeneratedId);
            $query->where('franchise_id', $parentFranchiseId);
        });
        $query->orderBy('created_at', 'desc');

        // Add date range filtering
        $datas = $query->get();

        if ($datas->isEmpty()) {
            return response()->json([
                'success' => false,
                'data' => $datas,
                'message' => 'Empty data!',
            ], 400);
        }

        return response()->json([
            'success' => true,
            'data' => $datas,
            'message' => 'Data retrieved successfully 3',
        ], 200);
    }


    public function myBooking(Request $request)
    {
        $service_Types = [1, 3, 4, 5, 6];
        $delivery_boy_id = Auth::guard('apidelboy')->user()->id;

        $date = $request->input('date');
        $formattedDate = $date ? Carbon::parse($date)->format('Y-m-d') : Carbon::today()->format('Y-m-d'); // Correct format

        // Agar service_type diya gaya hai to uska data fetch kare
        if ($request->has('service_type') && !empty($request->service_type)) {
            $service_type = intval($request->service_type);
            $SERVICE_MODAL = FranchiseBag::getServiceModel($service_type);

            $query = $SERVICE_MODAL::where('pickup_boy_id', $delivery_boy_id)
                ->whereDate('created_at', $formattedDate) // Date filtering
                ->orderBy('created_at', 'desc');

            $datas = $query->get();
        } else {
            // Agar service_type nahi diya gaya to sabhi service types ka data merge kare
            $datas = collect();

            foreach ($service_Types as $service_type) {
                $SERVICE_MODAL = FranchiseBag::getServiceModel($service_type);
                $query = $SERVICE_MODAL::where('pickup_boy_id', $delivery_boy_id)
                    ->whereDate('created_at', $formattedDate) // Date filtering for all service types
                    ->orderBy('created_at', 'desc');

                $results = $query->get();
                $datas = $datas->merge($results);
            }
        }

        return response()->json([
            'success' => true,
            'data' => $datas,
            'message' => 'Data retrieved successfully',
        ], 200);
    }


    public function parcelDetails(Request $request, $id)
    {
        // Ensure service_type is provided and get the model
        $service_type = intval($request->service_type);
        if (!$service_type) {
            return response()->json([
                'success' => false,
                'message' => 'Service type is required!',
            ], 400);
        }

        $SERVICE_MODAL = FranchiseBag::getServiceModel($service_type);

        // Fetch the parcel with pickup_boy_id condition
        $parcel = $SERVICE_MODAL::where('id', $id)->find($id);

        if (!$parcel) {
            return response()->json([
                'success' => false,
                'message' => 'Parcel not found or unauthorized access!',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $parcel,
            'message' => 'Data retrieved successfully',
        ], 200);
    }


    public function getPickupCharge(Request $request, RateCalculator $rateCalculator)
    {
        $pickup_address = $request->pickup_address;
        $weight = $request->weight;
        $franchise_id = Auth::guard('apidelboy')->user()->franchise_id;
        $franchise_address = Franchise::findOrFail($franchise_id)->address;
        $pickupCharge = $rateCalculator->calculatePickupCommissionForGotogoPost($franchise_address, $pickup_address);

        return response()->json([
            'success' => true,
            'pickupCharge' => $pickupCharge,
            'message' => 'Data retrieved successfully',
        ], 200);
    }


    //============================================here all booking function start=====================

    public static function checkServiceStatus($franchise, $service_type)
    {
        $deliveryBoy = Auth::guard('apidelboy')->user();

        if (!$deliveryBoy || !$franchise) {
            return null; // Both must be logged in
        }

        $deliveryBoyStatuses = [
            GotogoSpeedPostParcel::SERVICE_TYPE_GOTO_POST_SPEED => $deliveryBoy->gotogo_speed_post,
            GotogoSpeedPostParcel::SERVICE_TYPE_GOTO_POST_BUSINESS_PARCEL => $deliveryBoy->gotogo_business_parcel,
            GotogoSpeedPostParcel::SERVICE_TYPE_GOTO_POST_REGISTERED => $deliveryBoy->gotogo_post_registered,
            GotogoSpeedPostParcel::SERVICE_TYPE_INDIA_POST_SPEED => $deliveryBoy->india_post_speed,
            GotogoSpeedPostParcel::SERVICE_TYPE_INDIA_POST_BUSINESS => $deliveryBoy->india_post_business,
            GotogoSpeedPostParcel::SERVICE_TYPE_INDIA_POST_REGISTERED => $deliveryBoy->india_post_registered,
            GotogoSpeedPostParcel::E2E => $deliveryBoy->e2e,
            GotogoSpeedPostParcel::E2H => $deliveryBoy->e2h,
        ];

        $franchiseStatuses = [
            GotogoSpeedPostParcel::SERVICE_TYPE_GOTO_POST_SPEED => $franchise->gotogo_speed_post,
            GotogoSpeedPostParcel::SERVICE_TYPE_GOTO_POST_BUSINESS_PARCEL => $franchise->gotogo_business_parcel,
            GotogoSpeedPostParcel::SERVICE_TYPE_GOTO_POST_REGISTERED => $franchise->gotogo_post_registered,
            GotogoSpeedPostParcel::SERVICE_TYPE_INDIA_POST_SPEED => $franchise->india_post_speed,
            GotogoSpeedPostParcel::SERVICE_TYPE_INDIA_POST_BUSINESS => $franchise->india_post_business,
            GotogoSpeedPostParcel::SERVICE_TYPE_INDIA_POST_REGISTERED => $franchise->india_post_registered,
            GotogoSpeedPostParcel::E2E => $franchise->e2e,
            GotogoSpeedPostParcel::E2H => $franchise->e2h,
        ];

        $deliveryBoyStatus = $deliveryBoyStatuses[$service_type] ?? null;

        $franchiseStatus = $franchiseStatuses[$service_type] ?? null;

        if ($deliveryBoyStatus == 1 && $franchiseStatus == 1) {
            return 1; // Both are active
        }

        return 0; // Either one is inactive or undefined
    }

    public function checkBarcode(Request $request)
    {
        // Ensure service_type is provided and get the model
        $service_type = intval($request->service_type);
        $barcode_no = $request->barcode_no;

        if (!$service_type) {
            return response()->json([
                'success' => false,
                'message' => 'Service type is required!',
            ], 400);
        }

        $SERVICE_MODAL = FranchiseBag::getServiceModel($service_type);

        // Check if the barcode already exists
        $parcel = $SERVICE_MODAL::where('barcode_no', $barcode_no)->first();

        if ($parcel) {
            return response()->json([
                'success' => false,
                'message' => 'This barcode already exists!',
            ], 400); // 409 Conflict status for duplicate entry
        }

        return response()->json([
            'success' => true,
            'message' => 'Barcode is available for use.',
        ], 200);
    }

    public function sendBookingNotificationToUser($parcel, $service_type)
    {
        try {
            \Log::info("sendNotificationToUser function called in api", [
                'parcel_id' => $parcel->id ?? null,
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

                $title = FranchiseBag::getServiceType($service_type);
                $body = "✅ Order Placed: Article No. {$parcel->barcode_no}\n" .
                    "📅 Date: " . date('M d, Y, h:i A', strtotime($parcel->created_at)) . "\n" .
                    "📍 From: {$parcel->pickup_address}\n" .
                    "📍 To: {$parcel->consignee_address}\n" .
                    "💰 Amount: ₹{$parcel->payment_amount}\n" .
                    "🚀 Your order is placed successfully and will be processed soon!";

                \Log::info("Preparing to send push notification", [
                    'title' => $title,
                    'body' => $body,
                    'user_id' => $user->id,
                    'parcel_id' => $parcel->id
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
                    'current_location' => $parcel->consignee_address,
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


    public function sendNotificationToFranchise($franchise, $parcel, $service_type)
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
            $title = FranchiseBag::getServiceType($service_type);
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

    public function getUniqueCode($franchiseId, $serviceType)

    {

        $randomNumber = rand(1, 9);

        $serviceTypeValue = GotogoSpeedPostParcel::getServiceTypeDB($serviceType);

        $range_start_column = "parcel_barcode_range_start_{$serviceTypeValue}";

        $range_end_column = "parcel_barcode_range_end_{$serviceTypeValue}";

        $last_code_issued_column = "last_parcel_code_issued_{$serviceTypeValue}";


        $franchiseSeriesDetails = FranchiseBarcodeSeries::where("franchise_id", $franchiseId)->first();


        if ($franchiseSeriesDetails) {

            if ($franchiseSeriesDetails->{$range_end_column} != null && $franchiseSeriesDetails->{$range_end_column} > $franchiseSeriesDetails->{$last_code_issued_column}) {

                if ($franchiseSeriesDetails->{$last_code_issued_column}) {

                    $last_parcel_code_issued = $franchiseSeriesDetails->{$last_code_issued_column};

                    $seriesNum = str_pad($last_parcel_code_issued + 1, 7, '0', STR_PAD_LEFT);
                } else {

                    $seriesNum = str_pad($franchiseSeriesDetails->{$range_start_column}, 7, '0', STR_PAD_LEFT);
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

    public function getPrice($request)
    {
        $newrequest = new Request([
            'originPincode' => $request->PickupPincode,
            'destinationPincode' => $request->ConsigneePincode,
            'packageWeight' => $request->package_weight,
            'fuel_charge' => $request->fuel_charge ?? 0,
            'pickup_charge' => $request->pickup_charge ?? 0,
            'other_service_charge' => $request->other_service_charge ?? 0,
            'service_type' => intval($request->service_type),
        ]);

        $rateCalculator = new RateCalculator();
        return $rateCalculator->calculate($newrequest);
    }


    public function checkAndProcessBalance($franchise, $service_type, $payment_amount)
    {
        if ($payment_amount == 0) {
            http_response_code(400); // ✅ Set HTTP 400
            echo json_encode([
                'status' => 400,
                "showMessage" => "1",
                'message' => 'Service Not Available',
            ]);
            exit; // 🚀 Request yahin terminate ho jayegi
        }

        if ($service_type == 1 || $service_type == 3 || $service_type == 4) {
            $gotogo_balance = $franchise->gotogo_balance;
            $credit_balance = $franchise->credit_balance;
            $total_balance = $gotogo_balance + $credit_balance;

            if ($total_balance < $payment_amount) {
                http_response_code(400); // ✅ Set HTTP 400
                echo json_encode([
                    'status' => 400,
                    "showMessage" => "1",
                    'message' => 'Balance Low',
                ]);
                exit;
            }
        } else {
            $indiapost_balance = $franchise->indiapost_balance;
            if ($indiapost_balance < $payment_amount) {
                http_response_code(400); // ✅ Set HTTP 400
                echo json_encode([
                    'status' => 400,
                    "showMessage" => "1",
                    'message' => 'Balance Low',
                ]);
                exit;
            }
        }
    }


    public function deductProcessBalance($franchise, $service_type, $payment_amount)
    {

        if ($payment_amount == 0) {
            return response()->json([
                'status' => 400,
                "showMessage" => "1",
                'message' => 'Service Not Available',
            ], 400);
        }

        if ($service_type == 1 || $service_type == 3 || $service_type == 4) {
            $gotogo_balance = $franchise->gotogo_balance;
            $credit_balance = $franchise->credit_balance;
            $total_balance = $gotogo_balance + $credit_balance;
            if (($total_balance) >= $payment_amount) {
                $deduct_from_gotogo = min($gotogo_balance, $payment_amount);
                $franchise->decrement('gotogo_balance', $deduct_from_gotogo);
                $remaining_amount = $payment_amount - $deduct_from_gotogo;
                if ($remaining_amount > 0) {
                    $franchise->decrement('credit_balance', $remaining_amount);
                }
            } else {
                return response()->json(['status' => 400, 'message' => 'Balance Low'], 400);
            }
        } else {
            $indiapost_balance = $franchise->indiapost_balance;
            $franchise->decrement('indiapost_balance', $payment_amount);
            if ($indiapost_balance < $payment_amount) {
                return response()->json([
                    'status' => 400,
                    "showMessage" => "1",
                    'message' => 'Balance Low',
                ], 400);
            }
        }
    }

    public function getBarcodeNo($franchise, $service_type)
    {
        if ($service_type == 1 || $service_type == 3 || $service_type == 4) {
            $barcode_no = $this->getUniqueCode($franchise->id, $service_type);
            if ($barcode_no == 'Barcode series end' || $barcode_no == 'Barcodes not assigned') {
                return response()->json(['status' => 400, 'message' => 'barcode series end'], 400);
            }
        } else {
            $barcode_no = $this->getNextBarcode($franchise);
        }

        return $barcode_no;
    }

    public function processFranchiseCommission($franchiseId, $service_type, $payment_amount, $payment_method, $package_weight, $rateCalculater)
    {
        if ($service_type == 1 || $service_type == 3 || $service_type == 4) {
            $franchise_commission = $rateCalculater->calculateCommissionForGotogoPost($package_weight, 'franchise', $service_type);
        } else {
            $franchise_commission = $rateCalculater->calculateCommissionForIndiaPost($package_weight, 'franchise', $service_type);
        }

        FranchiseCommissionDetail::create([
            "franchise_id" => $franchiseId,
            "service_type" => $service_type,
            "amount" => $payment_amount,
            "commission" => $franchise_commission,
            "payment_method" => $payment_method,
        ]);
    }

    public function processPickupCommission($service_type, $payment_amount, $payment_method, $pickup_charge)
    {
        $delivery_boy_commission = $pickup_charge * 0.9;
        DeliveryBoyCommissionDetail::create([
            "delivery_boy_id" => Auth::guard('apidelboy')->user()->id,
            "service_type" => $service_type,
            "amount" => $payment_amount,
            "payment_method" => $payment_method,
            "commission_type" => "pickup",
            "commission" => $delivery_boy_commission,
        ]);
    }


    public function processFranchiseBarcodeSeries($franchiseId, $barcode_no, $service_type)
    {
        if ($service_type == 1 || $service_type == 3 || $service_type == 4) {

            $serviceTypeValue = GotogoSpeedPostParcel::getServiceTypeDB($service_type);
            $range_start_column = "parcel_barcode_range_start_{$serviceTypeValue}";
            $last_code_issued_column = "last_parcel_code_issued_{$serviceTypeValue}";

            $franchiseSeriesDetails = FranchiseBarcodeSeries::where("franchise_id", $franchiseId)->first();

            if ($franchiseSeriesDetails->{$last_code_issued_column}) {
                $last_parcel_code_issued = $franchiseSeriesDetails->{$last_code_issued_column};
                $seriesNum = $last_parcel_code_issued + 1;
            } else {
                $seriesNum = $franchiseSeriesDetails->{$range_start_column};
            }

            // Update last issued barcode number
            $franchiseSeriesDetails->{$last_code_issued_column} = $seriesNum;
            $franchiseSeriesDetails->save();

            // Save new barcode record
            $franchiseBarcode = new FranchiseBarcodes();
            $franchiseBarcode->barcodes = $barcode_no;
            $franchiseBarcode->franchise_barcodeseries_id = $franchiseSeriesDetails->id;
            $franchiseBarcode->save();
        }
    }


    public function bookParcel(Request $request, RateCalculator $rateCalculater)

    {

        // Request data ko validate karne ke liye Validator::make() ka use karo
        $validator = Validator::make($request->all(), [
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
            'payment_method' => 'required|string',
        ]);

        // Agar validation fail ho gayi to errors return karo
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }


        $delivery_boy_id = Auth::guard('apidelboy')->user()->id;
        $franchiseId = Auth::guard('apidelboy')->user()->franchise_id;
        $service_type = intval($request->service_type);

        $franchise = Franchise::findOrFail($franchiseId);

        $active = $this->checkServiceStatus($franchise, $service_type);

        if (!$active) {
            return response()->json([
                'success' => false,
                'message' => 'Service Not Available',
                'showMessage' => 1,
            ], 403);
        }

        if ($request->PickupGstNo) {
            $franchise_gst = $request->PickupGstNo;
        } else {
            $franchise_gst = $franchise->gst_number;
        }


        $linkDetail = GotogoLink::where('franchise_no', $franchise->franchise_no)->first();

        $payment_amount = $request->payment_amount;
        $pickup_charge = $request->pickup_charge;

        $this->checkAndProcessBalance($franchise, $service_type, $payment_amount);

        if ($request->barcode_no) {
            $ServiceModal = FranchiseBag::getServiceModel($service_type);
            $exists = $ServiceModal::where("barcode_no", $request->barcode_no)->exists(); // Check if exists
            if ($exists) {
                return response()->json(["success" => false, 'message' => 'Barcode Already Exists', "showMessage" => 1], 409); // 409 Conflict
            }
            $barcode_no = $request->barcode_no;
        } else {
            $barcode_no = $this->getBarcodeNo($franchise, $service_type);
        }

        $generator = new BarcodeGeneratorPNG();
        $barcode_image_src = $generator->getBarcode($barcode_no, $generator::TYPE_CODE_128);
        $barcode_image_src = base64_encode($barcode_image_src);

        $SERVICE_MODAL = FranchiseBag::getServiceModel($service_type);

        $TRACKING_MODAL = FranchiseBag::getTrackingModel($service_type);

        DB::beginTransaction(); // ✅ Transaction Start
        try {


            try {
                $parcel = $SERVICE_MODAL::create([
                    'franchise_id' => $franchiseId,
                    'pickup_boy_id' => $delivery_boy_id,
                    'pickup_name' => $request->PickupName,
                    'pickup_mobile' => $request->PickupMobile,
                    'pickup_gst_number' => $franchise_gst,
                    'pickup_email' => $request->PickupEmail,
                    'pickup_pincode' => $request->PickupPincode,
                    'pickup_city' => $request->PickupCity,
                    'pickup_state' => $request->PickupState,
                    'pickup_address' => $request->PickupAddress,
                    'consignee_name' => $request->ConsigneeName,
                    'consignee_mobile' => $request->ConsigneeMobile,
                    'consignee_email' => $request->ConsigneeEmail,
                    'consignee_gst_number' => $request->ConsigneeGstNo,
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
                    'payment_method' => $request->payment_method,
                    'barcode_no' =>  $barcode_no,
                    'barcode_image_src' =>  $barcode_image_src,
                    'insert_type' =>  GotogoSpeedPostParcel::INSERT_TYPE_APP,
                ]);


                // Tracking data insert
                $TRACKING_MODAL::create([
                    'parcel_id' => $parcel->id,
                    'barcode_no' => $barcode_no,
                    'source_franchise_id' => $franchiseId,
                    'order_placed_datetime' => now(),
                    'source_franchise_location' => $franchise->address,
                ]);
            } catch (\Exception $e) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error: ' . $e->getMessage()
                ], 500);
            }

            PickupDetails::where(['id' => $request->pickup_id])->update(['status' => 2]);

            $this->deductProcessBalance($franchise, $service_type, $payment_amount);
            $this->processFranchiseCommission($franchiseId, $service_type, $payment_amount, $request->payment_method, $request->package_weight, $rateCalculater);
            $this->processPickupCommission($service_type, $payment_amount, $request->payment_method, $pickup_charge);
            $this->processFranchiseBarcodeSeries($franchiseId, $barcode_no, $service_type);

            DB::commit(); // ✅ Transaction Commit
            // // ======================================
            // $savedPdfFilePath = $this->savePdf($parcel);
            // Mail::to($request->PickupEmail)->send(new ParcelMail([$savedPdfFilePath, $parcel]));
            // $this->sendBookingNotificationToUser($parcel, $service_type);
            // // $this->sendNotificationToFranchise($franchise, $parcel, $service_type);

            // $title = FranchiseBag::getServiceType($service_type);
            // $notification = new SMSNotification($request->PickupMobile, 'ORDER', [$barcode_no, $title, now()]);
            // $notification->sendMessage();
            // $notification = new SMSNotification($request->ConsigneeMobile, 'ORDER', [$barcode_no, $title, now()]);
            // $notification->sendMessage();
            // // ======================================


            // Try sending mail
            try {
                $savedPdfFilePath = $this->savePdf($parcel);
                Mail::to($request->PickupEmail)->send(new ParcelMail([$savedPdfFilePath, $parcel]));
            } catch (\Exception $e) {
                \Log::error('Mail sending failed: ' . $e->getMessage());
            }

            // Try sending user notification
            try {
                $this->sendBookingNotificationToUser($parcel, $service_type);
            } catch (\Exception $e) {
                \Log::error('User notification failed: ' . $e->getMessage());
            }

            // Try sending SMS
            try {
                $title = FranchiseBag::getServiceType($service_type);

                $notification = new SMSNotification($request->PickupMobile, 'ORDER', [$barcode_no, $title, now()]);
                $notification->sendMessage();

                $notification = new SMSNotification($request->ConsigneeMobile, 'ORDER', [$barcode_no, $title, now()]);
                $notification->sendMessage();
            } catch (\Exception $e) {
                \Log::error('SMS sending failed: ' . $e->getMessage());
            }

            return response()->json(['status' => 200, 'message' => 'new parcel added', 'data' => $parcel, 'linkDetail' => $linkDetail]);
        } catch (\Exception $th) {
            DB::rollBack(); // ❌ Transaction Rollback on Error
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong!',
                'error' => $th->getMessage()
            ], 500); // 500 for server error
        }
    }

    //============================================here all booking function end=====================

    public function updateSignature(Request $request)
    {
        $service_type = $request->service_type;
        $id = $request->id;

        $Model = FranchiseBag::getServiceModel($service_type);
        $data = $Model::find($id);

        if ($request->hasFile('signature')) {
            $file = $request->file('signature');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $path = public_path('tenancy/assets/signature/');
            $file->move($path, $fileName);

            $data->signature = $fileName;
            $data->save();

            return response()->json([
                'status' => true,
                'message' => 'Signature Updated Successfully',
                'image_url' => asset('tenancy/assets/signature/' . $fileName),
            ], 200);
        }

        return response()->json([
            'status' => false,
            'message' => 'No image uploaded',
        ], 400);
    }


    public function getNextBarcode($franchise)
    {

        $user = $franchise;
        $state = $user->state;

        try {
            // Get the next available barcode based on state
            $barcode = IndiaPostBarcode::where('state', $state)
                ->where('availables', '>', 0)
                ->first();

            if ($barcode) {
                $nextBarcode = $barcode->getNextBarcode();
                // Increment range_from and decrement availables
                $barcode->increment('range_from');
                $barcode->decrement('availables');

                return $nextBarcode;
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Barcode not found for this state',
                ], 404);
            }
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error generating barcode: ' . $e->getMessage(),
            ], 500);
        }
    }
}
