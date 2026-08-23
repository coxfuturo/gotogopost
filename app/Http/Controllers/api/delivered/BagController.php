<?php

namespace App\Http\Controllers\api\delivered;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\FranchiseBag;
use App\Models\PickupDetails;
use App\Http\Controllers\franchise\RateCalculator;
use App\Models\DeliveryBoyCommissionDetail;
use Carbon\Carbon;
use App\Mail\SendOtpMail;
use Illuminate\Support\Facades\Mail as MailFacade;
use Illuminate\Support\Facades\Config;
use App\Models\Mail;
use App\Models\Franchise;
use App\Models\UserPayment;
use App\Models\User;
use App\Models\ReturnedParcel;
use App\Models\GotogoSpeedPostParcel;
use App\Models\DeliveryBoyBagParcel;
use App\Notifications\SMSNotification;
use Illuminate\Support\Facades\Validator;
use Razorpay\Api\Api;
use DB;
use App\Mail\deliveredMail;

use App\Notifications\FranchisePushNotification;

class BagController extends Controller
{

   
    public function showRecievedBags(Request $request)
    {

        $date = $request->input('date');
        $formattedDate = $date ? Carbon::parse($date)->format('Y-m-d') : Carbon::today()->format('Y-m-d');
        $bagfromfranchise = FranchiseBag::where('delivery_boy_id', Auth::guard('apidelboy')->user()->id)
            ->whereDate('created_at', $formattedDate)
            ->orderBy('created_at', 'desc')
            ->with('franchise', 'deliveryBoy')
            ->get();

        if ($bagfromfranchise->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'No data found',
            ], 400);
        }

        $title = "Bag Details";
        return response()->json([
            'success' => true,
            'bagfromfranchise' => $bagfromfranchise,
            'title' => $title,
            'message' => 'Data retrieved successfully',
        ], 200);
    }


    public function viewParcels_of_receivedBag_from_franchise(Request $request, $id)
    {
        // Get Bag Parcel Record
        $bagParcel = DeliveryBoyBagParcel::where('bag_id', $id)->first();

        // Initialize Data
        $datas = [];
        $bagDetails = null;
        $title = "Bag Details";

        if ($bagParcel) {
            // Decode JSON Parcel Data
            $parcels = json_decode($bagParcel->parcels, true) ?? [];

            // Filter only required service types
            $validServiceTypes = [1, 3, 4, 5, 6];

            // Group parcel IDs by service type
            $parcelGroups = [];

            foreach ($parcels as $parcel) {
                $serviceType = $parcel['service_type'];
                $parcelId = $parcel['parcel_id'];

                if (in_array($serviceType, $validServiceTypes)) {
                    $parcelGroups[$serviceType][] = $parcelId;
                }
            }

            // Fetch all parcels in batch
            foreach ($parcelGroups as $serviceType => $parcelIds) {
                $Model = FranchiseBag::getServiceModel($serviceType);

                if ($Model) {
                    // Fetch data as collection (not array)
                    $records = $Model::whereIn('id', $parcelIds)->get();

                    // Ensure each record is converted to an object
                    foreach ($records as $record) {
                        $datas[] = (object) $record;
                    }
                }
            }
        }

        $bagDetails = FranchiseBag::where('id', $id)->select('bag_id')->first();

        return response()->json([
            'success' => true,
            'datas' => $datas,
            'bagId' => $id,
            'service_type' => 1,
            'bagDetails' => $bagDetails,
            'title' => $title,
            'message' => 'Data retrieved successfully',
        ], 200);
    }


    public function Detailsview($id, $service_type)

    {
        $Model = FranchiseBag::getServiceModel($service_type);
        $data = $Model::find($id);

        if ($data == null) {
            return response()->json([
                'success' => false,
                'message' => 'Empty data!',
            ], 400);
        }

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



        return response()->json([
            'success' => true,
            'data' => $data,
            'rateDetails' => $rateDetails,
            'message' => 'Data retrieved successfully',
        ], 200);
    }


    public function showAllParcelToDeliver(Request $request)
    {

        $allData = collect();

        $user = Auth::guard('apidelboy')->user();

        $delivery_boy_id = $user->id;

        // Step 1: Get all bags assigned to the delivery boy
        $bagIds = FranchiseBag::where('delivery_boy_id', $delivery_boy_id)
            ->pluck('id')
            ->toArray();


        if (empty($bagIds)) {
            return response()->json([], 200);
        }

        // Step 2: Fetch all bag parcel records at once
        $bagParcels = DeliveryBoyBagParcel::whereIn('bag_id', $bagIds)->get();


        $validServiceTypes = [1, 3, 4, 5, 6];
        $parcelGroups = [];

        // Step 3: Process all parcels in memory
        foreach ($bagParcels as $bagParcel) {
            $parcels = json_decode($bagParcel->parcels, true) ?? [];

            foreach ($parcels as $parcel) {
                $serviceType = $parcel['service_type'];
                $parcelId = $parcel['parcel_id'];

                if (in_array($serviceType, $validServiceTypes)) {
                    $parcelGroups[$serviceType][] = $parcelId;
                }
            }
        }

        $returnedParcels = collect();

        // Step 1: Fetch all returned parcels based on service_type and parcel_id
        foreach ($parcelGroups as $serviceType => $parcelIds) {
            $parcels = ReturnedParcel::where('service_type', $serviceType)
                ->whereIn('parcel_id', $parcelIds)
                ->get()
                ->keyBy(fn($parcel) => $serviceType . '_' . $parcel->parcel_id); // Unique key for lookup

            $returnedParcels = $returnedParcels->merge($parcels);
        }


        // Step 2: Fetch records from respective models and set return_type
        foreach ($parcelGroups as $serviceType => $parcelIds) {
            $Model = FranchiseBag::getServiceModel($serviceType);

            if ($Model) {
                $records = $Model::whereIn('id', $parcelIds)
                    ->whereNull('delivered_date')
                    ->with('cancelDelivery') // Ensure cancelDelivery is loaded
                    ->get();

                foreach ($records as $record) {
                    $item = (object) $record; // Convert to object before modifying

                    // Check if the record exists in returnedParcels
                    $key = $serviceType . '_' . $record->id;
                    $item->return_type = isset($returnedParcels[$key]) ? 1 : 0;

                    $item->service_type = $serviceType;
                    $allData->push($item);
                }
            }
        }

        $filteredData = collect();


        foreach ($allData as $item) {
            if (!empty($item->cancelDelivery)) {
                $isCancelledByDeliveryBoy = collect($item->cancelDelivery)->contains(function ($cancel) use ($item, $delivery_boy_id) {
                    if (is_array($cancel)) {
                        return $cancel['parcel_id'] == $item->id && $cancel['cancelled_by'] == $delivery_boy_id;
                    } elseif (is_object($cancel)) {
                        return $cancel->parcel_id == $item->id && $cancel->cancelled_by == $delivery_boy_id;
                    }

                    return false;
                });

                if ($isCancelledByDeliveryBoy) {
                    continue; // Skip this item
                }
            }

            // Push to filtered data if not cancelled by the delivery boy
            $filteredData->push($item);
        }

        return response()->json([
            'success' => true,
            'data' => $filteredData->values(),
            'count' => $filteredData->count(),
            'message' => 'Data retrieved successfully',
        ], 200);
    }


    public function sendDeliveryNotificationToUser($parcel, $service_type)
    {
        try {
            \Log::info("sendNotificationToUser function called", [
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
                $body = "✅ Order Update: Article No. {$parcel->barcode_no}\n" .
                    "📅 Date: " . now()->format('M d, Y, h:i A') . "\n" .
                    "📍 Location: {$parcel->consignee_address}\n" .
                    "🚀 Order has Delivered succefully to  {$parcel->consignee_name} in {$parcel->consignee_address}";


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

    public function sendOtp($barcode_no, $service_type)
    {
        try {
            $Model = FranchiseBag::getServiceModel($service_type);
            $parcelToUpdate = $Model::where('barcode_no', $barcode_no)->first();
            $consignee_email = $parcelToUpdate->consignee_email;
            $consignee_mobile = $parcelToUpdate->consignee_mobile;
            if ($parcelToUpdate) {
                $parcelToUpdate->otp = mt_rand(1111, 9999);
                $parcelToUpdate->save();

                MailFacade::to($consignee_email)->send(new SendOtpMail([
                    'otp' =>  $parcelToUpdate->otp,
                ]));


                $notification = new SMSNotification($consignee_mobile, 'OTP', [$parcelToUpdate->otp]);
                $response = $notification->sendMessage();
                return response()->json([
                    'success' => true,
                    'data' => $parcelToUpdate->otp,
                    'message' => 'OTP sent successfully!! otp',
                ], 200);
            } else {
                // return back()->with('error', 'Parcel not found.')->withInput();
                return response()->json([
                    'success' => false,
                    'message' => 'Parcel not found',
                ], 401)->withInput();
            }
        } catch (\Exception $th) {
            return response()->json([
                'success' => false,
                'error' => $th->getMessage(),
            ], 400);
        }
    }

    public function verifyOtp(Request $request, $service_type, RateCalculator $rateCalculator)
    {

        $title = FranchiseBag::getServiceType($service_type);


        $delivery_boy =  Auth::guard('apidelboy')->user();

        if ($request->isMethod('POST')) {
            try {
                $Model = FranchiseBag::getServiceModel($service_type);
                $parcelToUpdate = $Model::where('barcode_no', $request->barcode_no)->first();
                if ($parcelToUpdate) {
                    $submittedOtp = implode('', $request->input('otp', []));

                    if ($parcelToUpdate->otp === $submittedOtp) {
                        if ($parcelToUpdate->delivered_date == null) {

                            if ($service_type == 1 || $service_type == 3 || $service_type == 4) {
                                $commission = $rateCalculator->calculateCommissionForGotogoPost($parcelToUpdate->package_weight, 'delivery', $service_type);
                            }

                            if ($service_type == 5 || $service_type == 6 || $service_type == 7) {
                                $commission = $rateCalculator->calculateCommissionForIndiaPost($parcelToUpdate->package_weight, 'delivery', $service_type);
                            }

                            DeliveryBoyCommissionDetail::create([
                                "delivery_boy_id" => $delivery_boy->id,
                                "service_type" => $service_type,
                                "amount" => $parcelToUpdate->payment_amount,
                                "payment_method" => $parcelToUpdate->payment_method,
                                "commission_type" => "delivery",
                                "commission" => $commission,
                            ]);
                        }

                        $TrackingModel = FranchiseBag::getTrackingModel($service_type);
                        $trackingModalToUpdate =  $TrackingModel::where('barcode_no', $request->barcode_no)->first();
                        $trackingModalToUpdate->delivery_datetime = now();
                        $trackingModalToUpdate->delivery_location = $parcelToUpdate->consignee_address;
                        $trackingModalToUpdate->save();
                        $parcelToUpdate->delivered_date = Carbon::today()->toDateString();
                        $parcelToUpdate->delivered_by = $delivery_boy->id;
                        $parcelToUpdate->save();

                        MailFacade::to($parcelToUpdate->pickup_email)->send(new deliveredMail([$parcelToUpdate]));
                        $this->sendDeliveryNotificationToUser($parcelToUpdate, $service_type);

                        return response()->json([
                            'success' => true,
                            'message' => 'Verified successfully!',
                        ], 200);
                    } else {

                        return response()->json([
                            'success' => false,
                            'message' => 'Invalid OTP. Please try again',
                        ], 400)->withInput();
                    }
                } else {

                    return response()->json([
                        'success' => false,
                        'message' => 'Parcel not found',
                    ], 401)->withInput();
                }
            } catch (\Exception $th) {

                return response()->json([
                    'success' => false,
                    'error' => $th->getMessage(),
                ], 400);
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'otp verified',
        ], 200);
    }


    public function cancelDelivery(Request $request, $id, $service_type, RateCalculator $rateCalculator)
    {
        $Model = FranchiseBag::getServiceModel($service_type);
        $record = $Model::findOrFail($id);

        $delivery_boy = Auth::guard('apidelboy')->user();

        if ($request->isMethod('POST')) {
            try {

                if ($record->otp === null || strlen($record->otp) == 4) {

                    $record->cancelDelivery()->create([
                        'cancel_reason' => $request->reason,
                        'cancelled_by' => $delivery_boy->id,
                    ]);

                    $commission = 0;
                    $commission = $rateCalculator->calculateCommissionForGotogoPost($record->package_weight, 'delivery', $request->service_type);
                    DeliveryBoyCommissionDetail::create([
                        "delivery_boy_id" => $delivery_boy->id,
                        "service_type" => $service_type,
                        "amount" => $record->payment_amount,
                        "payment_method" => $record->payment_method,
                        "commission_type" => "delivery",
                        "commission" => $commission,
                    ]);

                    $record->otp = "0";
                    $record->save();
                } else {
                    if ($record->otp == '0') {

                        $record->cancelDelivery()->create([
                            'cancel_reason' => $request->reason,
                            'cancelled_by' => $delivery_boy->id,
                        ]);


                        $record->otp = "00";
                        $record->save();
                    }
                }

                return response()->json([
                    'success' => true,
                    'message' => 'Parcel Canceled successfully!',
                ], 200);
            } catch (\Exception $th) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid OTP. Please try again',
                    'error' => $th->getMessage(),
                ], 400)->withInput();
            }
        }
    }

    public function showAllDeliveredParcel(Request $request)
    {
        $service_type = $request->service_type;
        $date = $request->input('date');
        $formattedDate = $date ? Carbon::parse($date)->format('y-m-d') : Carbon::today()->format('y-m-d');

        $allData = $this->showAllDeliveredParcelHelper($service_type, $formattedDate);

        return response()->json([
            'success' => true,
            'data' => array_values($allData), // Ensure clean indexed array
            'message' => 'Data retrieved successfully',
        ], 200);
    }

    public function showAllDeliveredParcelHelper($service_type, $formattedDate)
    {
        $user = Auth::guard('apidelboy')->user();
        if (!$user) {
            return [];
        }

        $delivery_boy_id = $user->id;

        // Step 1: Get all bags assigned to the delivery boy
        $bagIds = FranchiseBag::where('delivery_boy_id', $delivery_boy_id)->pluck('id')->toArray();
        if (empty($bagIds)) {
            return [];
        }

        // Step 2: Fetch all bag parcel records at once
        $bagParcels = DeliveryBoyBagParcel::whereIn('bag_id', $bagIds)->get();
        $validServiceTypes = [1, 3, 4, 5, 6];

        $parcelGroups = [];

        // Step 3: Process all parcels in memory
        foreach ($bagParcels as $bagParcel) {
            $parcels = json_decode($bagParcel->parcels, true) ?? [];
            foreach ($parcels as $parcel) {
                if (in_array($parcel['service_type'], $validServiceTypes)) {
                    $parcelGroups[$parcel['service_type']][] = $parcel['parcel_id'];
                }
            }
        }

        if (empty($parcelGroups)) {
            return [];
        }

        // Step 4: Pre-fetch ReturnedParcels for lookup
        $returnedParcels = ReturnedParcel::whereIn('service_type', array_keys($parcelGroups))
            ->whereIn('parcel_id', collect($parcelGroups)->flatten()->toArray())
            ->get()
            ->keyBy(fn($parcel) => "{$parcel->service_type}_{$parcel->parcel_id}");

        $allData = [];


        foreach ($parcelGroups as $serviceType => $parcelIds) {
            $Model = FranchiseBag::getServiceModel($serviceType);
            if (!$Model) {
                continue;
            }

            // Fetch delivered parcels
            $deliveredRecords = $Model::whereIn('id', $parcelIds)
                ->whereDate('delivered_date', $formattedDate)
                ->where('delivered_by', $delivery_boy_id)
                ->get();

            // Fetch canceled parcels
            $canceledRecords = $Model::whereIn('id', $parcelIds)
                ->whereHas('cancelDelivery', function ($query) use ($formattedDate, $delivery_boy_id) {
                    $query->whereDate('created_at', $formattedDate)
                        ->where('cancelled_by', $delivery_boy_id); // Fix: 'delivered_by' changed to 'cancelled_by'
                })
                ->with('cancelDelivery')
                ->get();


            // Filter canceled records where canceled_by = delivery_boy_id
            $filteredRecords = $canceledRecords->filter(function ($record) use ($delivery_boy_id) {
                // Ensure cancelDelivery is loaded and is a collection
                if ($record->cancelDelivery instanceof \Illuminate\Support\Collection) {
                    return $record->cancelDelivery->contains(function ($cancel) use ($delivery_boy_id) {
                        return $cancel->cancelled_by == $delivery_boy_id;
                    });
                }
                return false;
            });


            $allParcels = $deliveredRecords->merge($filteredRecords);


            foreach ($allParcels as $record) {
                $returnType = isset($returnedParcels["{$serviceType}_{$record->id}"]) ? 1 : 0;

                // Only include cancelDelivery done by this delivery boy
                $cancelDelivery = !empty($record->cancelDelivery)
                    ? collect($record->cancelDelivery)
                    ->filter(function ($cancel) use ($delivery_boy_id) {
                        return $cancel->cancelled_by == $delivery_boy_id;
                    })
                    ->map(function ($cancel) {
                        return [
                            'id' => $cancel->id,
                            'parcel_type' => $cancel->parcel_type,
                            'cancel_reason' => $cancel->cancel_reason,
                            'cancelled_by' => $cancel->cancelled_by,
                            'parcel_id' => $cancel->parcel_id,
                            'created_at' => $cancel->created_at,
                            'updated_at' => $cancel->updated_at,
                        ];
                    })
                    ->first()
                    : (object) [];

                $allData[] = [
                    'id' => $record->id,
                    'pickup_name' => $record->pickup_name,
                    'pickup_mobile' => $record->pickup_mobile,
                    'pickup_pincode' => $record->pickup_pincode,
                    'pickup_city' => $record->pickup_city,
                    'pickup_state' => $record->pickup_state,
                    'pickup_address' => $record->pickup_address,
                    'consignee_name' => $record->consignee_name,
                    'consignee_mobile' => $record->consignee_mobile,
                    'consignee_pincode' => $record->consignee_pincode,
                    'consignee_city' => $record->consignee_city,
                    'consignee_state' => $record->consignee_state,
                    'consignee_address' => $record->consignee_address,
                    'package_weight' => $record->package_weight,
                    'payment_method' => $record->payment_method,
                    'payment_amount' => $record->payment_amount,
                    'barcode_no' => $record->barcode_no,
                    'barcode_image_src' => $record->barcode_image_src,
                    'delivered_date' => $record->delivered_date ? Carbon::parse($record->delivered_date)->format('d-m-Y') : null,
                    'pickup_date' => $record->created_at ? Carbon::parse($record->created_at)->format('d-m-Y') : null,
                    'signature' => $record->signature,
                    'service_type' => GotogoSpeedPostParcel::getServiceType($serviceType),
                    'return_type' => $returnType,
                    'cancelDelivery' => $cancelDelivery,
                ];
            }
        }

        // Filter by service_type if provided
        return isset($service_type) ? array_filter($allData, fn($item) => $item['service_type'] == $service_type) : $allData;
    }

    public function history(Request $request)
    {
        $service_type = $request->service_type;
        $date = $request->input('date');
        $formattedDate = $date ? Carbon::parse($date)->format('y-m-d') : Carbon::today()->format('y-m-d');

        $allData = collect();

        $allData = $allData->merge($this->historyHelper($service_type, $formattedDate));

        // Ensure proper JSON response format
        if ($allData->isEmpty()) {
            return response()->json([
                'success' => true,
                'data' => [],
                'message' => 'No data found',
            ], 200);
        }

        return response()->json([
            'success' => true,
            'data' => $allData->values(),
            'count' => $allData->count(),
            'message' => 'Data retrieved successfully',
        ], 200);
    }


    public function historyHelper($service_type, $formattedDate)
    {
        $user = Auth::guard('apidelboy')->user();
        if (!$user) {
            return [];
        }

        $delivery_boy_id = $user->id;

        // Step 1: Get all bags assigned to the delivery boy
        $bagIds = FranchiseBag::where('delivery_boy_id', $delivery_boy_id)->pluck('id')->toArray();
        if (empty($bagIds)) {
            return [];
        }

        // Step 2: Fetch all bag parcel records at once
        $bagParcels = DeliveryBoyBagParcel::whereIn('bag_id', $bagIds)->get();
        $validServiceTypes = [1, 3, 4, 5, 6];

        $parcelGroups = [];

        // Step 3: Process all parcels in memory
        foreach ($bagParcels as $bagParcel) {
            $parcels = json_decode($bagParcel->parcels, true) ?? [];
            foreach ($parcels as $parcel) {
                if (in_array($parcel['service_type'], $validServiceTypes)) {
                    $parcelGroups[$parcel['service_type']][] = $parcel['parcel_id'];
                }
            }
        }

        if (empty($parcelGroups)) {
            return [];
        }

        // Step 4: Pre-fetch ReturnedParcels for lookup
        $returnedParcels = ReturnedParcel::whereIn('service_type', array_keys($parcelGroups))
            ->whereIn('parcel_id', collect($parcelGroups)->flatten()->toArray())
            ->get()
            ->keyBy(fn($parcel) => "{$parcel->service_type}_{$parcel->parcel_id}");

        $allData = [];


        foreach ($parcelGroups as $serviceType => $parcelIds) {
            $Model = FranchiseBag::getServiceModel($serviceType);
            if (!$Model) {
                continue;
            }

            // Fetch delivered parcels
            $deliveredRecords = $Model::whereIn('id', $parcelIds)
                ->whereDate('delivered_date', $formattedDate)
                ->where('delivered_by', $delivery_boy_id)
                ->get();

            // Fetch canceled parcels
            $canceledRecords = $Model::whereIn('id', $parcelIds)
                ->whereHas('cancelDelivery', function ($query) use ($formattedDate, $delivery_boy_id) {
                    $query->whereDate('created_at', $formattedDate)
                        ->where('cancelled_by', $delivery_boy_id); // Fix: 'delivered_by' changed to 'cancelled_by'
                })
                ->with('cancelDelivery')
                ->get();


            // Filter canceled records where canceled_by = delivery_boy_id
            $filteredRecords = $canceledRecords->filter(function ($record) use ($delivery_boy_id) {
                // Ensure cancelDelivery is loaded and is a collection
                if ($record->cancelDelivery instanceof \Illuminate\Support\Collection) {
                    return $record->cancelDelivery->contains(function ($cancel) use ($delivery_boy_id) {
                        return $cancel->cancelled_by == $delivery_boy_id;
                    });
                }
                return false;
            });


            $pickupParcels = $Model::where('pickup_boy_id', $delivery_boy_id)
                ->whereDate('created_at', '=', $formattedDate)
                ->get();


            $allParcels = $deliveredRecords->merge($filteredRecords)->merge($pickupParcels);

            foreach ($allParcels as $record) {
                $returnType = isset($returnedParcels["{$serviceType}_{$record->id}"]) ? 1 : 0;

                // Only include cancelDelivery done by this delivery boy
                $cancelDelivery = !empty($record->cancelDelivery)
                    ? collect($record->cancelDelivery)
                    ->filter(function ($cancel) use ($delivery_boy_id) {
                        return $cancel->cancelled_by == $delivery_boy_id;
                    })
                    ->map(function ($cancel) {
                        return [
                            'id' => $cancel->id,
                            'parcel_type' => $cancel->parcel_type,
                            'cancel_reason' => $cancel->cancel_reason,
                            'cancelled_by' => $cancel->cancelled_by,
                            'parcel_id' => $cancel->parcel_id,
                            'created_at' => $cancel->created_at,
                            'updated_at' => $cancel->updated_at,
                        ];
                    })
                    ->first()
                    : (object) [];

                $allData[] = [
                    'id' => $record->id,
                    'pickup_name' => $record->pickup_name,
                    'pickup_mobile' => $record->pickup_mobile,
                    'pickup_pincode' => $record->pickup_pincode,
                    'pickup_city' => $record->pickup_city,
                    'pickup_state' => $record->pickup_state,
                    'pickup_address' => $record->pickup_address,
                    'consignee_name' => $record->consignee_name,
                    'consignee_mobile' => $record->consignee_mobile,
                    'consignee_pincode' => $record->consignee_pincode,
                    'consignee_city' => $record->consignee_city,
                    'consignee_state' => $record->consignee_state,
                    'consignee_address' => $record->consignee_address,
                    'package_weight' => $record->package_weight,
                    'payment_method' => $record->payment_method,
                    'payment_amount' => $record->payment_amount,
                    'barcode_no' => $record->barcode_no,
                    'barcode_image_src' => $record->barcode_image_src,
                    'delivered_date' => $record->delivered_date ? Carbon::parse($record->delivered_date)->format('d-m-Y') : null,
                    'pickup_date' => $record->created_at ? Carbon::parse($record->created_at)->format('d-m-Y') : null,
                    'signature' => $record->signature,
                    'service_type' => GotogoSpeedPostParcel::getServiceType($serviceType),
                    'return_type' => $returnType,
                    'cancelDelivery' => $cancelDelivery,
                ];
            }
        }

        $finalData = [];

        foreach ($allData as $item) {
            $isCancelled = isset($item['cancelDelivery']) && !empty($item['cancelDelivery']);
            $isDelivered = !empty($item['delivered_date']) && empty($item['cancelDelivery']);

            if ($isCancelled) {
                $item['order_status'] = 'Cancelled';
            } elseif ($isDelivered) {
                $item['order_status'] = 'Delivered';
            } else {
                $item['order_status'] = 'Pickup';
            }

            $finalData[] = $item;
        }
        // Filter by service_type if provided
        return isset($service_type)
            ? array_filter($finalData, fn($item) => $item['service_type'] == $service_type)
            : $finalData;
    }

    public function pickupList(Request $request)
    {
        $searchKey = $request->input('searchKey');
        $fromDate = $request->input('fromDate');
        $toDate = $request->input('toDate');
        $delivery_boy_id = Auth::guard('apidelboy')->user()->id;

        // Format date values properly
        $fromDate = $fromDate ? Carbon::parse($fromDate)->startOfDay() : null;
        $toDate = $toDate ? Carbon::parse($toDate)->endOfDay() : null;

        $query = PickupDetails::where('deliveryboy_id', $delivery_boy_id)->where('status', '0')
            ->orderBy('created_at', 'desc');

        // Search key filtering
        if ($searchKey) {
            $query->where(function ($q) use ($searchKey) {
                $q->where('name', 'LIKE', "%{$searchKey}%")
                    ->orWhere('email', 'LIKE', "%{$searchKey}%")
                    ->orWhere('phone', 'LIKE', "%{$searchKey}%")
                    ->orWhere('pincode', 'LIKE', "%{$searchKey}%")
                    ->orWhere('city', 'LIKE', "%{$searchKey}%")
                    ->orWhere('state', 'LIKE', "%{$searchKey}%")
                    ->orWhere('address', 'LIKE', "%{$searchKey}%");
            });
        }

        // Date range filtering
        if ($fromDate && $toDate) {
            $query->whereBetween('created_at', [$fromDate, $toDate]);
        } elseif ($fromDate) {
            $query->whereDate('created_at', '>=', $fromDate);
        } elseif ($toDate) {
            $query->whereDate('created_at', '<=', $toDate);
        }

        // Get the filtered results with pagination
        $pickupdetails = $query->get();


        return response()->json([
            'success' => true,
            'data' =>  $pickupdetails,
            'message' => 'Data retrieved successfully',
        ], 200);
    }


    public function paymentStore(Request $request, RateCalculator $rateCalculator)
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
}
