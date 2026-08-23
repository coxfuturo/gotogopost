<?php



namespace App\Http\Controllers\market;



use App\Http\Controllers\Controller;

use App\Models\Pincode;

use App\Models\PostalRates;
use App\Models\GotogoPostalRates;
use App\Models\Commission;
use App\Models\IndiaPostBRRate;
use App\Models\IndiaPostBusinessParcel;
use App\Models\IndiaPostSpeedPostParcel;
use App\Models\IndiaPostCommission;
use App\Models\Franchise;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Psr\Http\Message\ResponseInterface;
use App\Models\FranchiseCommissionDetail;
use App\Models\ManagerCommissionDetail;



class RateCalculator extends Controller

{

    public function index()
    {
        return view('franchise.rateCalculator.index');
    }


    // public function distance($origins, $destinations)
    // {

    //     // return ['distance' => 100, 'origin' => $origins, 'destination' => $destinations];

    //     $api_url = 'https://maps.googleapis.com/maps/api/distancematrix/json';

    //     $api_key = 'AIzaSyDeBFQhymQMaqj0DcmPTKeyHLoq9_U6Vb0';

    //     if (!$api_key) {

    //         return response()->json([

    //             'error' => 'API key is missing or invalid.',

    //         ], 400);
    //     }

    //     $response = Http::get($api_url, [

    //         'origins' => $origins,

    //         'destinations' => $destinations,

    //         'units' => 'metric',

    //         'key' => $api_key,

    //     ]);



    //     // return $response;

    //     if ($response->successful()) {

    //         $data = $response->json();

    //         if (isset($data['rows'][0]['elements'][0]['distance']['value'])) {

    //             $distance_info = $data['rows'][0]['elements'][0]['distance']['value'];

    //             return ['distance' => $distance_info, 'origin' => $origins, 'destination' => $destinations];
    //         } else {
    //             return 0;
    //         }
    //     }
    // }

    public function distance($origins, $destinations)
    {
        $api_url = 'https://maps.googleapis.com/maps/api/distancematrix/json';
        $api_key = 'AIzaSyAxmRrwWLag7plK-SQFtQvQxlhRVwI09tY';

        if (!$api_key) {
            return response()->json([
                'error' => 'API key is missing or invalid.',
            ], 400);
        }

        $response = Http::get($api_url, [
            'origins' => $origins,
            'destinations' => $destinations,
            'units' => 'metric',
            'key' => $api_key,
        ]);

        if ($response->successful()) {
            $data = $response->json();

            if (
                isset($data['rows'][0]['elements'][0]['status']) &&
                $data['rows'][0]['elements'][0]['status'] === 'OK' &&
                isset($data['rows'][0]['elements'][0]['distance']['value'])
            ) {
                $distance_info = $data['rows'][0]['elements'][0]['distance']['value'];
                return [
                    'distance' => $distance_info,
                    'origin' => $origins,
                    'destination' => $destinations
                ];
            } else {
                return response()->json(['error' => 'Distance not found or location not valid.'], 400);
            }
        }

        return response()->json(['error' => 'Unable to fetch distance data from Google API.'], 500);
    }

    public function calculateShippingCostForIndiaPostSpeedPost($weight, $distances)

    {
    //    return $distances;
       $distance = floatval(str_replace(',', '', $distances));
        $distanceCategory = ''; // Default variable
        if ($distance <= 100) {

            $distanceCategory = 'Local';
        } elseif ($distance > 100 && $distance <= 200) {

            $distanceCategory = 'upto_200_kms';
        } elseif ($distance > 200 && $distance <= 1000) {

            $distanceCategory = '201_to_1000_kms';
        } elseif ($distance > 1000 && $distance <= 2000) {

            $distanceCategory = '1001_to_2000_kms';
        } else {

            $distanceCategory = 'above_2000_kms';
        }

        
        // Fetch the correct record based on the weight

        if ($weight <= 50) {
        
            $rate = PostalRates::where('weight', 'Up to 50 gm')->first();
        } elseif ($weight > 50 && $weight <= 200) {
            
            $rate = PostalRates::where('weight', '51 to 200 gm')->first();
        } elseif ($weight > 200 && $weight <= 500) {
        
            $rate = PostalRates::where('weight', '201 to 500 gm')->first();
        } else {
        
            $baseRate = PostalRates::where('weight', '201 to 500 gm')->first();
            $basePrice = $baseRate->$distanceCategory;
            $additionalWeight = $weight - 500;
            $additionalIncrements = ceil($additionalWeight / 500);
            $additionalRate = PostalRates::where('weight', 'Additional 500 gm or part thereof')->first();
            $additionalPrice = $additionalIncrements * $additionalRate->$distanceCategory;
            $totalPrice = $basePrice + $additionalPrice;
            return $totalPrice;
        }
        
        $basePrice = $rate->$distanceCategory;
        return $basePrice;
    }

    public function calculateShippingCostForIndiaPostBR($weight, $pickup_state, $consignee_state, $pickup_pincode, $consignee_pincode)
    {
        // Get category based on pickup and consignee states
        $category = $this->getShippingCategory($pickup_state, $consignee_state, $pickup_pincode, $consignee_pincode);

        $rate = IndiaPostBRRate::where('distance', $category)->first();

        // Check if rate is found
        if (!$rate) {
            return 'No rates found';
        }

        $weight = $weight / 1000; // Convert grams to kg
        $totalCost = 0; // Initialize cost variable

        // Calculate total cost based on weight
        if ($weight <= 2) {
            $totalCost = $rate->upto_2kg;
        } elseif ($weight > 2 && $weight <= 5) {
            $extraWeight = ceil($weight - 2); // Extra kg (ceil to round up)
            $totalCost = $rate->upto_2kg + ($extraWeight * $rate->addl_upto_5kg);
        } else { // Weight > 5 kg
            $extraWeightUpto5Kg = 3; // 2 kg to 5 kg range (fixed 3 kg extra)
            $extraWeightAbove5Kg = ceil($weight - 5); // Beyond 5 kg

            $totalCost = $rate->upto_2kg + ($extraWeightUpto5Kg * $rate->addl_upto_5kg) + ($extraWeightAbove5Kg * $rate->above_upto_5kg);
        }

        return $totalCost;
    }


    /**
     * Determine shipping category based on state and pincode.
     */
    private function getShippingCategory($pickup_state, $consignee_state, $pickup_pincode, $consignee_pincode)
    {
        // Convert states to lowercase for comparison
        $senderState = strtolower($pickup_state);
        $receiverState = strtolower($consignee_state);

        // Define Metro Cities
        $metroCities = ['delhi', 'mumbai', 'kolkata', 'chennai', 'bangalore', 'hyderabad'];

        // Define NCR Cities
        $ncrCities = ['delhi', 'ghaziabad', 'noida', 'greater noida', 'faridabad'];

        // Define Neighbouring States Map (All Indian States Covered)
        $neighbouringStates = [
            'andhra pradesh' => ['telangana', 'chhattisgarh', 'odisha', 'tamil nadu', 'karnataka'],
            'arunachal pradesh' => ['assam', 'nagaland'],
            'assam' => ['arunachal pradesh', 'nagaland', 'manipur', 'mizoram', 'tripura', 'meghalaya', 'west bengal'],
            'bihar' => ['jharkhand', 'uttar pradesh', 'west bengal'],
            'chhattisgarh' => ['madhya pradesh', 'maharashtra', 'telangana', 'odisha', 'jharkhand', 'uttar pradesh'],
            'goa' => ['maharashtra', 'karnataka'],
            'gujarat' => ['rajasthan', 'madhya pradesh', 'maharashtra'],
            'haryana' => ['punjab', 'himachal pradesh', 'uttarakhand', 'uttar pradesh', 'rajasthan', 'delhi'],
            'himachal pradesh' => ['jammu & kashmir', 'punjab', 'haryana', 'uttarakhand'],
            'jharkhand' => ['bihar', 'west bengal', 'odisha', 'chhattisgarh', 'uttar pradesh'],
            'karnataka' => ['maharashtra', 'goa', 'kerala', 'tamil nadu', 'andhra pradesh'],
            'kerala' => ['karnataka', 'tamil nadu'],
            'madhya pradesh' => ['rajasthan', 'uttar pradesh', 'chhattisgarh', 'maharashtra', 'gujarat'],
            'maharashtra' => ['gujarat', 'madhya pradesh', 'chhattisgarh', 'telangana', 'karnataka', 'goa'],
            'manipur' => ['mizoram', 'nagaland', 'assam'],
            'meghalaya' => ['assam'],
            'mizoram' => ['tripura', 'assam', 'manipur'],
            'nagaland' => ['assam', 'manipur', 'arunachal pradesh'],
            'odisha' => ['west bengal', 'jharkhand', 'chhattisgarh', 'andhra pradesh'],
            'punjab' => ['jammu & kashmir', 'himachal pradesh', 'haryana', 'rajasthan'],
            'rajasthan' => ['punjab', 'haryana', 'uttar pradesh', 'madhya pradesh', 'gujarat'],
            'sikkim' => ['west bengal'],
            'tamil nadu' => ['kerala', 'karnataka', 'andhra pradesh'],
            'telangana' => ['maharashtra', 'chhattisgarh', 'andhra pradesh', 'karnataka'],
            'tripura' => ['mizoram', 'assam'],
            'uttar pradesh' => ['uttarakhand', 'haryana', 'rajasthan', 'madhya pradesh', 'chhattisgarh', 'bihar', 'jharkhand'],
            'uttarakhand' => ['himachal pradesh', 'uttar pradesh'],
            'west bengal' => ['bihar', 'jharkhand', 'odisha', 'sikkim', 'assam'],
            'jammu & kashmir' => ['himachal pradesh', 'punjab'],
            'ladakh' => ['jammu & kashmir']
        ];

        // Extract first 3 digits of pincode for Local check
        $pickupPrefix = substr($pickup_pincode, 0, 3);
        $consigneePrefix = substr($consignee_pincode, 0, 3);

        // Initialize category variable
        $category = 'Other State'; // Default value


        if ($senderState === $receiverState) {
            $category = 'Within State';
        }

        if (isset($neighbouringStates[$senderState]) && in_array($receiverState, $neighbouringStates[$senderState])) {
            $category = 'Neighbouring State';
        }

        // Determine category based on sender & receiver states
        if ($pickupPrefix === $consigneePrefix) {
            $category = 'Local';
        }

        if (in_array($senderState, $metroCities) && in_array($receiverState, $metroCities)) {
            $category = 'Between Metro and State Capitals';
        }

        if (in_array($senderState, $ncrCities) && in_array($receiverState, $ncrCities)) {
            $category = 'NCR-Delhi/ Ghaziabad/ Noida/ Greater Noida/ Faridabad';
        }



        // Final return
        return $category;
    }

   public function calculateShippingCostForGotogoPost($weight, $distance, $service_type)
{
    // 1. Define the distance category based on the distance
    if ($distance <= 100) {
        $distanceCategory = 'Local';
    } elseif ($distance <= 200) {
        $distanceCategory = 'upto_200_kms';
    } elseif ($distance <= 1000) {
        $distanceCategory = '201_to_1000_kms';
    } elseif ($distance <= 2000) {
        $distanceCategory = '1001_to_2000_kms';
    } else {
        $distanceCategory = 'above_2000_kms';
    }

    // 2. Define weight slabs for service type
    if ($service_type == 1) {
        $weightRanges = [
            250  => 'Upto 250gm',
            500  => 'Additional 250gm to 500gm',
            1000 => 'Additional 500gm to 1kg',
            1500 => 'Additional 1kg to 1.5kg',
            2000 => 'Additional 1.5kg to 2kg',
            2500 => 'Additional 2kg to 2.5kg',
            3000 => 'Additional 2.5kg to 3kg',
            3500 => 'Additional 3kg to 3.5kg',
            4000 => 'Additional 3.5kg to 4kg',
            4500 => 'Additional 4kg to 4.5kg',
            5000 => 'Additional 4.5kg to 5kg',
        ];
    } elseif ($service_type == 3) {
        $weightRanges = [
            1000 => 'First 1 kg',
            1500 => 'Additional 500gm', // Changed key to avoid conflict
        ];
    } elseif ($service_type == 4) {
        $weightRanges = [
            100 => 'Upto 100gms',
        ];
    } else {
        return 0; // Unknown service type
    }

    // 3. Fetch rates from the database
    $ratesData = GotogoPostalRates::where('type', $service_type)->get()->keyBy('weight');

    // 4. Calculate total cost properly
    $totalCost = 0;
    $processedWeight = 0;

    foreach ($weightRanges as $weightLimit => $weightLabel) {
        if (!isset($ratesData[$weightLabel])) {
            continue;
        }

        $rate = $ratesData[$weightLabel]->$distanceCategory;

        if ($service_type == 3 && $weightLimit == 1500) {
            // Additional 500gm slabs repeat after 1kg
            while ($processedWeight + 500 <= $weight) {
                $totalCost += $rate;
                $processedWeight += 500;
            }
        } else {
            if ($weight > $processedWeight && $weight <= $weightLimit) {
                $totalCost += $rate;
                $processedWeight = $weightLimit;
                break;
            } elseif ($weight > $weightLimit) {
                $totalCost += $rate;
                $processedWeight = $weightLimit;
            }
        }
    }

    // 5. Return correctly formatted price
    return number_format($totalCost, 2, '.', '');
}


    public function calculateShippingCostForE2h($pages, $service_type)
    {
        $ratesData = GotogoPostalRates::where('type', $service_type)->get()->keyBy('weight');
        $rateColumn = 'Local';

        $shippingCost = 0;

        if ($pages <= 5) {
            if (isset($ratesData['First 5 Pages'])) {
                $shippingCost = $ratesData['First 5 Pages']->$rateColumn;
            }
        } else {
            if (isset($ratesData['First 5 Pages'])) {
                $shippingCost += $ratesData['First 5 Pages']->$rateColumn;
            }
            if (isset($ratesData['Each Additional Page for 6 & Above'])) {
                $additionalPages = $pages - 5;
                $shippingCost += $additionalPages * $ratesData['Each Additional Page for 6 & Above']->$rateColumn;
            }
        }

        $formattedPrice = number_format($shippingCost, 2, '.', '');
        return $formattedPrice;
    }

    /**
     * Gets the rate column name based on the provided zone.
     *
     * @param string $zone
     * @return string|null
     */


    public function getTotals($originState, $destinationState, $price)

    {
        if ($originState == $destinationState) {
            $totalPrice = $price * 0.09 + $price * 0.09 + $price;
            return ['gst' => $price * 0.09 + $price * 0.09, 'total' => $totalPrice];
        } else {
            $totalPrice = $price * .18 + $price;
            return ['gst' => $price * .18, 'total' => $totalPrice];
        }
    }


    public function getZone($originCity, $originState, $destinationCity, $destinationState)

    {

        $metroCities = ["central delhi", "mumbai", "kolkata", "chennai", "hyderabad", "bengaluru", "ahmedabad", "pune"];

        $fourZoneState = ["delhi", "himachal pradesh", "punjab", "uttarakhand", "uttar pradesh", "rajasthan", "haryana", "chhattisgarh", "bihar", "orissa", "tripura", "jharkhand", "west bengal", "andaman & nicobar", "gujarat", "goa", "maharashtra", "madhya pradesh", "andhra pradesh", "karnataka", "kerala", "telangana", "tamil nadu"];

        $northEastZone = ["jammu and kashmir", "assam", "sikkim", "nagaland", "meghalaya", "manipur", "mizoram", "arunachal pradesh"];

        // Zone 1: Intra-City

        if (in_array($originCity, $metroCities) && $originCity === $destinationCity) {

            return 'A';
        }

        // Zone 2: Intra-Region

        if ($originState == $destinationState && in_array($originState, $fourZoneState)) {

            return 'B';
        }

        // Zone 3: Other Metro Cities

        if (in_array($originCity, $metroCities) && in_array($destinationCity, $metroCities) && $originCity !== $destinationCity) {

            return 'C';
        }

        // Zone 4: inter state

        if ($originState !== $destinationState && in_array($originState, $fourZoneState) && in_array($destinationState, $fourZoneState)) {

            return 'D';
        }

        // Zone 5: northeast

        if (in_array($destinationState, $northEastZone) || in_array($originState, $northEastZone)) {

            return 'E';
        }
    }



    public function calculate(Request $request)

    {
    //   return $request->all();
        if ($request->originPincode) {

            $originData = Pincode::where('pincode', $request->originPincode)->first();

            if (!$originData) {

                return $data = [

                    'status' => 'fail',

                    'message' => 'fill valid origin pincode'

                ];
                return $data;
            }
        }


        if ($request->destinationPincode) {

            $destinationData = Pincode::where('pincode', $request->destinationPincode)->first();

            if (!$destinationData) {


                return $data = [

                    'status' => 'fail',

                    'message' => 'fill valid distination pincode'

                ];

                return $data;
            }
        }

      

        $zone = $this->getZone(

            strtolower($originData->district),

            strtolower($originData->state_name),

            strtolower($destinationData->district),

            strtolower($destinationData->state_name)

        );
       
          $distance = number_format($this->distance($request->originPincode, $request->destinationPincode)['distance'] / 1000);

        $price = 0;

        $fuel_charge = $request->fuel_charge ?? 0;
        $pickup_charge = $request->pickup_charge ?? 0;
        $other_service_charge = $request->other_service_charge ?? 0;
        $amount = $request->amount ?? 0;

        $service_type = $request->service_type;

        if ($amount) {
            $price = $amount;
        } else {
            if ($service_type == 1) {
                $price = $this->calculateShippingCostForGotogoPost($request->packageWeight, $distance, 1);
            } elseif ($service_type == 3) {
                $price = $this->calculateShippingCostForGotogoPost($request->packageWeight, $distance, 3);
            } elseif ($service_type == 4) {
                $price = $this->calculateShippingCostForGotogoPost($request->packageWeight, $distance, 4);
            } elseif ($service_type == 5) {
                $price = $this->calculateShippingCostForIndiaPostSpeedPost($request->packageWeight, $distance);
            } elseif ($service_type == 6) {
                $price = $this->calculateShippingCostForIndiaPostBR($request->packageWeight, $originData->state_name, $destinationData->state_name, $request->originPincode, $request->destinationPincode);
            } elseif ($service_type == 9) {
                $price = $this->calculateShippingCostForE2h($request->pages, 9);
            }
        }


        $totalprice = $price + $fuel_charge + $pickup_charge + $other_service_charge;

        $gst = number_format($this->getTotals($destinationData->state_name, $originData->state_name, $totalprice)['gst'], 2);
        $total = number_format($this->getTotals($destinationData->state_name, $originData->state_name, $totalprice)['total'] + $request->codAmount, 2);

        $data = [

            'status' => 'success',

            'message' => 'details fetch succesfully',

            'zone' => $zone,

            'fuel_charge' => $fuel_charge,

            'pickup_charge' => $pickup_charge,

            'other_service_charge' => $other_service_charge,

            'gst' => $gst,

            'total' =>  $total,

            'distance' => $distance,

            'price' => $price,

            'weight' => $request->packageWeight,

        ];

        return $data;
    }


    public function calculateByAmount(Request $request)

    {

        $fuel_charge = $request->fuel_charge ?? 0;
        $pickup_charge = $request->pickup_charge ?? 0;
        $other_service_charge = $request->other_service_charge ?? 0;
        $amount = $request->amount ?? 0;
        $price = $amount + $fuel_charge + $pickup_charge + $other_service_charge;
        $gst = number_format(($price) * 0.18, 2, '.', '');
        $total = number_format(($price), 2, '.', '') + $gst;
        $data = [

            'status' => 'success',

            'message' => 'details fetch succesfully',

            'fuel_charge' => $fuel_charge,

            'pickup_charge' => $pickup_charge,

            'other_service_charge' => $other_service_charge,

            'gst' => $gst,

            'total' =>  $total,

            'price' => $amount,

            'weight' => $request->packageWeight,

        ];

        return $data;
    }

    public function calculateCommissionForGotogoPost($weight, $membertype = 0, $service_type = 0)
    {

        if ($service_type == 1 || $service_type == 2 || $service_type == 5) {

            $weightRanges = [
                'Up to 250 gm' => 250,
                '250gm to 500gm' => 500,
                '500 to 1kg' => 1000,
                '1kg to 1.5kg' => 1500,
                '1.5kg to 2kg' => 2000,
                '2kg to 2.5kg' => 2500,
                '2.5kg to 3kg' => 3000,
                '3kg to 3.5kg' => 3500,
                '3.5kg to 4kg' => 4000,
                '4kg to 4.5kg' => 4500,
                '4.5kg to 5kg' => 5000,
            ];
        }


        if ($service_type == 3 || $service_type == 6) {
            $weightRanges = [
                'First 1 KG' => 2000,
                'Additional 500gm' => 1000,
            ];
        }


        if ($service_type == 4) {

            $weightRanges = [
                'Upto 100gms' => 100,
            ];
        }


        $rates = Commission::where('membertype', $membertype)
            ->where('servicetype', $service_type)
            ->get()
            ->keyBy('weight');



        $commissionAmount = 0;
        $accumulatedWeight = 0;
        foreach ($weightRanges as $rangeLabel => $maxWeight) {
            if ($weight <= $maxWeight) {
                $currentSlabWeight = $weight - $accumulatedWeight;
                if ($rates->has($rangeLabel)) {
                    $commissionAmount += $rates->get($rangeLabel)->amount;
                }
                break;
            } else {
                if ($rates->has($rangeLabel)) {
                    $commissionAmount += $rates->get($rangeLabel)->amount;
                }
                $accumulatedWeight = $maxWeight;
            }
        }


        $formattedPrice = number_format($commissionAmount, 2, '.', '');
        return $formattedPrice;
    }


    public function calculateCommissionForE2h($pages, $membertype = 0, $service_type)
    {

        $firstTwoPagesRate = Commission::where('membertype', $membertype)
            ->where('servicetype', $service_type)
            ->where('weight', 'First 5 Pages')
            ->first();

        $additionalPagesRate = Commission::where('membertype', $membertype)
            ->where('servicetype', $service_type)
            ->where('weight', 'Additional Pages')
            ->first();


        $commissionAmount = 0;


        if ($pages <= 5) {

            if ($firstTwoPagesRate) {
                $commissionAmount = $firstTwoPagesRate->amount;
            }
        } else {

            if ($firstTwoPagesRate) {
                $commissionAmount += $firstTwoPagesRate->amount;
            }
            if ($additionalPagesRate) {
                $additionalPages = $pages - 5;
                $commissionAmount += $additionalPages * $additionalPagesRate->amount;
            }
        }

        $formattedPrice = number_format($commissionAmount, 2, '.', '');
        return $formattedPrice;
    }

    public function calculateCommissionForIndiaPost($weight, $payment_amount, $service_type = 0)
{
    // Remove 18% GST from the payment amount
    $netAmount = $payment_amount / 1.18;

    // Determine commission based on net amount
    $commission = 0;
    if ($netAmount >= 15 && $netAmount < 35) {
        $commission = 3;
    } elseif ($netAmount >= 35) {
        $commission = 5;
    }

    // Return commission formatted to 2 decimal places
    return number_format($commission, 2, '.', '');
}

   public function calculateCommissionForIndiaPostMarket($payment_type = 0, $payment_amount = 0)
{
    $commission = 0;
   // Remove 18% GST from the payment amount
 $netAmount = $payment_amount / 1.18;

// Calculate 5% commission on the net amount
 $commission = $netAmount * 0.05;

// Return commission formatted to 2 decimal places
return number_format($commission, 2, '.', '');

}

//    public function calculateCommissionForIndiaPost($weight, $payment_amount = 0, $service_type = 0)
// {
//     $commission = 0;

//     if ($payment_amount >= 15 && $payment_amount < 35) {
//         $commission = 3;
//     } elseif ($payment_amount >= 35) {
//         $commission = 5;
//     }

//     $formattedPrice = number_format($commission, 2, '.', '');
//     return $formattedPrice;
// }




    // 🔹 Calculate Pickup Commission Based on Distance
    public function calculatePickupCommissionForGotogoPost($franchise_address, $pickup_address)
    {

        $distanceData = $this->distance($franchise_address, $pickup_address);

        if (isset($distanceData['error'])) {
            return ['error' => $distanceData['error']];
        }

        $distance = $distanceData['distance'] / 1000;

        // ✅ Commission Calculation
        if ($distance <= 5) {
            $formattedPrice = 50; // Fixed ₹50 for first 5KM
        } else {
            $extraDistance = $distance - 5; // Extra KM after 5KM
            $formattedPrice = 50 + ($extraDistance * 5); // ₹5 per extra KM
        }

        return  number_format($formattedPrice, 2, '.', '');
    }



    public function calculatePickupCommissionForIndiaPost($franchise_address, $pickup_address)
    {
        $formattedPrice = number_format(0, 2, '.', '');
        return $formattedPrice;
    }

    public function calculateCommissionForIndiaPostByRevenue($service_type = 0, $franchise_id = null, $startdate = null, $enddate = null)
    {

        $franchise_id = $franchise_id ?? Franchise::getFranchiseId();
        // If no startdate & enddate provided, use current month
        $startDate = $startdate ?? now()->startOfMonth();
        $endDate = $enddate ?? now()->endOfMonth();

        // Fetch total revenue for the selected date range
        $totalAmount = 0;
         
    //           $data = FranchiseCommissionDetail::where('service_type',$service_type)
    //    ->where('franchise_id',$franchise_id)
    //    ->whereBetween('created_at', [$startDate, $endDate])
    //    ->get();
    //    $totalAmount = $data->sum('amount');
    //    $totalCommission = $data->sum('commission');

//   return $service_type;
        $data = ManagerCommissionDetail::where('servicetype',$service_type)
       ->where('commission_id',$franchise_id)
       ->whereBetween('created_at', [$startDate, $endDate])
       ->get();
       $totalAmount = $data->sum('amount');
       $totalCommission = $data->sum('commission');
           

        return [
            'amount' => $totalAmount,
            'commission' =>  number_format($totalCommission, 2, '.', '')
        ];
    }
}
