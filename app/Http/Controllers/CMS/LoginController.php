<?php

namespace App\Http\Controllers\CMS;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CMS;
use App\Models\PPH;
use App\Models\CMSKyc;
use App\Models\CMSPayment;
use App\Models\RegistrationPayment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\GotogoSpeedPostParcel;
use App\Models\GotogoSuperFastParcel;
use App\Models\GotogoBusinessParcel;
use App\Models\GotogoRegisteredParcel;
use App\Models\IndiaPostSpeedPostParcel;
use App\Models\IndiaPostBusinessParcel;
use App\Models\IndiaPostRegisteredParcel;
use App\Http\Controllers\admin\CMSDailyBookingReportController;
use App\Models\Franchise;
use Carbon\Carbon;
use App\Models\FranchiseBag;
use Illuminate\Support\Facades\Mail;
use App\Mail\RegistrationMail;
use App\Mail\ForgotPasswordMail;
use Illuminate\Support\Arr;
use Razorpay\Api\Api;
use App\Notifications\SMSNotification;



use Config;

class LoginController extends Controller

{

    public function __construct()

    {

        $this->middleware(function ($request, $next) {

            if (Auth::guard('cms')->check()) {

                return redirect()->route('cms.dashboard');
            }



            return $next($request);
        })->only('login');
    }

    public function generateRandomNumber()
    {
        do {
            $randomNumber = rand(1, 99999);
            $formattedNumber = str_pad($randomNumber, 5, '0', STR_PAD_LEFT);
            $exists = CMS::where('cms_no', $formattedNumber)->exists();
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

            if ($franchise = CMS::where('generated_id', $request->get('username'))->first()) {
                $payment = CMSPayment::where('cms_id', $franchise->id)->where('status', 'completed')->first();
                if (!empty($payment) || $franchise->payment_status == 1) {
                    if (Hash::check($request->get('password'), $franchise->password)) {
                        Auth::guard('cms')->login($franchise, $request->get('remember'));
                        return redirect()->route('cms.dashboard');
                    }
                } else {
                   
                    $amountInPaise = $amount->cph * 100;
                    return view('cms.auth.makePayments', [
                        'cms_id' => $franchise->id,
                        'amount' => $amountInPaise
                    ]);
                }
            }


            if ($delboy = CMS::where('generated_id', $request->get('username'))->first()) {

                if (Hash::check($request->get('password'), $delboy->password)) {

                    Auth::guard('cms')->login($delboy, $request->get('remember'));

                    return redirect()->route('cms.dashboard');
                }
            }

            return redirect()->back()->with(['error' => 'Invalid Username or Password'])->withInput();
        }

        return view('cms.auth.login');
    }


    public function sendOtp(Request $request)
    {
        if ($request->method() == 'POST') {

            $this->validate($request, [
                'username' => 'required',
            ], [
                'username.required' => 'The username field is required.',
            ]);

            if ($franchise = CMS::where('generated_id', $request->get('username'))->first()) {

                $otp =  rand(10000, 99999);
                $franchise->verification_otp = $otp;
                $franchise->save();

                $notification = new SMSNotification($franchise->mobile, 'OTP', [$otp]);
                $response = $notification->sendMessage();

                Mail::to($franchise->email)->send(new ForgotPasswordMail([
                    'otp' =>  $franchise->verification_otp,
                ]));


                return view('cms.auth.verifyOtp', ['otp' => $franchise->verification_otp, 'username' => $request->get('username')]);
            }

            return redirect()->back()->with(['error' => 'Invalid Username or Password'])->withInput();
        }

        return view('cms.auth.sendOtp');
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
            if ($franchise = CMS::where('generated_id', $request->get('username'))->first()) {
                // Check if the entered OTP matches the saved OTP
                if ($franchise->verification_otp === $enteredOtp) {
                    Auth::guard('cms')->login($franchise);
                    // Redirect to the dashboard
                    return redirect()->route('cms.dashboard');
                } else {

                    return redirect()->route('cms.verifyOtp')->with([
                        'error' => 'Invalid OTP',
                        'otp_error' => 'Invalid OTP', // Separate key for OTP error
                        'username' => $request->get('username'), // Ensure username is passed back
                        'otp' => $franchise ? $franchise->verification_otp : null, // Pass OTP if franchise is found
                    ])->withInput();
                }
            }

            // If no user is found, return with an error message
            return redirect()->route('cms.verifyOtp')->with(['error' => 'Invalid Username or Password'])->withInput();
        }

        return view('cms.auth.verifyOtp', ['username' => null]);
    }



    function uploadFiles($files, $uploadDir)
    {
        $uploadedFiles = [];


        if (!is_array($files) || empty($files)) {
            return '';
        }

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

        if ($request->method() == 'POST') {
             
            $this->validate(
                $request,
                [
                    'name' => 'required',
                    'father_name' => 'required',
                    'mobile' => 'required|digits:10|unique:c_m_s,mobile',
                    'email' => 'required|email|unique:c_m_s,email',
                    'pincode' => 'required|digits:6',
                    'city' => 'required',
                    'district' => 'required',
                    'state' => 'required',
                    'address' => 'required',
                    'gender' => 'required',
                    'age' => 'required',
                    'natality' => 'required',
                    'cph_link' => 'required',
                ]
            );
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
                'gst_number',
                'cph_link',
                'gender',
                'age',
                'natality',
            ]);

            try {
                $mobile = $request->mobile;
                $otp = rand(10000, 99999);

                $allData = $registerData;

                $generatedId = $request->generated_id;

                $uploadDir = 'admin/cms/' . $generatedId . '/';

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
                    $file7->move('admin/cms/' . $generatedId . '/', $img7);
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

        return view('cms.auth.register');
    }


    public function otpViewPage(Request $request)
    {
        return view('cms.auth.otpViewPageForPhoneVerification', ['otp' => $request->otp, 'mobile' => $request->mobile]);
    }

    public function verifyPhoneNumberOtp(Request $request)
    {

        if ($request->method() == 'POST') {
            $enteredOtp = implode('', $request->get('otp'));

            $mobile = $request->get('mobile');
            if (!$mobile) {
                return back()->with('error', 'Mobile number missing.');
            }

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
                $post = new CMS();
                $post->cms_no = $this->generateRandomNumber();
                $post->name = implode(',', $registerData['name']);
                $post->register_type = $registerData['register_type'];
                $post->father_name = $registerData['father_name'];
                $post->mobile = $registerData['mobile'];
                $post->email = $registerData['email'];
                $post->pincode = $registerData['pincode'];
                $post->city = $registerData['city'];
                $post->district = $registerData['district'];
                $post->state = $registerData['state'];
                $post->address = $registerData['address'];
                $post->latitude = $registerData['latitude'] ?? null;
                $post->longitude = $registerData['longitude'] ?? null;
                $post->natality = $registerData['natality'];
                $post->age = $registerData['age'];
                $post->gender = $registerData['gender'];
                $post->cph_link = $registerData['cph_link'] ?? null;
                $post->location = $registerData['location'] ?? null;
                $post->generated_id = $registerData['generated_id'];
                $post->gst_number = $registerData['gst_number'];
                $password = substr(str_shuffle('0123456789'), 0, 10);
                $post->password = Hash::make($password);

                $post->save();

                if ($post) {
                    $kyc = new CMSKyc();
                    $kyc->cms_id = $post->id;
                    $kyc->adhar_card = $registerData['adhar_card'] ?? null;
                    $kyc->pan_card = $registerData['pan_card'] ?? null;
                    $kyc->ifsc_code = $registerData['ifsc_code'] ?? null;
                    $kyc->bank_name = $registerData['bank_name'] ?? null;
                    $kyc->branch_name = $registerData['branch_name'] ?? null;
                    $kyc->account_number = $registerData['account_number'] ?? null;
                    $kyc->status = 2;
                    $kyc->save();
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

                    // If KYC fails, delete the cms record
                    if (!$kyc) {
                        $post->delete();
                    }
                }

                // Send registration email with the generated password
            
                // Mail::to($registerData['email'])->send(new RegistrationMail([
                //     'username' => $post->generated_id,
                //     'password' => $password,
                //     'route' => route('cms.login')
                // ]));

                return redirect()->route('cms.login')->with('success', 'cms created successfully! ID: ' . $post->generated_id . ' Password: ' . $password);
                // return redirect()->route('cms.login')->with('success', 'cms has created successfully!');
            } catch (\Exception $th) {
                return back()->with('error', $th->getMessage())->withInput();
            }
        }

        return view('cms.auth.register');
    }


    public function resendPhoneNumberOtp(Request $request)
    {
        if ($request->method() == 'POST') {

            $this->validate($request, [
                'username' => 'required',
            ], [
                'username.required' => 'The username field is required.',
            ]);

            if ($franchise = CMS::where('generated_id', $request->get('username'))->first()) {

                $franchise->verification_otp = rand(10000, 99999);
                $franchise->save();

                Mail::to($franchise->email)->send(new ForgotPasswordMail([
                    'otp' =>  $franchise->verification_otp,
                ]));


                return view('cms.auth.verifyOtp', ['otp' => $franchise->verification_otp, 'username' => $request->get('username')]);
            }

            return redirect()->back()->with(['error' => 'Invalid Username or Password'])->withInput();
        }

        return view('cms.auth.sendOtp');
    }

    public function registerOld(Request $request)
    {

        // return CMS::whereIN('id', [3, 5, 7, 8, 10])->delete();

        // return DeliveryBoy::all();
        // return PPH::all();
        // return Franchise::all();
        // return CMS::all();
        if ($request->method() == 'POST') {


            $this->validate(
                $request,
                [

                    'name' => 'required',
                    'father_name' => 'required',
                    'mobile' => 'required|digits:10',
                    'email' => 'required|email|unique:c_m_s,email',
                    'pincode' => 'required|digits:6',
                    'city' => 'required',
                    'district' => 'required',
                    'state' => 'required',
                    'address' => 'required',
                    'amount_credited' => 'required',
                    'imps_no' => 'required',
                    'payment_date' => 'required',
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
                $post = new CMS();
                $post->register_type = $request->register_type;
                $post->cms_no = $this->generateRandomNumber();
                $post->name = implode(',', $request->name);
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
                $post->generated_id = $request->generated_id;
                $post->payment_account_no = $request->payment_account_number;
                $post->amount_credited = $request->amount_credited;
                $post->imps_no = $request->imps_no;
                $post->payment_date = $request->payment_date;

                $numbers = str_shuffle('0123456789');
                $password = substr($numbers, 0, 10);
                $post->password = Hash::make($password);
                $post->save();

                if ($post) {
                    $kyc = new CMSKyc();
                    $kyc->cms_id = $post->id;
                    $kyc->adhar_card = $request->adhar_card;
                    $kyc->pan_card = $request->pan_card;
                    $kyc->ifsc_code = $request->ifsc_code;
                    $kyc->bank_name = $request->bank_name;
                    $kyc->branch_name = $request->branch_name;
                    $kyc->account_number = $request->account_number;
                    $kyc->status = 2;
                    $kyc->approved_at = $date;

                    $uploadDir = 'admin/franchise/' . $post->generated_id . '/';

                    function uploadFiles($files, $uploadDir)
                    {
                        $uploadedFiles = [];
                        foreach ($files as $file) {
                            $extension = $file->getClientOriginalExtension(); // Use extension for file type
                            $imageName = time() . '_' . uniqid() . '.' . $extension; // Generate a unique name with time and uniqid
                            $file->move($uploadDir, $imageName);
                            $uploadedFiles[] = $imageName;
                        }
                        return implode(',', $uploadedFiles);
                    }

                    if ($request->hasFile('adhar_front_img')) {
                        $kyc->adhar_front_img = uploadFiles($request->file('adhar_front_img'), $uploadDir);
                    }

                    if ($request->hasFile('adhar_back_img')) {
                        $kyc->adhar_back_img = uploadFiles($request->file('adhar_back_img'), $uploadDir);
                    }

                    if ($request->hasFile('pan_img')) {
                        $kyc->pan_img = uploadFiles($request->file('pan_img'), $uploadDir);
                    }

                    if ($request->hasFile('cheque_img')) {
                        $kyc->cheque_img = uploadFiles($request->file('cheque_img'), $uploadDir);
                    }

                    if ($request->hasFile('photo')) {
                        $kyc->photo = uploadFiles($request->file('photo'), $uploadDir);
                    }

                    if ($request->hasFile('other_document')) {
                        $kyc->other_document = uploadFiles($request->file('other_document'), $uploadDir);
                    }

                    // if ($request->hasFile('adhar_front_img')) {
                    //     $file1 = $request->file('adhar_front_img');
                    //     $extension1 = $file1->getClientOriginalName();
                    //     $img1 = time() . '_' . $extension1;
                    //     $file1->move('admin/cms/' . $post->generated_id . '/', $img1);
                    //     $kyc->adhar_front_img = $img1;
                    // }
                    // if ($request->hasFile('adhar_back_img')) {
                    //     $file2 = $request->file('adhar_back_img');
                    //     $extension2 = $file2->getClientOriginalName();
                    //     $img2 = time() . '_' . $extension2;
                    //     $file2->move('admin/cms/' . $post->generated_id . '/', $img2);
                    //     $kyc->adhar_back_img = $img2;
                    // }

                    // if ($request->hasFile('pan_img')) {
                    //     $file3 = $request->file('pan_img');
                    //     $extension3 = $file3->getClientOriginalName();
                    //     $img3 = time() . '_' . $extension3;
                    //     $file3->move('admin/cms/' . $post->generated_id . '/', $img3);
                    //     $kyc->pan_img = $img3;
                    // }

                    // if ($request->hasFile('cheque_img')) {
                    //     $file4 = $request->file('cheque_img');
                    //     $extension4 = $file4->getClientOriginalName();
                    //     $img4 = time() . '_' . $extension4;
                    //     $file4->move('admin/cms/' . $post->generated_id . '/', $img4);
                    //     $kyc->cheque_img = $img4;
                    // }
                    // if ($request->hasFile('photo')) {
                    //     $file5 = $request->file('photo');
                    //     $extension5 = $file5->getClientOriginalName();
                    //     $img5 = time() . '_' . $extension5;
                    //     $file5->move('admin/cms/' . $post->generated_id . '/', $img5);
                    //     $kyc->photo = $img5;
                    // }
                    // if ($request->hasFile('other_document')) {
                    //     $file6 = $request->file('other_document');
                    //     $extension6 = $file6->getClientOriginalName();
                    //     $img6 = time() . '_' . $extension6;
                    //     $file6->move('admin/cms/' . $post->generated_id . '/', $img6);
                    //     $kyc->other_document = $img6;
                    // }
                    if ($request->hasFile('video_kyc')) {
                        $file7 = $request->file('video_kyc');
                        $extension7 = $file7->getClientOriginalName();
                        $img7 = time() . '_' . $extension7;
                        $file7->move('admin/cms/' . $post->generated_id . '/', $img7);
                        $kyc->video_kyc = $img7;
                    }
                    $kyc->save();
                    if (!$kyc) {
                        $post->delete();
                    }
                }

                // Config::set('mail.mailers.smtp.host', 'smtp.gmail.com');
                // Config::set('mail.mailers.smtp.port', 587);
                // Config::set('mail.mailers.smtp.username', 'snehalsharan10@gmail.com');
                // Config::set('mail.mailers.smtp.password', 'aipn fdol xxjb rshv');
                // Config::set('mail.mailers.smtp.encryption', 'tls');
                // Config::set('mail.from.address', 'deferfe1214@gmail.com');
                // Config::set('mail.from.name', 'gotogopost');

                //=====================================

                Mail::to($request->email)->send(new RegistrationMail([
                    'username' => $post->generated_id,
                    'password' => $password,
                    'route' => route('cms.login')
                ]));

                return response()->json([
                    'success' => true,
                    'message' => 'CPH created successfully!',
                    'data' => [
                        'id' => $post->generated_id,
                        'password' => $password,
                    ]
                ]);
            } catch (\Exception $th) {

                return response()->json([
                    'success' => false,
                    'message' => $th->getMessage(),
                    'input' => $request->all(),
                ]);
            }
        }

        return view('cms.auth.register');
    }

    public function logout()

    {

        Auth::guard('cms')->logout();

        return redirect()->route('cms.login');
    }


    public function profile(Request $request)
    {

        $id = Auth::guard('cms')->user()->id;

        $frachinse =  CMS::findorfail($id);

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
            $bagfromfranchise = FranchiseBag::where('service_type', $serviceNumber)
                ->where('cms_id',  $id)
                ->whereDate('received_date', Carbon::today())
                ->pluck('id')
                ->toArray();

            $parcels = $model::whereIn('source_franchise_bag_id', $bagfromfranchise)
                ->orWhereIn('destination_franchise_bag_id', $bagfromfranchise)
                ->get();

            $parcels->each(function ($parcel) use ($serviceType, $serviceNumber) {
                $parcel->service_type = $serviceType;
                $parcel->service_number = $serviceNumber;
            });

            $allParcels = $allParcels->merge($parcels);
        }

        // return $parcel;
        $dailybookingdata = CMSDailyBookingReportController::eachCMS($id);


        if ($request->date && $request->type === "parcel") {

            $date = Carbon::createFromFormat('d-m-Y', $request->date)->startOfDay()->toDateString();

            $allParcels = collect();

            foreach ($models as $serviceType => $model) {
                $serviceNumber = $serviceNumbers[$serviceType] ?? null;
                $bagfromfranchise = FranchiseBag::where('service_type', $serviceNumber)
                    ->where('cms_id',  $id)
                    ->whereDate('received_date', Carbon::today())
                    ->pluck('id')
                    ->toArray();

                $parcels = $model::whereIn('source_franchise_bag_id', $bagfromfranchise)
                    ->orWhereIn('destination_franchise_bag_id', $bagfromfranchise)
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
                $html .= '<a class="delete-table me-2" href="' . route('cms.parcel.view', ['id' => $item->barcode_no, 'service_type' => $item->service_number]) . '">';
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

            $dailybookingdata = CMSDailyBookingReportController::eachCMSfilterbyDate($id, $request->date);
            return response()->json(['status' => 200, 'message' => 'filter data succesfull', 'data' => $dailybookingdata]);
        }

        return view(
            'cms.auth.profile',
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
                $post = CMS::find($id);

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

                    $kyc = CMSKyc::where('cms_id', $id)->first();
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
                        $file1->move('admin/cms/' . $post->generated_id . '/', $img1);
                        $kyc->adhar_front_img = $img1;
                    }
                    if ($request->hasFile('adhar_back_img')) {
                        $file2 = $request->file('adhar_back_img');
                        $extension2 = $file2->getClientOriginalName();
                        $img2 = time() . '_' . $extension2;
                        $file2->move('admin/cms/' . $post->generated_id . '/', $img2);
                        $kyc->adhar_back_img = $img2;
                    }

                    if ($request->hasFile('pan_img')) {
                        $file3 = $request->file('pan_img');
                        $extension3 = $file3->getClientOriginalName();
                        $img3 = time() . '_' . $extension3;
                        $file3->move('admin/cms/' . $post->generated_id . '/', $img3);
                        $kyc->pan_img = $img3;
                    }

                    if ($request->hasFile('cheque_img')) {
                        $file4 = $request->file('cheque_img');
                        $extension4 = $file4->getClientOriginalName();
                        $img4 = time() . '_' . $extension4;
                        $file4->move('admin/cms/' . $post->generated_id . '/', $img4);
                        $kyc->cheque_img = $img4;
                    }
                    if ($request->hasFile('photo')) {
                        $file5 = $request->file('photo');
                        $extension5 = $file5->getClientOriginalName();
                        $img5 = time() . '_' . $extension5;
                        $file5->move('admin/cms/' . $post->generated_id . '/', $img5);
                        $kyc->photo = $img5;
                    }
                    if ($request->hasFile('other_document')) {
                        $file6 = $request->file('other_document');
                        $extension6 = $file6->getClientOriginalName();
                        $img6 = time() . '_' . $extension6;
                        $file6->move('admin/cms/' . $post->generated_id . '/', $img6);
                        $kyc->other_document = $img6;
                    }
                    if ($request->hasFile('video_kyc')) {
                        $file7 = $request->file('video_kyc');
                        $extension7 = $file7->getClientOriginalName();
                        $img7 = time() . '_' . $extension7;
                        $file7->move('admin/cms/' . $post->generated_id . '/', $img7);
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
                    'message' => 'CMS updated successfully!',
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


    // public function paymentStore(Request $request)
    // {
    //     $input = $request->all();
    //     // return $input;
    //     // Initialize Razorpay API
    //     $api = new Api(env('RAZORPAY_KEY'), env('RAZORPAY_SECRET'));

    //     try {
    //         // Create an initialyment record with status 'pending'
    //         $franchise = CMSPayment::where('cms_id', $request->cms_id)->first();
    //         if (empty($franchise)) {
    //             $paymentRecord = CMSPayment::create([
    //                 'cms_id' => $request->cms_id,
    //                 'razorpay_payment_id' => $request->razorpay_payment_id,
    //                 'amount' => $request->final_amount,
    //                 'status' => 'pending',
    //                 'method' => 'razorpay',
    //             ]);
    //         } else {
    //             $paymentRecord = CMSPayment::find($franchise->id);
    //         }
    //         // Check if Razorpay payment ID exists
    //         if (!empty($input['razorpay_payment_id'])) {
    //             // Fetch payment details from Razorpay
    //             try {
    //                 $payment = $api->payment->fetch($input['razorpay_payment_id']);
    //             } catch (\Exception $e) {
    //                 return response()->json([
    //                     'success' => false,
    //                     'message' => 'Error fetching payment details from Razorpay: ' . $e->getMessage(),
    //                 ], 500);
    //             }

    //             // Attempt to capture the payment
    //             try {
    //                 $response = $payment->capture([
    //                     'amount' => $payment['amount'],
    //                 ]);
    //             } catch (\Exception $e) {
    //                 return response()->json([
    //                     'success' => false,
    //                     'message' => 'Error capturing payment: ' . $e->getMessage(),
    //                 ], 500);
    //             }

    //             // Update the payment record based on the response from Razorpay
    //             try {
    //                 if ($response['status'] == 'captured') {
    //                     $paymentRecord->update(['status' => 'completed']);
    //                 } else {
    //                     $paymentRecord->update(['status' => 'failed']);
    //                 }
    //             } catch (\Exception $e) {
    //                 return response()->json([
    //                     'success' => false,
    //                     'message' => 'Error updating payment status: ' . $e->getMessage(),
    //                 ], 500);
    //             }

    //             // Respond with success
    //             return response()->json([
    //                 'success' => true,
    //                 'message' => 'CPH created and payment processed successfully!',
    //                 'data' => [
    //                     'id' => $request->generated_id,
    //                     'password' => $request->password, // Fixed the issue here
    //                 ]
    //             ]);
    //         } else {
    //             // Update the payment status to 'failed' if payment ID is empty
    //             try {
    //                 $paymentRecord->update(['status' => 'failed']);
    //             } catch (\Exception $e) {
    //                 return response()->json([
    //                     'success' => false,
    //                     'message' => 'Error updating payment status to failed: ' . $e->getMessage(),
    //                 ], 500);
    //             }

    //             return response()->json([
    //                 'success' => false,
    //                 'message' => 'Payment failed: Payment ID is missing',
    //             ], 400); // Respond with a 400 for bad request
    //         }
    //     } catch (\Exception $e) {
    //         // General error catch for any unexpected errors
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Payment processing failed: ' . $e->getMessage(),
    //         ], 500);
    //     }
    // }

    public function paymentStore(Request $request)
{
    $input = $request->all();

    // Initialize Razorpay API
    // $api = new Api(env('RAZORPAY_KEY'), env('RAZORPAY_SECRET'));
    $api = new Api('rzp_live_hZ7MLP0RaGm3Dx', 'XVMFy4TcNEkX9Yf2x2nhjUPn');
    try {
        // Check if a payment already exists for the CMS ID
        $existingPayment = CMSPayment::where('cms_id', $request->cms_id)->first();
             $amount = $request->final_amount / 100;
        if (!$existingPayment) {
            $paymentRecord = CMSPayment::create([
                'cms_id' => $request->cms_id,
                'razorpay_payment_id' => $request->razorpay_payment_id,
                'amount' => $amount,
                'status' => 'pending',
                'method' => 'razorpay',
            ]);
        } else {
            $paymentRecord = $existingPayment;
        }

        // Check for Razorpay payment ID
        if (!empty($input['razorpay_payment_id'])) {
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
                    'amount' => $payment['amount'], // Amount is in paise
                ]);
            } catch (\Exception $e) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error capturing payment: ' . $e->getMessage(),
                ], 500);
            }

            // Update payment record based on Razorpay response
            try {
                $status = ($response['status'] === 'captured') ? 'completed' : 'failed';
                $paymentRecord->update(['status' => $status]);
            } catch (\Exception $e) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error updating payment status: ' . $e->getMessage(),
                ], 500);
            }

            return response()->json([
                'success' => true,
                'message' => 'CPH created and payment processed successfully!',
                'data' => [
                    'id' => $request->generated_id,
                    'password' => $request->password,
                ]
            ]);
        } else {
            // Handle missing Razorpay payment ID
            $paymentRecord->update(['status' => 'failed']);

            return response()->json([
                'success' => false,
                'message' => 'Payment failed: Payment ID is missing',
            ], 400);
        }

    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Payment processing failed: ' . $e->getMessage(),
        ], 500);
    }
}


    public function getLocation(Request $request)
{
    // Validate the input
    $request->validate([
        'city' => 'required|string|max:255',
    ]);

    // Fetch CMS data matching city (prefix match)
    $locations = PPH::where('city', 'LIKE', $request->city . '%')->get();

    // If no data found
    if ($locations->isEmpty()) {
        return response("<select name='cph_link' class='form-control'><option disabled>No PPH found</option></select>");
    }

    // Start building the select HTML
    $html = "<select name='cph_link' class='form-control'>";
    $html .= "<option selected disabled>Select PPH</option>"; // Optional default

    foreach ($locations as $location) {
        $html .= "<option value='{$location->id}'>" .
                    "{$location->name} - {$location->address}, {$location->city} - {$location->pincode}" .
                 "</option>";
    }

    $html .= "</select>";

    return response($html);
}
}
