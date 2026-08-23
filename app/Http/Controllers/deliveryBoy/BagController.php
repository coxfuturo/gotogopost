<?php



namespace App\Http\Controllers\deliveryBoy;


use App\Http\Controllers\Controller;
use App\Models\FranchiseBag;
use App\Models\DeliveryBoy;
use App\Models\Franchise;
use App\Models\DeliveryBoyCommissionDetail;
use App\Notifications\FranchisePushNotification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Http\Controllers\franchise\RateCalculator;
use App\Mail\SendOtpMail;
use Illuminate\Support\Facades\Mail as MailFacade;
use Illuminate\Support\Facades\Config;
use App\Notifications\SMSNotification;
use App\Models\PickupDetails;
use App\Models\ReturnedParcel;
use App\Models\DeliveryBoyBagParcel;
use App\Models\GotogoSpeedPostParcel;
use App\Mail\deliveredMail;



class BagController extends Controller

{

    public function __construct()
    {
        $this->middleware(function ($request, $next) {

            if ($request->service_type) {
                $serviceStatuses = DeliveryBoy::checkServiceStatus($request->service_type);

                if (!$serviceStatuses) {
                    return abort(403, 'Service not available.');
                }
            }

            return $next($request);
        });
    }


    public function showRecievedBags(Request $request)
    {
        $date = $request->input('date');
        $formattedDate = $date ? Carbon::parse($date)->format('Y-m-d') : Carbon::today()->format('Y-m-d');
        $bagfromfranchise = FranchiseBag::where('delivery_boy_id',  Auth::guard('delboy')->user()->id)

            ->whereDate('created_at', $formattedDate)

            ->orderBy('created_at', 'desc')

            ->with('franchise')

            ->get();

        $title = "Bag Details";
        return view('deliveryBoy.bag.recievedBags', ['bagfromfranchise' => $bagfromfranchise, 'bagId' => $request->service_type, 'title' => $title]);
    }


    public function receivedBagsSearch(Request $request)
    {
        $DBid = Auth::guard('delboy')->user()->id;
        $searchKey = $request->input('searchKey');
        $createdDate = $request->input('createdDate');
        $formattedDate = $createdDate ? Carbon::parse($createdDate)->format('Y-m-d') : Carbon::today()->format('Y-m-d');


        if ($searchKey) {
            $desiredBags = [];

            // Get the correct model for the service type
            $Model = FranchiseBag::getBookingModelByBarcode($searchKey);
            $serviceType = FranchiseBag::getServiceTypeFromModel($Model);

            // Find parcel by barcode
            $parselToSearch = $Model::where('barcode_no', $searchKey)
                ->select('id')
                ->first();

            if ($parselToSearch) {
                // Get bag IDs associated with the parcel
                $bagIds = DeliveryBoyBagParcel::whereJsonContains('parcels', [['parcel_id' => intval($parselToSearch->id), 'service_type' => intval($serviceType)]])
                    ->pluck('bag_id');

                // Get Franchise Bags
                $allFranchiseBag = FranchiseBag::whereIn('id', $bagIds)
                    ->where('delivery_boy_id', $DBid)
                    ->where('service_type', $serviceType)
                    ->get();
                // Ensure bags belong to the current franchise (Filtering collection)
                $allFranchiseBag = $allFranchiseBag->filter(function ($bag) use ($bagIds) {

                    return in_array($bag->id, $bagIds->toArray());
                });
                $desiredBags = $allFranchiseBag;
            }
        }

        // Base query for FranchiseBag
        $bagsQuery = FranchiseBag::where('delivery_boy_id', $DBid)
            ->with('franchise')
            ->orderBy('created_at', 'desc');


        // Apply date filter if $createdDate is provided
        $bagsQuery->when($formattedDate, function ($query) use ($formattedDate) {
            return $query->whereDate('created_at', $formattedDate);
        });


        // Determine the type of bags to query based on 'bag_type'
        $bagsQuery->whereNotNull('delivery_boy_id')
            ->with('deliveryBoy')
            ->when($searchKey, function ($query) use ($searchKey) {
                $query->where(function ($q) use ($searchKey) {
                    $q->whereHas('deliveryBoy', function ($q) use ($searchKey) {
                        $q->where('pincode', 'like', '%' . $searchKey . '%')
                            ->orWhere('city', 'like', '%' . $searchKey . '%');
                    })->orWhere('barcode_no', 'like', '%' . $searchKey . '%');
                });
            });


        if (!empty($desiredBags)) {
            $filteredBags = collect($desiredBags);
        } else {
            $filteredBags = $bagsQuery->get();
        }

        $html = '';
        foreach ($filteredBags as $bag) {
            ob_start();
?>
            <div class="col-lg-2 d-flex" style="position:relative;">
                <a href="<?= route('deliveryBoy.bag.viewParcelfranchiseReceived', ['id' => $bag->id, 'service_type' => $bag->service_type]) ?>">
                    <div class="profile-widget w-100 bagCard <?= $searchKey ? 'filteredColor' : '' ?>">
                        <div class="dash-card-icon">
                            <i class="fa-solid fa-suitcase" style="color: #666363; font-size: 30px;"></i>
                        </div>
                        <h5 class="user-name mt-1 mb-0 text-ellipsis"><a href="#">ID: <?= $bag->bag_id ?></a></h5>
                        <h6 class="user-name mt-1 mb-0 text-ellipsis"><a href="#">Fo-Code: <?= $bag->franchise->franchise_no ?></a></h6>
                        <h6 class="user-name mt-1 mb-0 text-ellipsis"><a href="#">City: <?= $bag->deliveryBoy->pincode ?></a></h6>
                    </div>
                </a>
            </div>
        <?php
            $html .= ob_get_clean();
        }

        $allotherBags = FranchiseBag::where('delivery_boy_id', $DBid)
            ->whereNotIn('id', $filteredBags->pluck('id')->toArray())
            ->orderBy('created_at', 'desc')
            ->with('franchise')
            ->whereDate('created_at', $formattedDate)
            ->get();

        foreach ($allotherBags as $bag) {
            ob_start();
        ?>
            <div class="col-lg-2 d-flex" style="position:relative;">
                <a href="<?= route('deliveryBoy.bag.viewParcelfranchiseReceived', ['id' => $bag->id, 'service_type' => $bag->service_type]) ?>">
                    <div class="profile-widget w-100 bagCard">
                        <div class="dash-card-icon">
                            <i class="fa-solid fa-suitcase" style="color: #666363; font-size: 30px;"></i>
                        </div>
                        <h5 class="user-name mt-1 mb-0 text-ellipsis"><a href="#">ID: <?= $bag->bag_id ?></a></h5>
                        <h6 class="user-name mt-1 mb-0 text-ellipsis"><a href="#">Fo-Code: <?= $bag->franchise->franchise_no ?></a></h6>
                        <h6 class="user-name mt-1 mb-0 text-ellipsis"><a href="#">City: <?= $bag->deliveryBoy->pincode ?></a></h6>
                    </div>
                </a>
            </div>
<?php
            $html .= ob_get_clean();
        }

        // Return HTML as response
        return response()->json(['html' => $html]);
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

        return view('deliveryBoy.bag.view', ['datas' => $datas, 'bagId' => $id, 'service_type' => $request->service_type, 'bagDetails' => $bagDetails, 'title' => $title]);
    }


    public function showAllParcelToDeliver(Request $request)
    {
        
        $allData = collect();

        $user = Auth::guard('delboy')->user();
      
        if (!$user) {
            return response()->json([], 200);
        }

        $delivery_boy_id = $user->id;

        // Step 1: Get all bags assigned to the delivery boy
        $bagIds = FranchiseBag::where('delivery_boy_id', $delivery_boy_id)
            ->pluck('id')
            ->toArray();

      
        // if (empty($bagIds)) {
        //     return response()->json([], 200);
        // }

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

        $allData = collect(); // Collection to store final processed data


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

        $filteredData = collect(); // Naya collection banayein


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


        return view('deliveryBoy.bag.showAllParcelToDeliver', [
            'datas' => $filteredData->values(),
            'title' => 'All Parcels of Received Bags',
        ]);
    }


    public function showAllDeliveredParcel(Request $request)
    {

        $service_type = $request->service_type;

        $date = $request->input('date');

        $formattedDate = $date ? Carbon::parse($date)->format('y-m-d') : Carbon::today()->format('y-m-d');

        $allData = collect(); // Flat array ke liye collection

        $title = FranchiseBag::getServiceType($service_type);

        $allData = $allData->merge($this->showAllDeliveredParcelHelper($service_type, $formattedDate));

        return view('deliveryBoy.bag.showAllDeliveredParcel', ['datas' => $allData, 'title' => $title]);
    }


    public function showAllDeliveredParcelHelper($service_type, $formattedDate)
    {
        $user = Auth::guard('delboy')->user();
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
                    'service_type' => $serviceType,
                    'return_type' => $returnType,
                    'cancelDelivery' => $cancelDelivery,
                ];
            }
        }

        // Filter by service_type if provided
        return isset($service_type) ? array_filter($allData, fn($item) => $item['service_type'] == $service_type) : $allData;
    }


    public function sendOtpNotificationToUser($otp, $parcel, $service_type)
    {
        try {
            \Log::info("sendOtpNotificationToUser function called", [
                'parcel_id' => $parcel->id ?? null,
                'consignee_mobile' => $parcel->consignee_mobile ?? null
            ]);

            // Consignee (Receiver) की डिटेल्स निकालें
            $userPhone = $parcel->consignee_mobile;
            $user = User::where('phone', $userPhone)->first();

            if (!$user) {
                \Log::error("User not found for phone: {$userPhone}");
                return;
            }

            \Log::info("User found", ['user_id' => $user->id]);

            $token = $user->fcm_token;
            if ($token) {
                \Log::info("FCM token found", ['user_id' => $user->id, 'fcm_token' => $token]);

                // Notification title & body
                $title = "🔐 GOTOGO Post OTP Verification";
                $body = "📦 Your parcel with Article No. {$parcel->barcode_no} needs verification.\n" .
                    "🔢 OTP: {$otp}\n" .
                    "📍 Delivery Location: {$parcel->consignee_address}\n" .
                    "🚀 Please enter the OTP to confirm delivery.";

                \Log::info("Preparing to send OTP notification", [
                    'title' => $title,
                    'body' => $body,
                    'user_id' => $user->id,
                    'parcel_id' => $parcel->id
                ]);

                // Push Notification
                $notification = new FranchisePushNotification($token, $parcel, $title, $body);
                $notification->sendPushNotification();

                \Log::info("OTP push notification sent successfully", ['user_id' => $user->id]);

                // User Notification Save
                \Log::info("Saving OTP user notification record");

                $parcel->userNotifications()->create([
                    'user_id' => $user->id,
                    'service_type' => get_class($parcel),
                    'parcel_id' => $parcel->id,
                    'message' => "OTP Sent Successfully",
                    'barcode_no' => $parcel->barcode_no,
                    'body' => $body,
                    'current_location' => $parcel->consignee_address,
                ]);

                \Log::info("User notification record created successfully");
            } else {
                \Log::error("FCM token not found for user", ['user_id' => $user->id]);
            }
        } catch (\Exception $e) {
            \Log::error("Error in sendOtpNotificationToUser: " . $e->getMessage(), [
                'parcel_id' => $parcel->id ?? null,
                'user_phone' => $userPhone ?? null,
                'exception' => $e
            ]);
        }
    }

    public function sendNotificationToUser($parcel, $service_type)
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

                // Prepare Notification
                $title = FranchiseBag::getServiceType($service_type);
                $body = "✅ Order Update: Article No. {$parcel->barcode_no}\n" .
                    "📅 Date: " . now()->format('M d, Y, h:i A') . "\n" .
                    "📍 Location: {$parcel->consignee_address}\n" .
                    "🚀 Order has Delivered succefully to  {$parcel->consignee_name} in {$parcel->consignee_address}";

                \Log::info("Sending push notification", ['title' => $title, 'body' => $body]);

                // Send Push Notification
                $notification = new FranchisePushNotification($token, $parcel, $title, $body);
                $status = $notification->sendPushNotification();

                \Log::info("notifiction status", [
                    'status' => $status,
                ]);

                if ($status == 0) {
                    return;
                }

                \Log::info("Saving user notification record");

                $parcel->userNotifications()->create([
                    'user_id' => $user->id,
                    'service_type' => get_class($parcel),
                    'parcel_id' => $parcel->id,
                    'message' => "Order has Delivered",
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

    public function sendNotificationToFranchise($parcel, $service_type)
    {
        try {
            \Log::info("sendNotificationToFranchise started", [
                'parcel_id' => $parcel->id ?? 'N/A',
                'service_type' => $service_type
            ]);

            $franchiseId = $parcel->franchise_id;
            \Log::info("Fetching franchise details", ['franchise_id' => $franchiseId]);

            $franchise = Franchise::findOrFail($franchiseId);

            if (!$franchise) {
                \Log::warning("Franchise not found", ['franchise_id' => $franchiseId]);
                return;
            }

            \Log::info("franchise details", ['$franchise' => $franchise]);


            // Get FCM Token
            $token = $franchise->fcm_token;

            \Log::info("token", ['franchise' => $token]);
            if (!$token) {
                \Log::warning("FCM token not found for franchise", ['franchise_id' => $franchiseId]);
                return;
            }

            \Log::info("Preparing notification", [
                'franchise_id' => $franchiseId,
                'fcm_token' => $token
            ]);

            // Prepare Notification
            $title = FranchiseBag::getServiceType($service_type);
            $body = "✅ Order Update: Article No. {$parcel->barcode_no}\n" .
                "📅 Date: " . now()->format('M d, Y, h:i A') . "\n" .
                "📍 Location: {$parcel->consignee_address}\n" .
                "🚀 Order has Delivered succefully to  {$parcel->consignee_name} in {$parcel->consignee_address}";

            \Log::info("Sending push notification", ['title' => $title, 'body' => $body]);

            // Send Push Notification
            $notification = new FranchisePushNotification($token, $parcel, $title, $body);
            $status = $notification->sendPushNotification();

            \Log::info("notifiction status", [
                'status' => $status,
            ]);

            if ($status == 0) {
                return;
            }

            // Store Notification in Database
            $parcel->franchiseNotifications()->create([
                'franchise_id' => $franchiseId,
                'service_type' => get_class($parcel),
                'parcel_id' => $parcel->id,
                'message' => "Order has Delivered",
                'barcode_no' => $parcel->barcode_no,
                'body' => $body,
                'current_location' => $parcel->consignee_address,
            ]);

            \Log::info("Franchise notification created in database", [
                'franchise_id' => $franchiseId,
                'parcel_id' => $parcel->id
            ]);
        } catch (\Exception $e) {
            \Log::error("Error in sendNotificationToFranchise", [
                'parcel_id' => $parcel->id ?? 'N/A',
                'franchise_id' => $franchiseId ?? 'N/A',
                'error' => $e->getMessage(),
                'exception' => $e
            ]);
        }
    }

    public function sendNotificationToDeliveryBoy($parcel, $service_type)
    {
        try {
            \Log::info("sendNotificationToDeliveryBoy started", [
                'parcel_id' => $parcel->id ?? 'N/A',
                'service_type' => $service_type
            ]);

            $delivery_details = Auth::guard('delboy')->user();
            $delivery_boy_id = $delivery_details->id;
            $token = $delivery_details->fcm_token;

            if (!$token) {
                \Log::warning("FCM token not found for delivery boy");
                return;
            }

            \Log::info("FCM token found", ['token' => $token]);

            // Prepare Notification
            $title = FranchiseBag::getServiceType($service_type);
            $body = "✅ Order Update: Article No. {$parcel->barcode_no}\n" .
                "📅 Date: " . now()->format('M d, Y, h:i A') . "\n" .
                "📍 Location: {$parcel->consignee_address}\n" .
                "🚀 Order has Delivered succefully to  {$parcel->consignee_name} in {$parcel->consignee_address}";

            \Log::info("Sending push notification", ['title' => $title, 'body' => $body]);

            // Send Push Notification
            $notification = new FranchisePushNotification($token, $parcel, $title, $body);
            $status = $notification->sendPushNotification();

            \Log::info("notifiction status", [
                'status' => $status,
            ]);

            if ($status == 0) {
                return;
            }

            // Store Notification in Database
            $parcel->deliveryBoyNotifications()->create([
                'delivery_boy_id' => $delivery_boy_id,
                'service_type' => get_class($parcel),
                'parcel_id' => $parcel->id,
                'message' => "Order has Delivered",
                'barcode_no' => $parcel->barcode_no,
                'body' => $body,
                'current_location' => $parcel->consignee_address,
            ]);

            \Log::info("Delivery Boy notification saved in database", [
                'delivery_boy_id' => $delivery_boy_id,
                'parcel_id' => $parcel->id
            ]);
        } catch (\Exception $e) {
            \Log::error("Error in sendNotificationToFranchise", [
                'parcel_id' => $parcel->id ?? 'N/A',
                'error' => $e->getMessage(),
                'exception' => $e
            ]);
        }
    }


    public function sendOtp(Request $request)
    {

        try {


            $service_type = $request->service_type;
            $Model = FranchiseBag::getServiceModel($service_type);
            $parcelToUpdate = $Model::where('barcode_no', $request->barcode_no)->first();
            $consignee_email = $parcelToUpdate->consignee_email;
            $consignee_mobile = $parcelToUpdate->consignee_mobile;
            $pickup_email = $parcelToUpdate->pickup_email;
            $pickup_mobile = $parcelToUpdate->pickup_mobile;

            $isReturned = ReturnedParcel::where('service_type', $service_type)
                ->where('parcel_id', $parcelToUpdate->id)
                ->exists();

            if ($isReturned) {
                $consignee_email = $pickup_email;
                $consignee_mobile = $pickup_mobile;
            }

            if ($parcelToUpdate) {
                $parcelToUpdate->otp = mt_rand(1111, 9999);
                $parcelToUpdate->save();

                MailFacade::to($consignee_email)->send(new SendOtpMail([
                    'otp' =>  $parcelToUpdate->otp,
                ]));

                $notification = new SMSNotification($consignee_mobile, 'OTP', [$parcelToUpdate->otp]);
                $response = $notification->sendMessage();

                //$this->sendOtpNotificationToUser($parcelToUpdate->otp, $parcelToUpdate, $request->service_type);
                return redirect()->back()->with('success', 'OTP sent successfully!! otp:' . $parcelToUpdate->otp);
                // return redirect()->back()->with('success', 'OTP sent successfully!!');
            } else {
                return back()->with('error', 'Parcel not found.')->withInput();
            }
        } catch (\Exception $th) {
            return back()->with('error', $th->getMessage())->withInput();
        }
    }

    public function verifyOtp(Request $request, RateCalculator $rateCalculator)
    {

        $barcode_no = $request->barcode_no;
        $delivery_boy = Auth::guard('delboy')->user();
        $service_type = $request->service_type;
        $Model = FranchiseBag::getServiceModel($service_type);
        $parcelToUpdate = $Model::where('barcode_no', $barcode_no)->first();
        $title = FranchiseBag::getServiceType($service_type);

        if ($request->isMethod('POST')) {
            try {
                if ($parcelToUpdate) {
                    $submittedOtp = implode('', $request->input('otp', []));

                    if ($parcelToUpdate->otp !== $submittedOtp) {

                        if (strtolower($parcelToUpdate->payment_method) === 'cod') {
                            return view('deliveryBoy.parcel-payment.index', ['parcel' => $parcelToUpdate, 'title' => $title]);
                        }
                        if ($parcelToUpdate->delivered_date == null) {

                            // ✅ Process Prepaid Orders (Directly Complete Delivery)
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
                                "commission_type" => 'delivery',
                                "commission" => $commission,
                            ]);
                        }

                        // ✅ Update Tracking & Delivery Details
                        $TrackingModel = FranchiseBag::getTrackingModel($service_type);
                        $trackingModalToUpdate =  $TrackingModel::where('barcode_no', $request->barcode_no)->first();
                        $trackingModalToUpdate->delivery_datetime = now();
                        $trackingModalToUpdate->delivery_location = $parcelToUpdate->consignee_address;
                        $trackingModalToUpdate->save();

                        $parcelToUpdate->delivered_date = Carbon::today()->toDateString();
                        $parcelToUpdate->delivered_by = $delivery_boy->id;
                        $parcelToUpdate->save();

                        // ✅ Send Notifications
                        $notification = new SMSNotification($parcelToUpdate->pickup_mobile, 'DELIVERED', [$parcelToUpdate->barcode_no, $delivery_boy->name, now()]);
                        $notification->sendMessage();

                        $notification = new SMSNotification($parcelToUpdate->consignee_mobile, 'DELIVERED', [$parcelToUpdate->barcode_no, $delivery_boy->name, now()]);
                        $notification->sendMessage();

                        MailFacade::to($parcelToUpdate->pickup_email)->send(new deliveredMail([$parcelToUpdate]));
                        //  MailFacade::to('fuloriadeepak999@gmail.com')->send(new deliveredMail([$parcelToUpdate]));

                        // $this->sendNotificationToFranchise($parcelToUpdate, $service_type);
                        $this->sendNotificationToUser($parcelToUpdate, $service_type);
                        // $this->sendNotificationToDeliveryBoy($parcelToUpdate, $service_type);
                        return redirect()->back()->with('success', 'Verified successfully! Parcel delivered.');
                    } else {
                        return back()->with('error', 'Invalid OTP. Please try again.')->withInput();
                    }
                } else {
                    return back()->with('error', 'Parcel not found.')->withInput();
                }
            } catch (\Exception $th) {
                return back()->with('error', $th->getMessage())->withInput();
            }
        }

        return view('deliveryBoy.bag.verifyOtp', compact('title', 'parcelToUpdate'));
    }

    public function cancelDelivery(Request $request, RateCalculator $rateCalculator)
    {


        $service_type = $request->service_type;
        $Model = FranchiseBag::getServiceModel($service_type);
        $record = $Model::findOrFail($request->id);

        $delivery_boy = Auth::guard('delboy')->user();

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
                        "commission" => $commission,
                        "commission_type" => 'delivery',
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

                return redirect()->back()->with('success', 'Parcel Canceled successfully!');
            } catch (\Exception $th) {
                return back()->with('error', $th->getMessage())->withInput();
            }
        }
    }

    public function pickupList(Request $request)
    {
        $searchKey = $request->input('searchKey');
        $fromDate = $request->input('fromDate');
        $toDate = $request->input('toDate');
        $delivery_boy_id = Auth::guard('delboy')->user()->id;

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
        $pickupdetails = $query->paginate(10);

        return view('deliveryBoy.bag.pickupList', compact('pickupdetails'));
    }

    // notification of deliverboy
    public function deliveryNotification(Request $request)
    {
        dd('hhj');
    }
}
