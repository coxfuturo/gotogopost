<?php



namespace App\Http\Controllers\api\user;


use App\Http\Controllers\Controller;
use App\Models\Pincode;
use Illuminate\Http\Request;
use App\Models\Franchise;
use  App\Http\Controllers\franchise\RateCalculator;




class PincodeController extends Controller

{

    public function listFranchiseOfAnyPincode($pincode)
    {
        try {
            $franchises = Franchise::where('pincode', $pincode)->where('status', 1)->get();

            if ($franchises->isEmpty()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'No franchises found for the given pincode.',
                    'data' => []
                ], 404);
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Franchises retrieved successfully.',
                'data' => $franchises
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while retrieving franchises.',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    public function getCityStateByPincode($pincode)
    {
        $city = Pincode::where('pincode', $pincode)->first();

        if ($city) {
            $latestFromthisPincode = Franchise::where('pincode', $pincode)->where('status', 1)->first();
            if ($latestFromthisPincode) {
                if ($city) {
                    return response()->json(['success' => true, 'district' => $city->district, 'state' => $city->state_name]);
                } else {
                    return response()->json(['success' => false]);
                }
            } else {
                return response()->json(['success' => false, 'message' => 'service not available', 'district' => $city->district, 'state' => $city->state_name]);
            }
        } else {
            return response()->json(['success' => false, 'message' => 'wrong pincode']);
        }
    }


    public function nearestFranchise($pincode, RateCalculator $rateCalculator)
    {
        // Step 1: Get Pincode Details
        $pincodeDetails = Pincode::where('pincode', $pincode)->first();

        if (!$pincodeDetails) {
            return response()->json(['success' => false, 'message' => 'Invalid Pincode']);
        }

        $state = trim(strtolower($pincodeDetails->state_name)); // ✅ `state_name`
        $city = trim(strtolower($pincodeDetails->office_name)); // ✅ `office_name` (district)

        $data = [];

        // Step 2: Search by Pincode
        $franchises = Franchise::where('pincode', $pincode)->where('status', 1)->get();

        // Step 3: If < 5 results, search by City (office_name)
        if ($franchises->count() < 5) {
            $cityFranchises = Franchise::whereRaw("LOWER(district) = ?", [$city])
                ->where('status', 1)
                ->get();
            $franchises = $franchises->merge($cityFranchises);
        }

        // Step 4: If still < 5 results, search by State (state_name)
        if ($franchises->count() < 5) {
            $stateFranchises = Franchise::whereRaw("LOWER(state) = ?", [$state])
                ->where('status', 1)
                ->get();
            $franchises = $franchises->merge($stateFranchises);
        }

        // Step 5: Calculate Distance & Prepare Data
        foreach ($franchises as $franchise) {
            $distanceData = $rateCalculator->distance($pincode, $franchise->pincode);

            $data[] = [
                "franchise_id" => $franchise->id,
                "address" => $franchise->address,
                "pincode" => $franchise->pincode,
                "distance" => $distanceData['distance'],
            ];
        }

        // Step 6: Sort by Distance
        usort($data, function ($a, $b) {
            return $a['distance'] <=> $b['distance'];
        });

        // Step 7: Return At Least 5 Results (Even if less available)
        return response()->json(array_slice($data, 0, max(5, count($data))));
    }



    public function getPrice(Request $request, RateCalculator $rateCalculator)
    {
   
        $data = $rateCalculator->calculate($request);
        return $data;
    }

  
}
