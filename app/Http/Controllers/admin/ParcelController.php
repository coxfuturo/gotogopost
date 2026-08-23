<?php



namespace App\Http\Controllers\admin;



use App\Http\Controllers\Controller;

use App\Models\Parcel;

use App\Models\PickupDetails;

use Illuminate\Http\Request;

use Illuminate\Contracts\Support\Renderable;

use Illuminate\Http\RedirectResponse;

use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\Hash;

use App\Models\FranchiseBag;

use App\Models\Pincode;



class ParcelController extends Controller

{

    public function index(Request $request)

    {

        $datas = Parcel::all();



        return view('admin.parcel.index', compact('datas'));
    }



    public function create()

    {

        $pickupDetails = PickupDetails::all();

        return view('admin.parcel.create', ['pickupDetails' => $pickupDetails]);
    }



    public function store(Request $request): Renderable|RedirectResponse

    {



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

            // Create Pickup

            Parcel::create([

                'franchise_id' => Auth::guard('admin')->user()->id,

                'pickup_name' => $request->PickupName,

                'pickup_mobile' => $request->PickupMobile,

                'pickup_email' => $request->PickupEmail,

                'pickup_pincode' => $request->PickupPincode,

                'pickup_city' => $request->PickupCity,

                'pickup_state' => $request->PickupState,

                'pickup_address' => $request->PickupAddress,

                'consignee_name' => $request->ConsigneeName,

                'consignee_mobile' => $request->ConsigneeMobile,

                'consignee_email' => $request->ConsigneeEmail,

                'consignee_pincode' => $request->ConsigneePincode,

                'consignee_city' => $request->ConsigneeCity,

                'consignee_state' => $request->ConsigneeState,

                'consignee_address' => $request->ConsigneeAddress,

                'package_weight' => $request->package_weight,

                'package_length' => $request->package_length,

                'package_width' => $request->package_width,

                'package_height' => $request->package_height,

                'payment_method' => $request->payment_method,

                'order_no' => uniqid(),

            ]);





            return redirect()->route('admin.parcel.index')->with('success', 'New Parcel Added');
        } catch (\Exception $th) {

            return back()->with('error', $th->getMessage());
        }
    }





    public function edit(Request $request, $id)

    {

        $post = Parcel::findOrFail($id);



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



                return redirect()->route('admin.parcel.index')->with('success', 'Parcel updated successfully!');
            } catch (\Exception $th) {

                return back()->with('error', $th->getMessage())->withInput();
            }
        }



        $data = $post;

        $pickupDetails = PickupDetails::all();



        return view('admin.parcel.edit', compact('data', 'pickupDetails'));
    }







    public function view(Request $request, $id)

    {
        $Model = FranchiseBag::getServiceModel($request->service_type);
        $data =  $Model::where('barcode_no', $id)->first();

        $fuel_charge = $data->fuel_charge;
        $pickup_charge = $data->pickup_charge;
        $other_service_charge = $data->other_service_charge;
        $total_payment_amount = $data->payment_amount;
        $net_price = $total_payment_amount / 1.18;
        $amount = $net_price - ($fuel_charge + $pickup_charge + $other_service_charge);
        $gst = number_format($net_price * 0.18, 2, '.', '');
        $net_price_formatted = number_format($net_price, 2, '.', '');


        $rateDetails = [
            'fuel_charge' => $fuel_charge,
            'pickup_charge' => $pickup_charge,
            'other_service_charge' => $other_service_charge,
            'total_payment_amount' => number_format($total_payment_amount, 2, '.', ''),
            'net_price' => $net_price_formatted,
            'amount' => number_format($amount, 2, '.', ''),
            'gst' => $gst,
        ];

        return view('admin.parcel.view', compact('data', 'rateDetails'));
    }



    public function delete($id)

    {



        try {

            Parcel::findorfail($id)->delete();

            return redirect()->route('admin.parcel.index')->with('success', 'Parcel Deleted Successfully');
        } catch (\Exception $th) {

            return back()->with('error', $th->getMessage())->withInput();
        }
    }

    
    public function gotogoPincode()

    {
        
       return $data = Pincode::all();

        return view('admin.parcel.create', ['data' => $data]);
    }
}
