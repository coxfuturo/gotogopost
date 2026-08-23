<?php



namespace App\Http\Controllers\franchise;



use App\Http\Controllers\Controller;

use App\Models\PickupDetails;
use App\Models\ECustomer;
use App\Models\Franchise;
use App\Models\DeliveryBoy;
use App\Models\NoRegisterCustomer;
use Illuminate\Http\Request;

use Carbon\Carbon;
use App\Notifications\PickupPushNotification;

use PDF;

use DB;

class PickupDetailsController extends Controller

{



    public function index(Request $request)
    {
        $searchKey = $request->input('searchKey');
        $fromDate = $request->input('fromDate');
        $toDate = $request->input('toDate');

        $query = PickupDetails::where('franchise_id', Franchise::getFranchiseId())
            ->orderBy('created_at', 'desc')
            ->with('deliveryBoy'); // Load Delivery Boy details

        // Search key filtering
        if ($searchKey) {
            $query->where(function ($q) use ($searchKey) {
                $q->where('name', 'LIKE', "%{$searchKey}%")
                    ->orWhere('email', 'LIKE', "%{$searchKey}%")
                    ->orWhere('phone', 'LIKE', "%{$searchKey}%")
                    ->orWhere('pincode', 'LIKE', "%{$searchKey}%")
                    ->orWhere('city', 'LIKE', "%{$searchKey}%")
                    ->orWhere('state', 'LIKE', "%{$searchKey}%")
                    ->orWhere('address', 'LIKE', "%{$searchKey}%");
            });
        }

        // Date range filtering
        if ($fromDate && $toDate) {
            $fromDate = Carbon::parse($fromDate)->startOfDay();
            $toDate = Carbon::parse($toDate)->endOfDay();
            $query->whereBetween('created_at', [$fromDate, $toDate]);
        } elseif ($fromDate) {
            $fromDate = Carbon::parse($fromDate)->startOfDay();
            $query->whereDate('created_at', '>=', $fromDate);
        } elseif ($toDate) {
            $toDate = Carbon::parse($toDate)->endOfDay();
            $query->whereDate('created_at', '<=', $toDate);
        }

        // Get delivery boys for the franchise
        $franchiseId = Franchise::getFranchiseId();
        $deliveryBoys = DeliveryBoy::where('franchise_id', $franchiseId)->get();

        // Get the filtered results with pagination
        $pickupdetails = $query->paginate(10);

        return view('franchise.pickup-details.index', compact('pickupdetails', 'deliveryBoys'));
    }


    public function details(Request $request)

    {
         
        $pickupDetails = PickupDetails::where('id', $request->id)->first();

        return response()->json($pickupDetails);
    }

    public function codDetails(Request $request)

    {
        if($request->cod == 'cod'){
        $pickupDetails = ECustomer::where('id', $request->id)->first();
        }else{
             $pickupDetails = NoRegisterCustomer::where('id', $request->id)->select('id', 'name', 'phone as mobile', 'email', 'pincode', 'city', 'state', 'address','gst_no')->first();
        }
        return response()->json($pickupDetails);
    }

    // Email
      



    public function store(Request $request)
    {
        try {

            $this->validate($request, [
                'name' => 'required',
                'phone' => 'required',
                'pincode' => 'required',
                'city' => 'required',
                'state' => 'required',
                'address' => 'required',
            ]);

            $franchiseId = $request->franchiseID ?: Franchise::getFranchiseId();

            $requestData = [
                'franchise_id' => $franchiseId,
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'pincode' => $request->pincode,
                'city' => $request->city,
                'state' => $request->state,
                'address' => $request->address,
            ];

            PickupDetails::create($requestData);

            return back()->with('success', 'Pickup details added successfully');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            return back()->with('error', 'Something went wrong! ' . $e->getMessage())->withInput();
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



    // this is for testing only
    public function test()
    {

        $pdf = PDF::loadView('franchise.pickup-details.test2');
        $destinationPath = public_path('tenancy/assets/franchise/Pdf/');
        $fileName = uniqid() . '_parcel_' . '.pdf';
        $filePath = $destinationPath . $fileName;
        $pdf->save($filePath);


        return view('franchise.pickup-details.test');
    }

    public function updateStatus(Request $request, $id)
    {
        $pickupDetail = PickupDetails::findOrFail($id);
        $pickupDetail->status = $request->input('status');
        $pickupDetail->save();

        return redirect()->back()->with('success', 'Status updated successfully.');
    }

    public function sendPickNotificationToDeliveryBoy($pickupDetail, $delivery_boy_id)
    {
        try {
            if (!$pickupDetail) {
                \Log::error("Pickup detail is missing");
                return;
            }

            \Log::info("sendPickNotificationToDeliveryBoy started", [
                'pickup_id' => (string) ($pickupDetail->id ?? 'N/A'),
                'delivery_boy_id' => (string) $delivery_boy_id
            ]);

            // Fetch delivery boy details
            $deliveryBoy = DeliveryBoy::findOrFail($delivery_boy_id);
            $token = $deliveryBoy->fcm_token;

            if (!$token) {
                \Log::warning("FCM token not found for delivery boy", ['delivery_boy_id' => (string) $delivery_boy_id]);
                return;
            }

            \Log::info("FCM token found", ['token' => $token]);

            // Convert all data values to string to prevent FCM error
            $name = isset($pickupDetail->name) ? (string) $pickupDetail->name : 'N/A';
            $phone = isset($pickupDetail->phone) ? (string) $pickupDetail->phone : 'N/A';
            $address = isset($pickupDetail->address) ? (string) $pickupDetail->address : 'N/A';

            // Prepare Notification for a new parcel assignment
            $title = "📦 New Pickup Assigned";
            $body = "🚀 A new pickup request is assigned to you.\n" .
                "👤 Name: {$name}\n" .
                "📞 Phone: {$phone}\n" .
                "📍 Address: {$address}\n" .
                "📅 Date: " . now()->format('M d, Y, h:i A');

            \Log::info("Sending push notification", ['title' => $title, 'body' => $body]);

            // Send Push Notification
            $notification = new PickupPushNotification($token, $title, $body);
            $status = $notification->sendPushNotification();

            \Log::info("Notification status", ['status' => (string) $status]);

            if ($status == 0) {
                return;
            }

            DB::table('delivery_boy_notifications')->insert([
                'delivery_boy_id' => (string) $delivery_boy_id,
                'message' => "New pickup assigned",
                'name' => $name,
                'phone' => $phone,
                'address' => $address,
                'body' => $body,
                'created_at' => now(),
                'updated_at' => now(),
            ]);


            \Log::info("Delivery Boy notification saved in database", [
                'delivery_boy_id' => (string) $delivery_boy_id,
                'pickup_id' => (string) $pickupDetail->id
            ]);
        } catch (\Exception $e) {
            \Log::error("Error in sendPickNotificationToDeliveryBoy", [
                'pickup_id' => (string) ($pickupDetail->id ?? 'N/A'),
                'error' => $e->getMessage(),
                'exception' => $e
            ]);
        }
    }



    public function assignDeliveryBoy(Request $request, $id)
    {


        $pickupDetail = PickupDetails::findOrFail($id);
        $delivery_boy_id = $request->input('deliveryboy_id');
        $pickupDetail->deliveryboy_id = $delivery_boy_id;
        $pickupDetail->save();

        $this->sendPickNotificationToDeliveryBoy($pickupDetail, $delivery_boy_id);

        return redirect()->back()->with('success', 'Delivery boy assigned successfully.');
    }
}
