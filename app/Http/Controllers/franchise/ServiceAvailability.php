<?php

namespace App\Http\Controllers\franchise;

use App\Http\Controllers\Controller;
use App\Models\Pincode;
use Illuminate\Http\Request;




class ServiceAvailability extends Controller
{
    public function index(Request $request)
    {
        if ($request->method() == 'POST') {

            $pinData = Pincode::where('pincode', $request->pincode)->first();
            if (!$pinData) {
                return response()->json([
                    'message' => 'fail'
                ]);
            }

            $district = ucwords(strtolower($pinData->district));
            $state = ucwords(strtolower($pinData->state_name));
            $data = [
                'message' => 'success',
                'district' => $district,
                'state' => $state,
                'zone' => 1,
                'prePaid' => 'yes',
                'cod' => 'no',
            ];

            return response()->json($data);
        }

        return view('franchise.serviceAvailability.index');
    }
}
