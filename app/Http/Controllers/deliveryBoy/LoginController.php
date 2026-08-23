<?php



namespace App\Http\Controllers\deliveryBoy;



use App\Http\Controllers\Controller;
use App\Models\Franchise;
use App\Models\DeliveryBoy;
use App\Models\FranchiseBag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\GotogoSpeedPostParcel;
use App\Models\GotogoSuperFastParcel;
use App\Models\GotogoBusinessParcel;
use App\Models\GotogoRegisteredParcel;
use App\Models\IndiaPostSpeedPostParcel;
use App\Models\DeliveryBoyKyc;
use App\Models\FranchiseKyc;
use App\Models\IndiaPostBusinessParcel;
use App\Models\IndiaPostRegisteredParcel;
use App\Http\Controllers\admin\DeliveryBoyDailyBookingReportController;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use App\Mail\RegistrationMail;
use App\Mail\ForgotPasswordMail;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Notifications\SMSNotification;



class LoginController extends Controller

{

    public function __construct()

    {

        $this->middleware(function ($request, $next) {

            if (Auth::guard('delboy')->check()) {

                return redirect()->route('deliveryBoy.dashboard');
            }



            return $next($request);
        })->only('login');
    }

    public function generateRandomNumber()
    {
        do {
            $randomNumber = rand(1, 99999);
            $formattedNumber = str_pad($randomNumber, 5, '0', STR_PAD_LEFT);
            $exists = DeliveryBoy::where('delivery_boy_no', $formattedNumber)->exists();
        } while ($exists);

        return $formattedNumber;
    }

    public function login(Request $request)

    {

        if ($request->method() == 'POST') {

            $this->validate($request, [

                'username' => 'required',

                'password' => 'required'

            ], [

                'username.required' => 'The username field is required.',

            ]);



            if ($delboy = DeliveryBoy::where('generated_id', $request->get('username'))->first()) {

                if (Hash::check($request->get('password'), $delboy->password)) {

                    Auth::guard('delboy')->login($delboy, $request->get('remember'));

                    return redirect()->route('deliveryBoy.dashboard');
                }
            }


            // if (Auth::guard('delboy')->attempt([
            //     'generated_id' => $request->get('username'),
            //     'password' => $request->get('password')
            // ], $request->get('remember'))) {

            //     return redirect()->route('deliveryBoy.dashboard');
            // }



            return redirect()->back()->with(['error' => 'Invalid Username or Password'])->withInput();
        }

        return view('deliveryBoy.auth.login');
    }


    public function sendOtp(Request $request)
    {
        if ($request->method() == 'POST') {

            $this->validate($request, [
                'username' => 'required',
            ], [
                'username.required' => 'The username field is required.',
            ]);

            if ($franchise = DeliveryBoy::where('generated_id', $request->get('username'))->first()) {

                $otp =  rand(10000, 99999);
                $franchise->verification_otp = $otp;
                $franchise->save();

                $notification = new SMSNotification($franchise->mobile, 'OTP', [$otp]);
                $response = $notification->sendMessage();

                Mail::to($franchise->email)->send(new ForgotPasswordMail([
                    'otp' =>  $franchise->verification_otp,
                ]));


                return view('deliveryBoy.auth.verifyOtp', ['otp' => $franchise->verification_otp, 'username' => $request->get('username')]);
            }

            return redirect()->back()->with(['error' => 'Invalid Username or Password'])->withInput();
        }

        return view('deliveryBoy.auth.sendOtp');
    }


    public function verifyOtp(Request $request)
    {
        if ($request->method() == 'POST') {
            // Validate the input to ensure the OTP is provided
            $this->validate($request, [
                'otp' => 'required|array',
            ], [
                'otp.required' => 'The OTP field is required.',
            ]);

            // Convert the OTP array into a string
            $enteredOtp = implode('', $request->get('otp'));

            // Find the franchise by the username (generated_id)
            if ($franchise = DeliveryBoy::where('generated_id', $request->get('username'))->first()) {
                // Check if the entered OTP matches the saved OTP
                if ($franchise->verification_otp === $enteredOtp) {
                    Auth::guard('delboy')->login($franchise);
                    // Redirect to the dashboard
                    return redirect()->route('deliveryBoy.dashboard');
                } else {
                    return redirect()->route('deliveryBoy.verifyOtp')->with([
                        'error' => 'Invalid OTP',
                        'otp_error' => 'Invalid OTP', // Separate key for OTP error
                        'username' => $request->get('username'), // Ensure username is passed back
                        'otp' => $franchise ? $franchise->verification_otp : null, // Pass OTP if franchise is found
                    ])->withInput();
                }
            }

            // If no user is found, return with an error message
            return redirect()->route('deliveryBoy.verifyOtp')->with(['error' => 'Invalid Username or Password'])->withInput();
        }

        return view('deliveryBoy.auth.verifyOtp', ['username' => null]);
    }



    public function register(Request $request)
    {
        // return $franchise = DeliveryBoyKyc::all();
         $franchise = Franchise::where('status', 1)->get();

        if ($request->method() == 'POST') {
            $this->validate(
                $request,
                [
                    'name' => 'required',
                    'father_name' => 'required',
                    'mobile' => 'required|digits:10|unique:delivery_boys,mobile',
                    'email' => 'required|email|unique:delivery_boys,email',
                    'pincode' => 'required|digits:6',
                    'city' => 'required',
                    'district' => 'required',
                    'state' => 'required',
                    'address' => 'required',
                    'franchise_id' => 'required',
                ]
            );

            // Collect only the necessary data for registration
            $registerData = $request->only([
                'name',
                'father_name',
                'mobile',
                'email',
                'pincode',
                'city',
                'district',
                'state',
                'address',
                'generated_id',
                'franchise_id',
                'adhar_card',
                'age',
                'gender',
            ]);

            try {
                $mobile = $request->mobile;
                $otp = rand(10000, 99999);

                // Prepare all data for registration, including files
                $allData = $registerData;

                $generatedId = $request->generated_id;

                if ($request->hasFile('adhar_front_img')) {
                    $file1 = $request->file('adhar_front_img');
                    $extension1 = $file1->getClientOriginalName();
                    $img1 = time() . '_' . $extension1;
                    $file1->move(public_path('tenancy/assets/delboy/' . $generatedId), $img1);
                    $allData['adhar_front_img'] = $img1;
                }

                if ($request->hasFile('adhar_back_img')) {
                    $file2 = $request->file('adhar_back_img');
                    $extension2 = $file2->getClientOriginalName();
                    $img2 = time() . '_' . $extension2;
                    $file2->move(public_path('tenancy/assets/delboy/' . $generatedId), $img2);
                    $allData['adhar_back_img'] = $img2;
                }

                if ($request->hasFile('photo')) {
                    $file5 = $request->file('photo');
                    $extension5 = $file5->getClientOriginalName();
                    $img5 = time() . '_' . $extension5;
                    $file5->move(public_path('tenancy/assets/delboy/' . $generatedId), $img5);
                    $allData['photo'] = $img5;
                }

                if ($request->hasFile('drivary_lances_img')) {
                    $file5 = $request->file('drivary_lances_img');
                    $extension5 = $file5->getClientOriginalName();
                    $img5 = time() . '_' . $extension5;
                    $file5->move(public_path('tenancy/assets/delboy/' . $generatedId), $img5);
                    $allData['drivary_lances_img'] = $img5;
                }

                if ($request->hasFile('pan_img')) {
                    $file5 = $request->file('pan_img');
                    $extension5 = $file5->getClientOriginalName();
                    $img5 = time() . '_' . $extension5;
                    $file5->move(public_path('tenancy/assets/delboy/' . $generatedId), $img5);
                    $allData['pan_img'] = $img5;
                }

                if ($request->hasFile('video_kyc')) {
                    $file7 = $request->file('video_kyc');
                    $extension7 = $file7->getClientOriginalName();
                    $img7 = time() . '_' . $extension7;
                    $file7->move('tenancy/assets/delboy/' . $generatedId . '/', $img7);
                    $allData['video_kyc'] = $img7;
                }


                \Cache::put('register_data_' . $mobile, $allData, now()->addMinutes(10));
                \Cache::put('otp_' . $mobile, $otp, now()->addMinutes(10));

                // Call SMSNotification with correct parameters
                $notification = new SMSNotification($mobile, 'OTP', [$otp]);
                $response = $notification->sendMessage();

                // return view('deliveryBoy.auth.phoneNumberVerifyOtp', ['otp' => $otp, 'mobile' => $mobile]);
                return response()->json([
                    'success' => true,
                    'message' => 'otp sent successfully',
                    'data' => [
                        'otp' => $otp,
                        'mobile' => $mobile,
                    ]
                ]);
            } catch (\Exception $th) {
                return back()->with('error', $th->getMessage())->withInput();
            }
        }

        return view('deliveryBoy.auth.register', ['franchise' => $franchise]);
    }

  
    public function otpViewPage(Request $request)
    {
        return view('deliveryBoy.auth.phoneNumberVerifyOtp', ['otp' => $request->otp, 'mobile' => $request->mobile]);
    }

    public function verifyPhoneNumberOtp(Request $request)
    {
        $franchise = Franchise::where('status', 1)->get();

        if ($request->method() == 'POST') {
            // Get OTP entered by the user
            $enteredOtp = implode('', $request->get('otp'));

            // Get mobile number from the request (it should be sent via hidden input or session)
            $mobile = $request->get('mobile');
            if (!$mobile) {
                return back()->with('error', 'Mobile number missing.');
            }

            // Retrieve the OTP and register data from the cache
            $cachedOtp = \Cache::get('otp_' . $mobile);
            $registerData = \Cache::get('register_data_' . $mobile);



            // Check if OTP or register data is missing or expired
            if (!$cachedOtp || !$registerData) {
                return back()->with('error', 'OTP expired or invalid, please try again.');
            }

            // Verify OTP
            if ($cachedOtp != $enteredOtp) {
                return back()->with('error', 'Invalid OTP, please enter the correct OTP.');
            }

            try {
                // Create new DeliveryBoy record
                $post = new DeliveryBoy();
                $post->name = $registerData['name'];
                $post->father_name = $registerData['father_name'];
                $post->mobile = $registerData['mobile'];
                $post->email = $registerData['email'];
                $post->pincode = $registerData['pincode'];
                $post->city = $registerData['city'];
                $post->district = $registerData['district'];
                $post->state = $registerData['state'];
                $post->address = $registerData['address'];
                $post->age = $registerData['age'];
                $post->gender = $registerData['gender'];
                $post->wallet_balance = 0;
                $post->generated_id = $registerData['generated_id'];
                $post->delivery_boy_no = str_pad(rand(1, 999999), 6, '0', STR_PAD_LEFT);
                $numbers = str_shuffle('0123456789');
                $password = substr($numbers, 0, 10);
                $post->franchise_id = $registerData['franchise_id'];
                $post->password = Hash::make($password);
                $post->save();

                // Save KYC information
                if ($post) {
                    $kyc = new DeliveryBoyKyc;
                    $kyc->delivery_boy_id = $post->id;
                    $kyc->adhar_card = $registerData['adhar_card'];

                    // Handle file uploads for KYC
                    if (isset($registerData['adhar_front_img'])) {

                        $kyc->adhar_front_img = $registerData['adhar_front_img'];
                    }

                    if (isset($registerData['adhar_back_img'])) {

                        $kyc->adhar_back_img = $registerData['adhar_back_img'];
                    }

                    if (isset($registerData['photo'])) {

                        $kyc->photo = $registerData['photo'];
                    }
                    if (isset($registerData['drivary_lances_img'])) {

                        $kyc->drivary_lances_img = $registerData['drivary_lances_img'];
                    }
                    if (isset($registerData['pan_img'])) {

                        $kyc->pan_img = $registerData['pan_img'];
                    }

                    if (isset($registerData['video_kyc'])) {
                        $kyc->video_kyc = $registerData['video_kyc'];
                    }

                    $kyc->save();

                    // If KYC fails, delete the DeliveryBoy record
                    if (!$kyc) {
                        $post->delete();
                    }
                }

                // Generate and cache a new OTP for the next step if needed
                $otp = rand(10000, 99999);
                \Cache::put('otp_' . $request->mobile, $otp, now()->addMinutes(10));

                // Send registration email with the generated password
                Mail::to($registerData['email'])->send(new RegistrationMail([
                    'username' => $post->generated_id,
                    'password' => $password,
                    'route' => route('deliveryBoy.login')
                ]));

                return redirect()->back()->with('success', 'Delivery Boy created successfully! ID: ' . $post->generated_id . ' Password: ' . $password);
            } catch (\Exception $th) {
                return back()->with('error', $th->getMessage())->withInput();
            }
        }

        return view('deliveryBoy.auth.register', ['franchise' => $franchise]);
    }

    public function resendPhoneNumberOtp(Request $request)
    {
        if ($request->method() == 'POST') {

            $this->validate($request, [
                'username' => 'required',
            ], [
                'username.required' => 'The username field is required.',
            ]);

            if ($franchise = DeliveryBoy::where('generated_id', $request->get('username'))->first()) {

                $franchise->verification_otp = rand(10000, 99999);
                $franchise->save();

                Mail::to($franchise->email)->send(new ForgotPasswordMail([
                    'otp' =>  $franchise->verification_otp,
                ]));


                return view('deliveryBoy.auth.verifyOtp', ['otp' => $franchise->verification_otp, 'username' => $request->get('username')]);
            }

            return redirect()->back()->with(['error' => 'Invalid Username or Password'])->withInput();
        }

        return view('deliveryBoy.auth.sendOtp');
    }



    public function logout()

    {

        Auth::guard('delboy')->logout();

        return redirect()->route('deliveryBoy.login');
    }





    public function profile(Request $request)
    {

        $id = Auth::guard('delboy')->user()->id;
        $frachinse = DeliveryBoy::with('kyc')->findOrFail($id);

        // return $frachinse;
        $models = [
            GotogoSpeedPostParcel::getServiceType(1) => GotogoSpeedPostParcel::class,
            GotogoSuperFastParcel::getServiceType(2) => GotogoSuperFastParcel::class,
            GotogoBusinessParcel::getServiceType(3) => GotogoBusinessParcel::class,
            GotogoRegisteredParcel::getServiceType(4) => GotogoRegisteredParcel::class,
            IndiaPostSpeedPostParcel::getServiceType(5) => IndiaPostSpeedPostParcel::class,
            IndiaPostBusinessParcel::getServiceType(6) => IndiaPostBusinessParcel::class,
            IndiaPostRegisteredParcel::getServiceType(7) => IndiaPostRegisteredParcel::class,
        ];

        // Define an array to map service type names to their corresponding service numbers
        $serviceNumbers = [
            GotogoSpeedPostParcel::getServiceType(1) => GotogoSpeedPostParcel::SERVICE_TYPE_GOTO_POST_SPEED,
            GotogoSuperFastParcel::getServiceType(2) => GotogoSuperFastParcel::SERVICE_TYPE_GOTO_POST_SUPERFAST,
            GotogoBusinessParcel::getServiceType(3) => GotogoBusinessParcel::SERVICE_TYPE_GOTO_POST_BUSINESS_PARCEL,
            GotogoRegisteredParcel::getServiceType(4) => GotogoRegisteredParcel::SERVICE_TYPE_GOTO_POST_REGISTERED,
            IndiaPostSpeedPostParcel::getServiceType(5) => IndiaPostSpeedPostParcel::SERVICE_TYPE_INDIA_POST_SPEED,
            IndiaPostBusinessParcel::getServiceType(6) => IndiaPostBusinessParcel::SERVICE_TYPE_INDIA_POST_BUSINESS,
            IndiaPostRegisteredParcel::getServiceType(7) => IndiaPostRegisteredParcel::SERVICE_TYPE_INDIA_POST_REGISTERED,
        ];

        $allParcels = collect();
        $formattedDate = Carbon::today()->format('Y-m-d');

        foreach ($models as $serviceType => $model) {
            $serviceNumber = $serviceNumbers[$serviceType] ?? null;
            $bagfromfranchise = FranchiseBag::where('service_type', $serviceNumber)
                ->where('delivery_boy_id',  $id)
                ->pluck('id')
                ->toArray();

            $parcels = $model::where(function ($query) use ($formattedDate, $bagfromfranchise) {
                $query->whereDate('delivered_date', '=', $formattedDate)
                    ->whereIn('source_franchise_bag_id', $bagfromfranchise)
                    ->orWhereIn('destination_franchise_bag_id', $bagfromfranchise)
                    ->orWhere(function ($subQuery) use ($formattedDate) {
                        $subQuery->where('otp', '0')
                            ->whereHas('cancelDelivery', function ($query) use ($formattedDate) {
                                $query->whereDate('created_at', '=', $formattedDate);
                            });
                    });
            })
                ->with('cancelDelivery')
                ->get();

            $parcels->each(function ($parcel) use ($serviceType, $serviceNumber) {
                $parcel->service_type = $serviceType;
                $parcel->service_number = $serviceNumber;
            });

            $allParcels = $allParcels->merge($parcels);
        }

        // return $parcel;
        $dailybookingdata = DeliveryBoyDailyBookingReportController::eachDeliveyBoy($id);


        if ($request->date && $request->type === "parcel") {

            $formattedDate =  Carbon::parse($request->date)->format('Y-m-d');
            $allParcels = collect();

            foreach ($models as $serviceType => $model) {
                $serviceNumber = $serviceNumbers[$serviceType] ?? null;
                $bagfromfranchise = FranchiseBag::where('service_type', $serviceNumber)
                    ->where('delivery_boy_id',  $id)
                    ->pluck('id')
                    ->toArray();

                $parcels = $model::where(function ($query) use ($formattedDate, $bagfromfranchise) {
                    $query->whereDate('delivered_date', '=', $formattedDate)
                        ->whereIn('source_franchise_bag_id', $bagfromfranchise)
                        ->orWhereIn('destination_franchise_bag_id', $bagfromfranchise)
                        ->orWhere(function ($subQuery) use ($formattedDate) {
                            $subQuery->where('otp', '0')
                                ->whereHas('cancelDelivery', function ($query) use ($formattedDate) {
                                    $query->whereDate('created_at', '=', $formattedDate);
                                });
                        });
                })
                    ->with('cancelDelivery')
                    ->get(['barcode_no', 'pickup_name', 'pickup_pincode', 'consignee_name', 'consignee_pincode']);

                $parcels->each(function ($parcel) use ($serviceType, $serviceNumber) {
                    $parcel->service_type = $serviceType;
                    $parcel->service_number = $serviceNumber;
                });

                $allParcels = $allParcels->merge($parcels);
            }

            $html = '';
            $i = 0; // Initialize index

            foreach ($allParcels as $item) {
                $html .= '<tr>';
                $html .= '<td>' . ++$i . '</td>'; // Increment and use index
                $html .= '<td>' . htmlspecialchars($item->service_type) . '</td>';
                $html .= '<td>' . htmlspecialchars($item->pickup_name) . '</td>';
                $html .= '<td>' . htmlspecialchars($item->pickup_pincode) . '</td>';
                $html .= '<td>' . htmlspecialchars($item->consignee_name) . '</td>';
                $html .= '<td>' . htmlspecialchars($item->consignee_pincode) . '</td>';
                $html .= '<td>';
                $html .= '<div class="table-actions d-flex">';
                $html .= '<a class="delete-table me-2" href="' . route('admin.parcel.view', ['id' => $item->barcode_no, 'service_type' => $item->service_number]) . '">';
                $html .= '<img src="' . asset('admin/assets/img/icons/eye.svg') . '" alt="Eye Icon">';
                $html .= '</a>';
                $html .= '</div>';
                $html .= '</td>';
                $html .= '</tr>';
            }

            return response()->json([
                'status' => 200,
                'message' => 'Filter data successful',
                'html' => $html // Include the HTML in the response
            ]);
        }


        if ($request->date && $request->type === "bookings") {

            $dailybookingdata = DeliveryBoyDailyBookingReportController::eachDeliveyBoyfilterbyDate($id, $request->date);
            return response()->json(['status' => 200, 'message' => 'filter data succesfull', 'data' => $dailybookingdata]);
        }

        return view(
            'deliveryBoy.auth.profile',
            [
                'data' => $frachinse,
                'parcel' => $allParcels,
                'bookingData' => $dailybookingdata
            ]
        );
    }



    public function update(Request $request, $id)
    {

        // return $request;

        if ($request->method() == 'POST') {

            //return $request;
            $this->validate(
                $request,
                [
                    'name' => 'required',
                    'mobile' => 'required|digits:10',
                    'email' => 'required|email',
                    'pincode' => 'required|digits:6',
                    'city' => 'required',
                    'district' => 'required',
                    'state' => 'required',
                    'address' => 'required',
                    'adhar_card' => 'required',
                ],
            );

            try {

                $date = Carbon::now();
                $post = DeliveryBoy::find($id);
                $post->name = $request->name;
                $post->father_name = $request->father_name;
                $post->mobile = $request->mobile;
                $post->email = $request->email;
                $post->pincode = $request->pincode;
                $post->city = $request->city;
                $post->district = $request->district;
                $post->state = $request->state;
                $post->address = $request->address;
                $post->latitude = $request->latitude;
                $post->longitude = $request->longitude;
                $post->age = $request->age;
                $post->gender = $request->gender;

                if (!empty($request->password)) {
                    $post->password = Hash::make($request->password);
                }

                $post->save();

                if ($post) {
                    $kyc = DeliveryBoyKyc::where('delivery_boy_id', $id)->first();
                    $post->adhar_card = $request->adhar_card;
                    $kyc->pan_card = $request->pan_card;
                    $kyc->ifsc_code = $request->ifsc_code;
                    $kyc->bank_name = $request->bank_name;
                    $kyc->branch_name = $request->branch_name;
                    $kyc->account_number = $request->account_number;
                    $kyc->approved_at = $date;
                    if ($request->hasFile('adhar_front_img')) {
                        $file1 = $request->file('adhar_front_img');
                        $extension1 = $file1->getClientOriginalName();
                        $img1 = time() . '_' . $extension1;
                        $file1->move('tenancy/assets/delboy/' . $post->generated_id . '/', $img1);
                        $kyc->adhar_front_img = $img1;
                    }
                    if ($request->hasFile('adhar_back_img')) {
                        $file2 = $request->file('adhar_back_img');
                        $extension2 = $file2->getClientOriginalName();
                        $img2 = time() . '_' . $extension2;
                        $file2->move('tenancy/assets/delboy/' . $post->generated_id . '/', $img2);
                        $kyc->adhar_back_img = $img2;
                    }

                    if ($request->hasFile('pan_img')) {
                        $file3 = $request->file('pan_img');
                        $extension3 = $file3->getClientOriginalName();
                        $img3 = time() . '_' . $extension3;
                        $file3->move('tenancy/assets/delboy/' . $post->generated_id . '/', $img3);
                        $kyc->pan_img = $img3;
                    }

                    if ($request->hasFile('photo')) {
                        $file5 = $request->file('photo');
                        $extension5 = $file5->getClientOriginalName();
                        $img5 = time() . '_' . $extension5;
                        $file5->move('tenancy/assets/delboy/' . $post->generated_id . '/', $img5);
                        $kyc->photo = $img5;
                    }

                    if ($request->hasFile('drivary_lances_img')) {
                        $file5 = $request->file('drivary_lances_img');
                        $extension5 = $file5->getClientOriginalName();
                        $img5 = time() . '_' . $extension5;
                        $file5->move(public_path('tenancy/assets/delboy/' . $post->generated_id), $img5);
                        $allData['drivary_lances_img'] = $img5;
                    }

                    if ($request->hasFile('pan_img')) {
                        $file5 = $request->file('pan_img');
                        $extension5 = $file5->getClientOriginalName();
                        $img5 = time() . '_' . $extension5;
                        $file5->move(public_path('tenancy/assets/delboy/' . $post->generated_id), $img5);
                        $allData['pan_img'] = $img5;
                    }

                    if ($request->hasFile('video_kyc')) {
                    $file7 = $request->file('video_kyc');
                    $extension7 = $file7->getClientOriginalName();
                    $img7 = time() . '_' . $extension7;
                    $file7->move('tenancy/assets/delboy/' . $generatedId . '/', $img7);
                    $kyc->photo = $img7;
                }

                    $kyc->save();
                    if (!$kyc) {
                        $post->delete();
                    }
                }
                return back()->with('success', 'Delivery Boy updated successfully!');
            } catch (\Exception $th) {
                return back()->with('error', $th->getMessage())->withInput();
            }
        }

        return view('franchise.auth.register');
    }

    public function franchise_state(Request $request)
{
    $franchises = Franchise::where('state', $request->state)->get();

    if ($franchises->isEmpty()) {
        $options = '<option value="" disabled selected>No franchises found</option>';
    } else {
        $options = '<option value="" disabled selected>-- Select Your Franchise --</option>';
        foreach ($franchises as $item) {
            $options .= '<option value="' . $item->id . '">' . $item->name . '</option>';
        }
    }

    return response()->json([
        'success' => true,
        'html' => $options
    ]);
}

}
