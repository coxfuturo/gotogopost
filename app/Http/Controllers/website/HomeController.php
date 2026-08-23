<?php

namespace App\Http\Controllers\website;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Franchise;
use App\Models\FranchiseBag;
use App\Models\E2HTrackOrder;
use App\Models\MessageFromWebsites;
use  App\Http\Controllers\franchise\RateCalculator;
use DB;
use LDAP\Result;
use Illuminate\Support\Facades\Log;

use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\DeliveryBoy;
use App\Models\PickupDetails;
use App\Models\Pincode;

use Illuminate\Support\Facades\Mail as MailFacade;
use Config;

class HomeController extends Controller

{

    public function index()

    {
        return view('website.index');
    }



    public function getPinCodes(Request $request, RateCalculator $rateCalculator)
    {
        $state = trim(strtolower($request->state));
        $city = trim(strtolower($request->city));
        $pincode = trim($request->pincode);

        $data = [];

        // Step 1: Search by Pincode
        $franchisesByPincode = Franchise::where('pincode', $pincode)
            ->where('status', 1)
            ->get();

        $data = $this->formatFranchiseData($franchisesByPincode, $rateCalculator, $pincode);

        if (count($data) >= 5) {
            return response()->json($data);
        }

        // Step 2: Search by City
        $franchisesByCity = Franchise::whereRaw('LOWER(TRIM(city)) = ?', [$city])
            ->where('status', 1)
            ->get();

        $data = array_merge($data, $this->formatFranchiseData($franchisesByCity, $rateCalculator, $pincode));

        if (count($data) >= 5) {
            return response()->json(array_slice($data, 0, 5));
        }

        // Step 3: Search by State
        $franchisesByState = Franchise::whereRaw('LOWER(TRIM(state)) = ?', [$state])
            ->where('status', 1)
            ->get();

        $data = array_merge($data, $this->formatFranchiseData($franchisesByState, $rateCalculator, $pincode));

        // Step 4: Return at least 5 records
        return response()->json(array_slice($data, 0, max(5, count($data))));
    }

    // Helper function to format and sort franchise data
    private function formatFranchiseData($franchises, $rateCalculator, $pincode)
    {
        $data = [];

        foreach ($franchises as $franchise) {
            $data[] = [
                'franchiseInfo' => $franchise,
                'distance' => $rateCalculator->distance($pincode, $franchise->pincode),
            ];
        }

        usort($data, function ($a, $b) {
            return $a['distance']['distance'] <=> $b['distance']['distance'];
        });

        return $data;
    }


    public function pickupDetailsStore(Request $request)
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

            $franchiseId = $request->franchiseID;

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



    public function about()

    {

        return view('website.about');
    }

    public function contact()

    {

        return view('website.contact');
    }


    public function services()

    {

        return view('website.services');
    }

    public function ocean()

    {

        return view('website.ocean_freight_forwarding');
    }

    public function insta()

    {

        return view('website.insta');
    }

    public function ecommerce()

    {

        return view('website.ecommerce');
    }

    public function logistic()
    {
        return view('website.logistic');
    }


    public function air()

    {

        return view('website.air_freight_forwarding');
    }

    public function shipment()

    {

        return view('website.track');
    }


    public function faq()

    {

        return view('website.faq');
    }

    public function privacyPolicy()

    {
        return view('website.privacy_policy');
    }

    public function agreement()

    {
        return view('website.agreement');
    }

    public function terms()

    {

        return view('website.terms');
    }

    public function roadFreight()

    {

        return view('website.road_freight');
    }

    public function login()

    {

        return view('website.login');
    }

    public function register()

    {

        return view('website.register');
    }

    public function deleteUser()

    {

        return view('website.user-delete');
    }

    public function deleteUserAccount(Request $request)
    {
        Log::info('ddddddd');
        $request->validate([
            'phone' => 'required|string',
            'password' => 'required|string',
        ]);

        // Find user by phone number
        $user = User::where('phone', $request->phone)->first();


        if (!$user) {
            Log::info('user not found');
            return response()->json(['success' => false, 'message' => 'User not found.'], 404);
        }

        // Verify password
        if (!Hash::check($request->password, $user->password)) {
            Log::info('password not match');
            return response()->json(['success' => false, 'message' => 'Incorrect password.'], 401);
        }

        // Delete the user account
        $user->delete();
        Log::info('Data Deleted');
        return response()->json(['success' => true, 'message' => 'Your account has been deleted successfully.'], 200);
    }

    public function trackOrder(Request $request)
    {
       
        $trackOrder = $request->trackOrder;

        // Get Tracking and Booking Models (These should return class names, not objects)
        $TrackingModel = FranchiseBag::getTrackingModelByBarcode($trackOrder);

        $BookingModel = FranchiseBag::getBookingModelByBarcode($trackOrder);

        if (is_null($BookingModel)) {
            return view('website.trackOrder', [
                'trackingDetails' => [],
                'bookingDetails' => []
            ]);
        }

        $serviceTypeNo = FranchiseBag::getServiceTypeFromModel($BookingModel);

        if ($BookingModel == "App\Models\Mail") {
            $serviceTypeNo = 9;
        }

        $serviceTypeName = FranchiseBag::getServiceType($serviceTypeNo);

        // Debugging: Check if class names are valid
        if (!$TrackingModel || !$BookingModel) {
            return back()->with('error', 'Invalid barcode or model not found.');
        }

        // Ensure that the returned values are actually class names
        if (!is_string($TrackingModel) || !class_exists($TrackingModel)) {
            return back()->with('error', 'Tracking model does not exist.');
        }

        // Fetch tracking details
        if ($serviceTypeNo == 9) {
            $keyToSearch = "mail_code";
            $trackingDetails = $TrackingModel::where($keyToSearch, $trackOrder)
                ->with([
                    'sourceFranchise',
                    'user',
                    'destinationFranchise',
                    'mail' => function ($query) {
                        $query->with('recipients');
                    },
                    'deliveryBoy'
                ])
                ->first();
        } else {

            $keyToSearch = "barcode_no";
            $trackingDetails = $TrackingModel::where($keyToSearch, $trackOrder)->first();
        }


        $bookingDetails = $BookingModel::where($keyToSearch, $trackOrder)->first();


        // Check if data is found
        if (!$bookingDetails && !$trackingDetails) {
            return back()->with('error', 'No tracking details found.');
        }

        return view('website.trackOrder', compact('trackingDetails', 'bookingDetails', 'serviceTypeName'));
    }


    public function e2hTrackOrder(Request $request)
    {
        $trackOrder = $request->trackOrder;

        $trackingDetails = E2HTrackOrder::where('mail_code', $trackOrder)
            ->with([
                'sourceFranchise',
                'destinationFranchise',
                'mail' => function ($query) {
                    $query->with('recipients');
                },
                'deliveryBoy'
            ])
            ->first();

        if ($trackingDetails == null) {

            $trackingDetails = [];
        }
        return response()->json([
            'success' => true,
            'message' => 'Data retrieved successfully',
            'trackingDetails' => $trackingDetails,

        ]);
    }

    public function franchiseByCode(Request $request)
    {
        // Request se barcode lo
        $trackOrder = $request->trackOrder;

        // Barcode ka corresponding model nikalne ka function call karo
        $BookingModel = FranchiseBag::getBookingModelByBarcode($trackOrder);

        if ($BookingModel == "App\Models\Mail") {
            $searchValue = "mail_code";
        } else {
            $searchValue = "barcode_no";
        }
        // Agar model mila to uss model ka data fetch kar ke return karo
        if ($BookingModel) {
            $data = $BookingModel::where($searchValue, $trackOrder)->get()->map(function ($item) {
                return [
                    'id' => $item->id,
                    'barcode_no' => $item->barcode_no,
                    'franchise-name' => $item->franchise->name,
                    'franchise-mobile' => $item->franchise->mobile,
                ];
            });

            return response()->json([
                'success' => true,
                'message' => 'Data retrieved successfully',
                'data' => $data
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'No data found for this barcode',
            'data' => []
        ], 404);
    }

    public function franchiseByPhone(Request $request)
    {
        // Request se number lo
        $number = (string) $request->number; // Ensure it's a string

        // Booking model identify karo
        $BookingModel = FranchiseBag::getBookingModelByNumber($number);

        if (!$BookingModel) {
            return response()->json([
                'success' => false,
                'message' => 'No data found for this number',
                'data' => null
            ], 404);
        }

        // Correct search column
        $searchValue = ($BookingModel == \App\Models\Recipient::class) ? 'recipient_phone' : 'pickup_mobile';


        // Model ka sirf pehla record fetch karo
        $item = $BookingModel::where($searchValue, $number)->latest('created_at')->first();

        if (!$item) {
            return response()->json([
                'success' => false,
                'message' => 'No data found for this number',
                'data' => null
            ], 404);
        }

        // Format single record
        $data = [
            'id' => $item->id,
            'pickup_mobile' => $item->pickup_mobile ?? null,
            'franchise-name' => $item->franchise->name ?? 'N/A',
            'franchise-mobile' => $item->franchise->mobile ?? 'N/A',
        ];

        return response()->json([
            'success' => true,
            'message' => 'Data retrieved successfully',
            'data' => $data
        ]);
    }



    public function franchiseByPin(Request $request)
    {
        // Request se pincode lo
        $pincode = $request->pincode;

        // Pincode ke basis par Franchise nikalne ka query
        $franchise = Franchise::where("pincode", $pincode)->first();

        if (!$franchise) {
            return response()->json([
                'success' => false,
                'message' => 'No franchise found for this pincode',
                'barcode_no' => $pincode,
            ], 200);
        }

        return response()->json([
            'success' => true,
            'barcode_no' => $pincode,
            'franchise-name' => $franchise->name,
            'franchise-mobile' => $franchise->mobile,
        ], 200);
    }



    public function store(Request $request)
    {

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:message_from_websites,email',
            'phone' => 'required|digits_between:10,15',
            'option' => 'nullable|string',
            'message' => 'nullable|string',
        ]);
        $website_message = new MessageFromWebsites();
        $website_message->name = $request->name;
        $website_message->email = $request->email;
        $website_message->phone = $request->phone;
        $website_message->option = $request->option;
        $website_message->message = $request->message;
        $website_message->save();

        return response()->json(['success' => true, 'message' => 'Contact form submitted successfully!!']);

        // return response()->json(['success' => 'Contact form submitted successfully!']);
    }

    public function deleteDelivery()

    {

        return view('website.delivery-delete');
    }

    public function deleteDeliveryAccount(Request $request)
    {
        $this->validate($request, [
            'email' => 'required',
            'password' => 'required'
        ], [
            'email.required' => 'The email field is required.',
            'password.required' => 'The password field is required.'
        ]);


        // Find user by generated_id
        $delboy = DeliveryBoy::where('generated_id', $request->get('email'))->where('status', 1)->first();

        // return response()->json(['success' => true, 'message' => $delboy], 200);
        if (!$delboy) {

            Log::info('User not found for username: ' . $request->get('email'));
            return response()->json(['success' => false, 'message' => 'User not found.'], 404);
        }

        // Verify password
        if (!Hash::check($request->password, $delboy->password)) {
            Log::info('Password mismatch for username: ' . $request->get('email'));
            return response()->json(['success' => false, 'message' => 'Incorrect password.'], 401);
        }

        // Mark the user as inactive

        $delboy->status = 2;
        $delboy->save();
        Log::info('Account marked as inactive for username: ' . $request->get('email'));

        return response()->json(['success' => true, 'message' => 'Your account has been deleted successfully.'], 200);
    }



    public function testMail(Request $request)
    {
        // Config::set('mail.mailers.smtp.host', 'smtp.gmail.com');
        // Config::set('mail.mailers.smtp.port', 587);
        // Config::set('mail.mailers.smtp.username', 'snehalsharan10@gmail.com');
        // Config::set('mail.mailers.smtp.password', 'aipn fdol xxjb rshv');
        // Config::set('mail.mailers.smtp.encryption', 'tls');
        // Config::set('mail.from.address', 'deferfe1214@gmail.com');
        // Config::set('mail.from.name', 'gotogopost');

        $message1 = 'with custom config This is a test email from Laravel';
        //$message2 = 'with gotogo config This is a test email from Laravel';

        MailFacade::raw($message1, function ($message) {
            $message->to('fuloriadeepak999@gmail.com')
                ->subject('Test Mail');
        });

        return "Email sent successfully!";
    }



    public function nearestfranchiseByPincode($pincode, RateCalculator $rateCalculator)
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

    public function registerPackage(){
       
        return view('website.package');
    }
}
