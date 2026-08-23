<?php

namespace App\Http\Controllers\PPH;

use App\Http\Controllers\Controller;
use App\Models\PPH;
use App\Models\PPHKyc;
use App\Models\PphPayment;
use App\Models\RegistrationPayment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\GotogoSpeedPostParcel;
use App\Models\GotogoSuperFastParcel;
use App\Models\GotogoBusinessParcel;
use App\Models\GotogoRegisteredParcel;
use App\Models\IndiaPostSpeedPostParcel;
use App\Models\IndiaPostBusinessParcel;
use App\Models\IndiaPostRegisteredParcel;
use App\Http\Controllers\admin\PPHDailyBookingReportController;
use App\Models\Franchise;
use Carbon\Carbon;
use App\Models\CMSBag;
use Illuminate\Support\Facades\Mail;
use App\Mail\RegistrationMail;
use App\Mail\ForgotPasswordMail;
use Config;
use Illuminate\Support\Arr;
use Razorpay\Api\Api;
use App\Notifications\SMSNotification;



class LoginController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (Auth::guard('pph')->check()) {
                return redirect()->route('pph.dashboard');
            }

            return $next($request);
        })->only('login');
    }


    public function generateRandomNumber()
    {
        do {
            $randomNumber = rand(1, 99999);
            $formattedNumber = str_pad($randomNumber, 5, '0', STR_PAD_LEFT);
            $exists = PPH::where('pph_no', $formattedNumber)->exists();
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
               $amount = RegistrationPayment::first();

            if ($franchise = PPH::where('generated_id', $request->get('username'))->first()) {
                $payment = PphPayment::where('pph_id', $franchise->id)->where('status', 'completed')->first();
                if (!empty($payment) || $franchise->payment_status == 1) {
                    if (Hash::check($request->get('password'), $franchise->password)) {
                        Auth::guard('pph')->login($franchise, $request->get('remember'));
                        return redirect()->route('pph.dashboard');
                    }
                } else {
                    $amountInPaise = $amount->pph * 100;
                    return view('pph.auth.makePayment', [
                        'pph_id' => $franchise->id,
                        'amount' => $amountInPaise
                    ]);
                }
            }

            if ($delboy = PPH::where('generated_id', $request->get('username'))->first()) {

                if (Hash::check($request->get('password'), $delboy->password)) {

                    Auth::guard('pph')->login($delboy, $request->get('remember'));

                    return redirect()->route('pph.dashboard');
                }
            }

            return redirect()->back()->with(['error' => 'Invalid Username or Password'])->withInput();
        }

        return view('pph.auth.login');
    }

    public function sendOtp(Request $request)
    {
        if ($request->method() == 'POST') {

            $this->validate($request, [
                'username' => 'required',
            ], [
                'username.required' => 'The username field is required.',
            ]);

            if ($franchise = PPH::where('generated_id', $request->get('username'))->first()) {

                $otp =  rand(10000, 99999);
                $franchise->verification_otp = $otp;
                $franchise->save();

                $notification = new SMSNotification($franchise->mobile, 'OTP', [$otp]);
                $response = $notification->sendMessage();

                Mail::to($franchise->email)->send(new ForgotPasswordMail([
                    'otp' =>  $franchise->verification_otp,
                ]));


                return view('pph.auth.verifyOtp', ['otp' => $franchise->verification_otp, 'username' => $request->get('username')]);
            }

            return redirect()->back()->with(['error' => 'Invalid Username or Password'])->withInput();
        }

        return view('pph.auth.sendOtp');
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
            if ($franchise = PPH::where('generated_id', $request->get('username'))->first()) {
                // Check if the entered OTP matches the saved OTP
                if ($franchise->verification_otp === $enteredOtp) {
                    Auth::guard('pph')->login($franchise);
                    // Redirect to the dashboard
                    return redirect()->route('pph.dashboard');
                } else {
                    return redirect()->route('pph.verifyOtp')->with([
                        'error' => 'Invalid OTP',
                        'otp_error' => 'Invalid OTP', // Separate key for OTP error
                        'username' => $request->get('username'), // Ensure username is passed back
                        'otp' => $franchise ? $franchise->verification_otp : null, // Pass OTP if franchise is found
                    ])->withInput();
                }
            }

            // If no user is found, return with an error message
            return redirect()->route('pph.verifyOtp')->with(['error' => 'Invalid Username or Password'])->withInput();
        }

        return view('pph.auth.verifyOtp', ['username' => null]);
    }


    function uploadFiles($files, $uploadDir)
    {
        $uploadedFiles = [];


        // Ensure files is an array of UploadedFile objects
        if (!is_array($files) || empty($files)) {
            return '';
        }

        // Flatten in case it's a nested array
        $files = Arr::flatten($files);

        foreach ($files as $file) {
            if ($file instanceof \Illuminate\Http\UploadedFile) {
                $extension = $file->getClientOriginalExtension();
                $imageName = time() . '_' . uniqid() . '.' . $extension;
                $file->move($uploadDir, $imageName);
                $uploadedFiles[] = $imageName;
            }
        }

        return implode(',', $uploadedFiles);
    }

    public function register(Request $request)
    {
        $franchise = Franchise::where('status', 1)->get();

        if ($request->method() == 'POST') {

            $this->validate(
                $request,
                [
                    'name' => 'required',
                    'father_name' => 'required',
                    'mobile' => 'required|digits:10|unique:p_p_h_s,mobile',
                    'email' => 'required|email|unique:p_p_h_s,email',
                    'pincode' => 'required|digits:6',
                    'city' => 'required',
                    'district' => 'required',
                    'state' => 'required',
                    'address' => 'required',
                ]
            );

            // Collect only the necessary data for registration
            $registerData = $request->only([
                'name',
                'father_name',
                'mobile',
                'email',
                'register_type',
                'pincode',
                'city',
                'district',
                'state',
                'address',
                'generated_id',
                'franchise_id',
                'adhar_card',
                'latitude',
                'longitude',
                'pan_card',
                'ifsc_code',
                'bank_name',
                'branch_name',
                'account_number',
                'gst_number'
            ]);

            try {
                $mobile = $request->mobile;
                $otp = rand(10000, 99999);

                // Prepare all data for registration, including files
                $allData = $registerData;

                $generatedId = $request->generated_id;

                $uploadDir = 'admin/pph/' . $generatedId . '/';

                if ($request->hasFile('adhar_front_img')) {

                    $allData['adhar_front_img'] = $this->uploadFiles($request->file('adhar_front_img'), $uploadDir);
                }

                if ($request->hasFile('adhar_back_img')) {
                    $allData['adhar_back_img'] = $this->uploadFiles($request->file('adhar_back_img'), $uploadDir);
                }

                if ($request->hasFile('pan_img')) {
                    $allData['pan_img'] = $this->uploadFiles($request->file('pan_img'), $uploadDir);
                }

                if ($request->hasFile('cheque_img')) {
                    $allData['cheque_img'] = $this->uploadFiles($request->file('cheque_img'), $uploadDir);
                }

                if ($request->hasFile('photo')) {
                    $allData['photo'] = $this->uploadFiles($request->file('photo'), $uploadDir);
                }

                if ($request->hasFile('other_document')) {
                    $allData['other_document'] = $this->uploadFiles($request->file('other_document'), $uploadDir);
                }

                if ($request->hasFile('video_kyc')) {
                    $file7 = $request->file('video_kyc');
                    $extension7 = $file7->getClientOriginalName();
                    $img7 = time() . '_' . $extension7;
                    $file7->move('admin/pph/' . $generatedId . '/', $img7);
                    $allData['video_kyc'] = $img7;
                }


                \Cache::put('register_data_' . $mobile, $allData, now()->addMinutes(10));
                \Cache::put('otp_' . $mobile, $otp, now()->addMinutes(10));

                // Call SMSNotification with correct parameters
                $notification = new SMSNotification($mobile, 'OTP', [$otp]);
                $response = $notification->sendMessage();

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

        return view('pph.auth.register');
    }


    public function otpViewPage(Request $request)
    {
        return view('pph.auth.otpViewPageForPhoneVerification', ['otp' => $request->otp, 'mobile' => $request->mobile]);
    }

    public function verifyPhoneNumberOtp(Request $request)
    {

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

            //Check if OTP or register data is missing or expired
            if (!$cachedOtp || !$registerData) {
                return back()->with('error', 'OTP expired or invalid, please try again.');
            }

            //Verify OTP
            if ($cachedOtp != $enteredOtp) {
                return back()->with('error', 'Invalid OTP, please enter the correct OTP.');
            }

            try {
                $post = new PPH();
                $post->pph_no = $this->generateRandomNumber();
                $post->name = implode(',', $registerData['name']); // Handling array for name
                $post->register_type = $registerData['register_type'];
                $post->father_name = $registerData['father_name'];
                $post->mobile = $registerData['mobile'];
                $post->email = $registerData['email'];
                $post->pincode = $registerData['pincode'];
                $post->city = $registerData['city'];
                $post->district = $registerData['district'];
                $post->state = $registerData['state'];
                $post->address = $registerData['address'];
                $post->latitude = $registerData['latitude'] ?? null; // Handle null values
                $post->longitude = $registerData['longitude'] ?? null;
                $post->generated_id = $registerData['generated_id'];
                $post->gst_number = $registerData['gst_number'];


                // Generate a random password
                $password = substr(str_shuffle('0123456789'), 0, 10);
                $post->password = Hash::make($password);

                // Save the Franchise record
                $post->save();
                // Save KYC information
                if ($post) {
                    $kyc = new PPHKyc();
                    $kyc->pph_id = $post->id;
                    $kyc->adhar_card = $registerData['adhar_card'] ?? null;
                    $kyc->pan_card = $registerData['pan_card'] ?? null;
                    $kyc->ifsc_code = $registerData['ifsc_code'] ?? null;
                    $kyc->bank_name = $registerData['bank_name'] ?? null;
                    $kyc->branch_name = $registerData['branch_name'] ?? null;
                    $kyc->account_number = $registerData['account_number'] ?? null;
                    $kyc->status = 2; // Default status
                    $kyc->save();


                    // Handle KYC file uploads and save to KYC object
                    if (isset($registerData['adhar_front_img'])) {
                        $kyc->adhar_front_img = $registerData['adhar_front_img'];
                    }

                    if (isset($registerData['adhar_back_img'])) {
                        $kyc->adhar_back_img = $registerData['adhar_back_img'];
                    }

                    if (isset($registerData['pan_img'])) {
                        $kyc->pan_img = $registerData['pan_img'];
                    }

                    if (isset($registerData['cheque_img'])) {
                        $kyc->cheque_img = $registerData['cheque_img'];
                    }

                    if (isset($registerData['photo'])) {
                        $kyc->photo = $registerData['photo'];
                    }

                    if (isset($registerData['other_document'])) {
                        $kyc->other_document = $registerData['other_document'];
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

                // // Send registration email with the generated password
                // Mail::to($registerData['email'])->send(new RegistrationMail([
                //     'username' => $post->generated_id,
                //     'password' => $password,
                //     'route' => route('pph.login')
                // ]));

                return redirect()->route('pph.login')->with('success', 'pph created successfully! ID: ' . $post->generated_id . ' Password: ' . $password);
            } catch (\Exception $th) {
                return back()->with('error', $th->getMessage())->withInput();
            }
        }

        return view('pph.auth.register');
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
        Auth::guard('pph')->logout();
        return redirect()->route('pph.login');
    }





    public function profile(Request $request)
    {

        $id = Auth::guard('pph')->user()->id;

        $frachinse =  PPH::findorfail($id);

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

        foreach ($models as $serviceType => $model) {
            $serviceNumber = $serviceNumbers[$serviceType] ?? null;
            $bagfromfranchise = CMSBag::where('service_type', $serviceNumber)
                ->where('pph_id',  $id)
                ->whereDate('received_date', Carbon::today())
                ->pluck('id')
                ->toArray();

            $parcels = $model::whereIn('source_cms_bag_id', $bagfromfranchise)
                ->orWhereIn('destination_cms_bag_id', $bagfromfranchise)
                ->get();

            $parcels->each(function ($parcel) use ($serviceType, $serviceNumber) {
                $parcel->service_type = $serviceType;
                $parcel->service_number = $serviceNumber;
            });

            $allParcels = $allParcels->merge($parcels);
        }

        $dailybookingdata = PPHDailyBookingReportController::eachPPH($id);

        if ($request->date && $request->type === "parcel") {


            $date = Carbon::createFromFormat('d-m-Y', $request->date)->startOfDay()->toDateString();

            $allParcels = collect();

            foreach ($models as $serviceType => $model) {
                $serviceNumber = $serviceNumbers[$serviceType] ?? null;
                $bagfromfranchise = CMSBag::where('service_type', $serviceNumber)
                    ->where('pph_id',  $id)
                    ->whereDate('received_date', $date)
                    ->pluck('id')
                    ->toArray();

                $parcels = $model::whereIn('source_cms_bag_id', $bagfromfranchise)
                    ->orWhereIn('destination_cms_bag_id', $bagfromfranchise)
                    ->get(['barcode_no', 'pickup_name', 'pickup_pincode', 'consignee_name', 'consignee_pincode', 'id']);

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
            $dailybookingdata = PPHDailyBookingReportController::eachPPHfilterbyDate($id, $request->date);
            return response()->json([
                'status' => 200,
                'message' => 'Filter data successful',
                'data' => $dailybookingdata
            ]);
        }

        return view('pph.auth.profile', [
            'data' => $frachinse,
            'parcel' => $allParcels,
            'bookingData' => $dailybookingdata
        ]);
    }



    public function update(Request $request, $id)
    {

        // return $request;

        if ($request->method() == 'POST') {

            $this->validate(
                $request,
                [
                    // 'name' => 'required',
                    // 'father_name' => 'required',
                    // 'mobile' => 'required|digits:10',
                    // 'email' => 'required|email',
                    // 'pincode' => 'required|digits:6',
                    // 'city' => 'required',
                    // 'district' => 'required',
                    // 'state' => 'required',
                    // 'address' => 'required',
                    // 'society_name' => 'required',
                    // 'payment_account_number' => 'required',
                    // 'amount_credited' => 'required',
                    // 'imps_no' => 'required',
                    // 'payment_date' => 'required',
                    // 'pan_img' => 'file|size:1024',
                    // 'adhar_front_img' => 'file|max:1024',
                    // 'adhar_back_img' => 'file|max:1024',
                    // 'cheque_img' => 'file|max:1024',
                    // 'photo' => 'file|max:1024',
                ],
                // [
                //     'pan_img.max' => 'The PAN card image size must not exceed 1 MB.',
                //     'adhar_front_img.max' => 'The adhar card front image size must not exceed 1 MB.',
                //     'adhar_back_img.max' => 'The adhar card back image size must not exceed 1 MB.',
                //     'cheque_img.max' => 'The Cancel cheque image size must not exceed 1 MB.',
                //     'photo.max' => 'The photo size must not exceed 1 MB.',
                // ]
            );

            try {

                $date = Carbon::now();
                $post = PPH::find($id);

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

                if (!empty($request->password)) {
                    $post->password = Hash::make($request->password);
                }

                $post->save();

                if ($post) {

                    $kyc = PPHKyc::where('pph_id', $id)->first();
                    $kyc->adhar_card = $request->adhar_card;
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
                        $file1->move('admin/pph/' . $post->generated_id . '/', $img1);
                        $kyc->adhar_front_img = $img1;
                    }
                    if ($request->hasFile('adhar_back_img')) {
                        $file2 = $request->file('adhar_back_img');
                        $extension2 = $file2->getClientOriginalName();
                        $img2 = time() . '_' . $extension2;
                        $file2->move('admin/pph/' . $post->generated_id . '/', $img2);
                        $kyc->adhar_back_img = $img2;
                    }

                    if ($request->hasFile('pan_img')) {
                        $file3 = $request->file('pan_img');
                        $extension3 = $file3->getClientOriginalName();
                        $img3 = time() . '_' . $extension3;
                        $file3->move('admin/pph/' . $post->generated_id . '/', $img3);
                        $kyc->pan_img = $img3;
                    }

                    if ($request->hasFile('cheque_img')) {
                        $file4 = $request->file('cheque_img');
                        $extension4 = $file4->getClientOriginalName();
                        $img4 = time() . '_' . $extension4;
                        $file4->move('admin/pph/' . $post->generated_id . '/', $img4);
                        $kyc->cheque_img = $img4;
                    }
                    if ($request->hasFile('photo')) {
                        $file5 = $request->file('photo');
                        $extension5 = $file5->getClientOriginalName();
                        $img5 = time() . '_' . $extension5;
                        $file5->move('admin/pph/' . $post->generated_id . '/', $img5);
                        $kyc->photo = $img5;
                    }
                    if ($request->hasFile('other_document')) {
                        $file6 = $request->file('other_document');
                        $extension6 = $file6->getClientOriginalName();
                        $img6 = time() . '_' . $extension6;
                        $file6->move('admin/pph/' . $post->generated_id . '/', $img6);
                        $kyc->other_document = $img6;
                    }
                    if ($request->hasFile('video_kyc')) {
                        $file7 = $request->file('video_kyc');
                        $extension7 = $file7->getClientOriginalName();
                        $img7 = time() . '_' . $extension7;
                        $file7->move('admin/pph/' . $post->generated_id . '/', $img7);
                        $kyc->video_kyc = $img7;
                    }
                    $kyc->save();
                    if (!$kyc) {
                        $post->delete();
                    }
                }
                // return redirect->back()-([
                //     'success' => true,
                //     'message' => 'Franchise updated successfully!',

                // ]);

                return response()->json([
                    'success' => true,
                    'message' => 'PPH updated successfully!',
                ]);

                // return back()->with('success', 'Franchise updated successfully!');
            } catch (\Exception $th) {

                return response()->json([
                    'success' => false,
                    'error' => $th->getMessage(),
                ]);
            }
        }
    }

    public function paymentStore(Request $request)
    {
        $input = $request->all();
        // $api = new Api(env('RAZORPAY_KEY'), env('RAZORPAY_SECRET'));
          $api = new Api('rzp_live_hZ7MLP0RaGm3Dx', 'XVMFy4TcNEkX9Yf2x2nhjUPn');
        try {
            // Create an initialyment record with status 'pending'
            $franchise = PphPayment::where('pph_id', $request->pph_id)->first();
            $amount = $request->final_amount / 100;
            if (empty($franchise)) {
                $paymentRecord = PphPayment::create([
                    'pph_id' => $request->pph_id,
                    'razorpay_payment_id' => $request->razorpay_payment_id,
                    'amount' => $amount,
                    'status' => 'pending',
                    'method' => 'razorpay',
                ]);
            } else {
                $paymentRecord = PphPayment::find($franchise->id);
            }
            // Check if Razorpay payment ID exists
            if (!empty($input['razorpay_payment_id'])) {
                // Fetch payment details from Razorpay
                try {
                    $payment = $api->payment->fetch($input['razorpay_payment_id']);
                } catch (\Exception $e) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Error fetching payment details from Razorpay: ' . $e->getMessage(),
                    ], 500);
                }

                // Attempt to capture the payment
                try {
                    $response = $payment->capture([
                        'amount' => $payment['amount'],
                    ]);
                } catch (\Exception $e) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Error capturing payment: ' . $e->getMessage(),
                    ], 500);
                }

                // Update the payment record based on the response from Razorpay
                try {
                    if ($response['status'] == 'captured') {
                        $paymentRecord->update(['status' => 'completed']);
                    } else {
                        $paymentRecord->update(['status' => 'failed']);
                    }
                } catch (\Exception $e) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Error updating payment status: ' . $e->getMessage(),
                    ], 500);
                }

                // Respond with success
                return response()->json([
                    'success' => true,
                    'message' => 'PPH created and payment processed successfully!',
                    'data' => [
                        'id' => $request->generated_id,
                        'password' => $request->password, // Fixed the issue here
                    ]
                ]);
            } else {
                // Update the payment status to 'failed' if payment ID is empty
                try {
                    $paymentRecord->update(['status' => 'failed']);
                } catch (\Exception $e) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Error updating payment status to failed: ' . $e->getMessage(),
                    ], 500);
                }

                return response()->json([
                    'success' => false,
                    'message' => 'Payment failed: Payment ID is missing',
                ], 400); // Respond with a 400 for bad request
            }
        } catch (\Exception $e) {
            // General error catch for any unexpected errors
            return response()->json([
                'success' => false,
                'message' => 'Payment processing failed: ' . $e->getMessage(),
            ], 500);
        }
    }
}
