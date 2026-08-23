<?php

namespace App\Http\Controllers\api\franchise;

use App\Http\Controllers\Controller;
use App\Models\PickupDetails;
use App\Models\Franchise;
use  App\Http\Controllers\franchise\RateCalculator;
use Illuminate\Http\Request;


use Carbon\Carbon;

class PickupDetailsController extends Controller
{
    public function store(Request $request)
    {

        $validator = \Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required',
            'phone' => 'required',
            'pincode' => 'required',
            'city' => 'required',
            'state' => 'required',
            'address' => 'required',
        ]);

        // If validation fails, return the validation errors in JSON format
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors(),
                'showMessage' => 1
            ], 422);
        }

        // Get franchise ID, either from the request or default method
        if ($request->franchiseID) {
            $franchiseId = $request->franchiseID;
        } else {
            $franchiseId = Franchise::getFranchiseId();
        }

        try {
            // Create new PickupDetails entry
            $data =  PickupDetails::create([
                'franchise_id' => $franchiseId,
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'pincode' => $request->pincode,
                'city' => $request->city,
                'state' => $request->state,
                'address' => $request->address,
            ]);

            // Return success response in JSON format
            return response()->json([
                'success' => true,
                'data' => $data,
                'message' => 'Pickup details added successfully',
            ], 201);
        } catch (\Exception $e) {
            // Return error response if something goes wrong
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'showMessage' => 1
            ], 500);
        }
    }

    public function getPinCodes(Request $request, RateCalculator $rateCalculator)

    {

        $state = strtolower($request->state);
        $city = strtolower($request->city);
        $nearbyFranchiseStateCity = Franchise::whereRaw('LOWER(state) LIKE ?', ["%{$state}%"])

            ->whereRaw('LOWER(city) LIKE ?', ["%{$city}%"])

            ->where('status', 1)

            ->get();


        $data = [];

        foreach ($nearbyFranchiseStateCity as $franchise) {

            $data[] = [

                'franchiseInfo' => $franchise,

                'distance' => $rateCalculator->distance($request->pincode, $franchise->pincode),

            ];
        }

        usort($data, function ($a, $b) {

            return $a['distance']['distance'] <=> $b['distance']['distance'];
        });



        return response()->json($data);
    }
}
