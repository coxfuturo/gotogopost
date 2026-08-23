<?php

namespace App\Http\Controllers\CMS;

use App\Http\Controllers\Controller;
use App\Models\SoftCopyParcel;
use App\Models\PickupDetails;
use Illuminate\Http\Request;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;




class SoftCopyController extends Controller
{
    public function index(): Renderable|JsonResponse|RedirectResponse
    {
        $data = SoftCopyParcel::where('cms_id', Auth::guard('cms')->user()->id)
            ->orderBy('created_at', 'desc')
            ->get();
        return view('cms.softCopyParcel.index', ['datas' => $data]);
    }


    public function store(Request $request): Renderable|RedirectResponse
    {

        // dd($request->file('file'));
        try {
            // Create Pickup
            $softcopy = SoftCopyParcel::create([
                'cms_id' => Auth::guard('cms')->user()->id,
                'pickup_name' => 'John Doe',
                'pickup_mobile' => '1234567890',
                'pickup_email' => 'john@example.com',
                'pickup_pincode' => '123456',
                'pickup_city' => 'Pickup City',
                'pickup_state' => 'Pickup State',
                'pickup_address' => '123 Pickup Street',
                'consignee_name' => 'Jane Smith',
                'consignee_mobile' => '0987654321',
                'consignee_email' => 'jane@example.com',
                'consignee_pincode' => '654321',
                'consignee_city' => 'Consignee City',
                'consignee_state' => 'Consignee State',
                'consignee_address' => '456 Consignee Street',
                'package_weight' => 2.5,
                'package_length' => 10,
                'package_width' => 5,
                'package_height' => 8,
                'payment_method' => 'Credit Card',
                'order_no' => uniqid(),
            ]);
            return redirect()->route('cms.softCopyParcel.index')->with('success', 'New Parcel Added');
        } catch (\Exception $th) {
            return back()->with('error', $th->getMessage());
        }
    }

    public function edit(Request $request, $id)
    {
        $post = SoftCopyParcel::findOrFail($id);

        if ($request->isMethod('POST')) {


            $this->validate($request, [
                // Pickup details validation rules
                'PickupName' => 'required|string|max:191',
                'PickupMobile' => 'required|string',
                'PickupEmail' => 'required|email|max:191',
                'PickupPincode' => 'required|string|max:6',
                'PickupCity' => 'required|string|max:191',
                'PickupState' => 'required|string|max:191',
                'PickupAddress' => 'required|string|max:255',
                // Consignee details validation rules
                'ConsigneeName' => 'required|string|max:191',
                'ConsigneeMobile' => 'required|string',
                'ConsigneeEmail' => 'required|email|max:191',
                'ConsigneePincode' => 'required|string|max:6',
                'ConsigneeCity' => 'required|string|max:191',
                'ConsigneeState' => 'required|string|max:191',
                'ConsigneeAddress' => 'required|string|max:255',
                // Parcel details validation rules
                'package_weight' => 'required|numeric',
                'package_length' => 'required|numeric',
                'package_width' => 'required|numeric',
                'package_height' => 'required|numeric',
                'payment_method' => 'required|string',
            ]);


            try {
                $post->pickup_name = $request->PickupName;
                $post->pickup_mobile = $request->PickupMobile;
                $post->pickup_email = $request->PickupEmail;
                $post->pickup_pincode = $request->PickupPincode;
                $post->pickup_city = $request->PickupCity;
                $post->pickup_state = $request->PickupState;
                $post->pickup_address = $request->PickupAddress;
                $post->consignee_name = $request->ConsigneeName;
                $post->consignee_mobile = $request->ConsigneeMobile;
                $post->consignee_email = $request->ConsigneeEmail;
                $post->consignee_pincode = $request->ConsigneePincode;
                $post->consignee_city = $request->ConsigneeCity;
                $post->consignee_state = $request->ConsigneeState;
                $post->consignee_address = $request->ConsigneeAddress;
                $post->package_weight = $request->package_weight;
                $post->package_length = $request->package_length;
                $post->package_width = $request->package_width;
                $post->package_height = $request->package_height;
                $post->payment_method = $request->payment_method;

                $post->save();

                return redirect()->route('cms.softCopyParcel.index')->with('success', 'Parcel updated successfully!');
            } catch (\Exception $th) {
                return back()->with('error', $th->getMessage())->withInput();
            }
        }

        $data = $post;
        $pickupDetails = PickupDetails::where('franchise_id', Auth::guard('cms')->user()->id)
            ->get();

        return view('cms.softCopyParcel.edit', compact('data', 'pickupDetails'));
    }

    public function view($id)
    {
        $data = SoftCopyParcel::findorfail($id);

        return view('cms.softCopyParcel.view', compact('data'));
    }

    public function delete($id)
    {

        try {
            SoftCopyParcel::findorfail($id)->delete();
            return redirect()->route('cms.softCopyParcel.index')->with('success', 'Parcel Deleted Successfully');
        } catch (\Exception $th) {
            return back()->with('error', $th->getMessage())->withInput();
        }
    }
}
