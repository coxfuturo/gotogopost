<?php

namespace App\Http\Controllers\api\delivered;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\FranchiseBag;
use App\Http\Controllers\franchise\RateCalculator;
use App\Models\DeliveryBoyCommissionDetail;
use App\Models\DeliveryBoyNotification;
use App\Models\GotogoSpeedPostParcel;
use App\Models\DeliveryBoyBagParcel;
use App\Models\PickupDetails;
use App\Models\DeliveryBoy;
use Carbon\Carbon;
use App\Mail\SendOtpMail;
use App\Models\ReturnedParcel;
use Illuminate\Support\Facades\Mail as MailFacade;
use Illuminate\Support\Facades\Config;

class DashboardController extends Controller

{

    public function deliveryNotification(Request $request)
    {
        $date = $request->input('date');

        $formattedDate = $date ? Carbon::parse($date)->format('Y-m-d') : Carbon::today()->format('Y-m-d');
        $id = Auth::guard('apidelboy')->user()->id;
        $get_notification = DeliveryBoyNotification::where('delivery_boy_id', $id)
            ->whereDate('created_at', $formattedDate)
            ->get();
        return response()->json([
            'status' => 'success',
            'message' => 'Delivery notifications fetched successfully',
            'data' => $get_notification
        ], 200);
    }



    public function pendingCount()
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

        return $filteredData->count();
    }


    public function deliveredParcelCount()
    {

        $formattedDate = Carbon::today()->format('y-m-d');

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


            foreach ($deliveredRecords as $record) {
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
        return isset($service_type) ? count(array_filter($allData, fn($item) => $item['service_type'] == $service_type)) : count($allData);
    }


    public function cancelCount()
    {

        $formattedDate = Carbon::today()->format('y-m-d');

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


            foreach ($filteredRecords as $record) {
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
        return isset($service_type) ? count(array_filter($allData, fn($item) => $item['service_type'] == $service_type)) : count($allData);
    }



    public function CommissionCount()
    {
        $user = Auth::guard('apidelboy')->user();
        $delivery_boy_id = $user->id;

        $today = Carbon::today();
        $data = DeliveryBoyCommissionDetail::where('delivery_boy_id', $delivery_boy_id)
            ->whereDate('created_at', $today)
            ->get();

        foreach ($data as $key => $value) {

            $value->service_type = GotogoSpeedPostParcel::getServiceType($value->service_type);
        }

        return $data->sum('commission');
    }


    public function paymentToCollect()
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

        $total = 0;
        foreach ($filteredData as $item) {
            if (isset($item['payment_method']) && trim(strtolower($item['payment_method'])) === 'cod') {
                $total += (float) ($item['payment_amount'] ?? 0);
            }
        }

        return $total;
    }


    public function paymentCollected()
    {

        $formattedDate = Carbon::today()->format('y-m-d');

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


            foreach ($deliveredRecords as $record) {
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

        $total = 0;
        foreach ($allData as $item) {
            if (isset($item['payment_method']) && trim(strtolower($item['payment_method'])) === 'cod') {
                $total += (float) ($item['payment_amount'] ?? 0);
            }
        }

        return $total;
    }

    public function allParcelToDeliver()
    {
        return 0;
    }


    public function pickupCount()
    {
        $delivery_boy_id = Auth::guard('apidelboy')->user()->id;

        $count = PickupDetails::where('deliveryboy_id', $delivery_boy_id)
            ->where('status', '0')
            ->count();

        return $count;
    }



    public function allDeliveredParcelCount()
    {
        $pendingCount = $this->pendingCount();
        $cancelCount = $this->cancelCount();
        $deliveredParcelCount = $this->deliveredParcelCount();
        $commissionCount = $this->CommissionCount();
        $paymentToCollect = $this->paymentToCollect();
        $paymentCollected = $this->paymentCollected();
        $allParcelToDeliver = $this->allParcelToDeliver();
        $pickupCount = $this->pickupCount();

        $data = [
            'cancel_count' => $cancelCount,
            'pending_count' => $pendingCount,
            'delivered_parcel_count' => $deliveredParcelCount,
            'commission_count' => $commissionCount,
            'payment_to_collect' => $paymentToCollect,
            'payment_collected' => $paymentCollected,
            'all_parcel_to_deliver' => $allParcelToDeliver,
            'pickup_count' => $pickupCount,
        ];

        return response()->json($data, 200);
    }


    public function commissionDetails(Request $request, RateCalculator $rateCalculator)
    {

        $delivery_boy_id = Auth::guard('apidelboy')->user()->id;
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

        $franchiseDetails = DeliveryBoy::findOrFail($delivery_boy_id)->only([
            'delivery_boy_no',
            'name',
            'mobile',
            'address',
            'pincode',
            'city',
            'state',
            'country',
        ]);

        return response()->json([
            'gotogoCommission' => $gotogoCommission,
            'indiaPostCommission' => $indiaPostCommission,
            'totalGotogoCommission' => $totalGotogoCommission,
            'totalIndiaPostCommission' => $totalIndiaPostCommission,
            'franchiseDetails' => $franchiseDetails,
        ]);
    }


    public function trackOrder(Request $request)
    {
        $trackOrder = $request->input('trackOrder');

        // Get Tracking and Booking Models (These should return class names, not objects)
        $TrackingModel = FranchiseBag::getTrackingModelByBarcode($trackOrder);
        $BookingModel = FranchiseBag::getBookingModelByBarcode($trackOrder);

        // Check if model class names are valid
        if (!class_exists($TrackingModel) || !class_exists($BookingModel)) {
            return response()->json([
                'error' => 'Invalid barcode or model not found.'
            ], 400);
        }

        // Fetch tracking and booking details
        $trackingDetails = $TrackingModel::where('barcode_no', $trackOrder)->first();
        $bookingDetails = $BookingModel::where('barcode_no', $trackOrder)->first();

        // Check if any data is found
        if (!$trackingDetails && !$bookingDetails) {
            return response()->json([
                'error' => 'No tracking details found.'
            ], 404);
        }

        return response()->json([
            'message' => 'Tracking details found',
            'tracking' => $trackingDetails,
            'booking' => $bookingDetails
        ], 200);
    }
}
