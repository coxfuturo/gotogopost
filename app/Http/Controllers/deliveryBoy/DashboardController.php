<?php

namespace App\Http\Controllers\deliveryBoy;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\GotogoSpeedPostParcel;
use App\Models\DeliveryBoyBagParcel;
use App\Models\ReturnedParcel;
use App\Models\DeliveryBoyCommissionDetail;
use App\Models\FranchiseBag;
use App\Models\PickupDetails;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{


    public function pendingCount()
    {

        $allData = collect();

        $user = Auth::guard('delboy')->user();

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


    public function deliveredParcelCount($start, $end)
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
                ->whereBetween('delivered_date', [$start, $end])
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


    public function cancelCount($start, $end)
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


            // Fetch canceled parcels
            $canceledRecords = $Model::whereIn('id', $parcelIds)
                ->whereHas('cancelDelivery', function ($query) use ($start, $end, $delivery_boy_id) {
                    $query->whereBetween('created_at', [$start, $end])
                        ->where('cancelled_by', $delivery_boy_id);
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



    public function CommissionCount($start, $end)
    {
        $user = Auth::guard('delboy')->user();
        $delivery_boy_id = $user->id;

        $today = Carbon::today();
        $data = DeliveryBoyCommissionDetail::where('delivery_boy_id', $delivery_boy_id)
            ->whereBetween('created_at', [$start, $end])
            ->get();

        foreach ($data as $key => $value) {

            $value->service_type = GotogoSpeedPostParcel::getServiceType($value->service_type);
        }

        return $data->sum('commission');
    }


    public function paymentToCollect($start, $end)
    {
        $allData = collect();

        $user = Auth::guard('delboy')->user();

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


    public function paymentCollected($start, $end)
    {

        $formattedDate = Carbon::today()->format('y-m-d');

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
                ->whereBetween('delivered_date', [$start, $end])
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


    public function pickupCount()
    {
        $delivery_boy_id = Auth::guard('delboy')->user()->id;

        // jo assing hue hain lekin abhi tak uthaye nhi hain
        $count = PickupDetails::where('deliveryboy_id', $delivery_boy_id)
            ->where('status', '0')
            ->count();

        return $count;
    }



    public function index(Request $request)
    {


        $start = $request->start ? Carbon::parse($request->start)->startOfDay()->format('Y-m-d H:i:s') : Carbon::today()->startOfDay()->format('Y-m-d H:i:s');
        $end = $request->end ? Carbon::parse($request->end)->endOfDay()->format('Y-m-d H:i:s') : Carbon::today()->endOfDay()->format('Y-m-d H:i:s');

        $pendingCount = $this->pendingCount();
        $cancelCount = $this->cancelCount($start, $end);
        $deliveredParcelCount = $this->deliveredParcelCount($start, $end);



        $commissionCount = $this->CommissionCount($start, $end);
        $paymentToCollect = $this->paymentToCollect($start, $end);
        $paymentCollected = $this->paymentCollected($start, $end);
        $pickupCount = $this->pickupCount($start, $end);

        $data = [
            'cancel_count' => $cancelCount ?? 0,
            'pending_count' => $pendingCount ?? 0,
            'delivered_parcel_count' => $deliveredParcelCount ?? 0,
            'commission_count' => $commissionCount ?? 0,
            'payment_to_collect' => $paymentToCollect ?? 0,
            'payment_collected' => $paymentCollected ?? 0,
            'pickup_count' => $pickupCount ?? 0,
        ];


        // Array ya invalid values ko 0 set karo
        foreach ($data as $key => $value) {
            if (!is_numeric($value)) {
                $data[$key] = 0;
            }
        }

        // View me associative array pass karo
        return view('deliveryBoy.dashboard', $data);
    }
}
