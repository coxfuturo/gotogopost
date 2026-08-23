<?php

namespace App\Http\Controllers\CMS;

use App\Http\Controllers\Controller;
use App\Models\PickupDetails;
use Illuminate\Http\Request;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;


class PickupDetailsController extends Controller
{
    public function index(Request $request): Renderable|JsonResponse|RedirectResponse
    {
        $pickupdetails = PickupDetails::where('cms_id', Auth::guard('cms')->user()->id)
            ->orderBy('created_at', 'desc')->get();
        return view('cms.parcel.pickupDetails', compact('pickupdetails'));
    }


    public function details(Request $request)
    {
        $pickupDetails = PickupDetails::where('id', $request->id)->first();
        return response()->json($pickupDetails);
    }

    public function store(Request $request)
    {

        $this->validate($request, [
            'name' => 'required|string|max:191',
            'email' => 'required|email|max:191|unique:pickup_details',
            'phone' => 'required|numeric|unique:pickup_details',
            'pincode' => 'required|numeric',
            'city' => 'required|string|max:191',
            'state' => 'required|string|max:191',
            'address' => 'required|string|max:191',
        ]);

        try {
            PickupDetails::create([
                'cms_id' => Auth::guard('cms')->user()->id,
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'pincode' => $request->pincode,
                'city' => $request->city,
                'state' => $request->state,
                'address' => $request->address,
            ]);
            return back()->with('success', 'Pickup details Added successfully');
        } catch (\Exception $th) {
            return back()->with('error', $th->getMessage());
        }
    }


    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required|string|max:191',
            'email' => 'required|email|max:191',
            'phone' => 'required|numeric',
            'pincode' => 'required|numeric',
            'city' => 'required|string|max:191',
            'state' => 'required|string|max:191',
            'address' => 'required|string|max:191',
        ]);

        try {
            $user = PickupDetails::findOrfail($id);
            $user->name = $request->name;
            $user->email = $request->email;
            $user->phone = $request->phone;
            $user->pincode = $request->pincode;
            $user->city = $request->city;
            $user->state = $request->state;
            $user->address = $request->address;
            $user->save();
            return back()->with('success', 'pickup Details Updated Successfully');
        } catch (\Exception $th) {
            return back()->with('error', $th->getMessage());
        }
    }

    public function delete($id)
    {
        try {
            PickupDetails::findorfail($id)->delete();
            return back()->with('success', 'Pick up details Deleted Successfully');
        } catch (\Throwable $th) {
            return back()->with('error', $th->getMessage());
        }
    }
}
