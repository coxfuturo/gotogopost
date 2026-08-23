<?php

namespace App\Http\Controllers;

use App\Models\Pincode;
use App\Models\Franchise;
use App\Models\PPH;
use App\Models\CMS;
use App\Models\DeliveryBoy;
use Illuminate\Http\Request;

class CityStateController extends Controller
{
    public function getCityState($pincode, Request $request)
    {
    //    return 44;
        if ($request->type == "franchise") {
            return $this->getFranchiseCityState($pincode);
        }

        if ($request->type == "cms") {

            return $this->getcmsCityState($pincode);
        }
        if ($request->type == "pph") {

            return $this->getpphCityState($pincode);
        }

        if ($request->type == "deliveryBoy") {

            return $this->getDeliveryBoyCityState($pincode, $request->name);
        }

         $data = Pincode::where('pincode', $pincode)->get();

$html = "<select name='address2' class='form-select'>";
foreach ($data as $list) {
    $html .= "<option value='" . $list->office_name . "'>" . $list->office_name . "</option>";
}
$html .= "</select>";


        $city = Pincode::where('pincode', $pincode)->first();
        if ($city) {
            return response()->json(['success' => true, 'district' => $city->district, 'state' => $city->state_name, 'html'=> $html]);
        } else {
            return response()->json(['success' => false]);
        }
    }

    public function gotogoCityState($pincode, Request $request)
    {

    if ($request->type == "franchise") {
            return $this->getFranchiseCityState($pincode);
        }

        if ($request->type == "cms") {

            return $this->getcmsCityState($pincode);
        }
        if ($request->type == "pph") {

            return $this->getpphCityState($pincode);
        }

        if ($request->type == "deliveryBoy") {

            return $this->getDeliveryBoyCityState($pincode, $request->name);
        }

         $data = Pincode::where('pincode', $pincode)->get();

$html = "<select name='address2' class='form-select'>";
foreach ($data as $list) {
    $html .= "<option value='" . $list->office_name . "'>" . $list->office_name . "</option>";
}
$html .= "</select>";

        $city = Pincode::where('pincode', $pincode)->where('status',1)->first();
        
        if ($city) {
        return response()->json([
            'success' => true,
            'district' => $city->district,
            'state' => $city->state_name,
            'html'=> $html
        ]);
    } else {
        return response()->json(['success' => false]);
    }
    }


    public function getFranchiseCityState($pincode)
    {
        $city = Pincode::where('pincode', $pincode)->first();
    
        $latestFromthisPincode = Franchise::where('pincode', 'like', $pincode)->latest()->first();
     
        if ($latestFromthisPincode) {
            $current_id = (int)str_replace('@gotogopost.in', '', $latestFromthisPincode->generated_id);
            $temp_generated_id = $current_id + 1;
            $generated_id = (string)$temp_generated_id . '@gotogopost.in';
        } else {
            $generated_id = (string)$pincode . '001' . '@gotogopost.in';
        }

        if ($city) {
            return response()->json(['success' => true, 'district' => $city->district, 'state' => $city->state_name, 'generated_id' => $generated_id]);
        } else {
            return response()->json(['success' => false]);
        }
    }


    public function getcmsCityState($pincode)
    {
        $city = Pincode::where('pincode', $pincode)->first();
        $latestFromthisPincode = CMS::where('pincode', 'like', $pincode)->latest()->first();

        if ($latestFromthisPincode) {
            $current_id = (int)str_replace('@gotogopost.in', '', $latestFromthisPincode->generated_id);
            $temp_generated_id = $current_id + 1;
            $generated_id = (string)$temp_generated_id . '@gotogopost.in';
        } else {
            $generated_id = (string)$pincode . '001' . '@gotogopost.in';
        }

        if ($city) {
            return response()->json(['success' => true, 'district' => $city->district, 'state' => $city->state_name, 'generated_id' => $generated_id]);
        } else {
            return response()->json(['success' => false]);
        }
    }

    public function getpphCityState($pincode)
    {
        $city = Pincode::where('pincode', $pincode)->first();
        $latestFromthisPincode = PPH::where('pincode', 'like', $pincode)->latest()->first();

        if ($latestFromthisPincode) {
            $current_id = (int)str_replace('@gotogopost.in', '', $latestFromthisPincode->generated_id);
            $temp_generated_id = $current_id + 1;
            $generated_id = (string)$temp_generated_id . '@gotogopost.in';
        } else {
            $generated_id = (string)$pincode . '001' . '@gotogopost.in';
        }

        if ($city) {
            return response()->json(['success' => true, 'district' => $city->district, 'state' => $city->state_name, 'generated_id' => $generated_id]);
        } else {
            return response()->json(['success' => false]);
        }
    }

    public function getDeliveryBoyCityState($pincode, $name)
    {
        $name = str_replace(' ', '', $name);
        $city = Pincode::where('pincode', $pincode)->first();
        $latestFromthisPincode = DeliveryBoy::where('pincode', 'like', $pincode)->latest()->first();

        if ($latestFromthisPincode) {;
            $current_id = str_replace('@gotogopost.in', '', $latestFromthisPincode->generated_id);
            $last_three_digits = (int) substr($current_id, -3);
            $temp_generated_id = $last_three_digits + 1;
            $temp_generated_id = str_pad($temp_generated_id, 3, '0', STR_PAD_LEFT);
            $generated_id = $name . substr($pincode, 0, 3) . $temp_generated_id . '@gotogopost.in';
        } else {
            $generated_id = $name . substr($pincode, 0, 3) . '001' . '@gotogopost.in';
        }
        if ($city) {
            return response()->json(['success' => true, 'district' => $city->district, 'state' => $city->state_name, 'generated_id' => $generated_id]);
        } else {
            return response()->json(['success' => false]);
        }
    }

public function gotogoPincode(Request $request)
{
    
    // Start building the query for Pincode model
    $query = Pincode::query();

    // Determine if any filters are applied based on request parameters
    $isFiltered = $request->filled('state') || $request->filled('district') || $request->filled('pincode');

    // Only show status = 1 when no filters are applied
    if (!$isFiltered) {
        $query->where('status', 1);
    }

    // Apply filters if they exist
    if ($request->filled('state')) {
        $query->where('state_name', $request->state);
    }

    if ($request->filled('district')) {
        $query->where('district', $request->district);
    }

    if ($request->filled('pincode')) {
        $query->where('pincode', $request->pincode);
    }

    // Order by state_name and district
    $pincodes = $query->orderBy('state_name', 'asc')
                      ->orderBy('district', 'asc')
                      ->get();
                    //   ->paginate(1000);

    // Return view with the result
    return view('admin.gotogoPincode', compact('pincodes'));
}




        public function getState(Request $request)
{
    $data = Pincode::where('state_name', $request->state)->groupBy('district')->get();

    if ($data->isEmpty()) {
        $options = '<option value="" disabled selected>No district found</option>';
    } else {
        $options = '<option value="" disabled selected>-- Select District --</option>';
        foreach ($data as $item) {
            $options .= '<option value="' . $item->district . '">' . $item->district . '</option>';
        }
    }

    return response()->json([
        'success' => true,
        'html' => $options
    ]);
}

public function status(Request $request)

    {
      

        try {

            $user = Pincode::findorfail($request->id);

            $status = $request->status;

            $user->status = $status;

            $user->save();
            return response()->json(['success' => true, 'status' => $user->status]);
        } catch (\Throwable $th) {

            return response()->json(['success' => false]);
        }
    }

}
