<?php

namespace App\Http\Controllers\franchise;

use App\Http\Controllers\Controller;
use App\Models\Franchise;
use App\Models\Admin;
use App\Models\CMS;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\FranchiseRoleUser;
use App\Models\GotogoSpeedPostParcel;
use App\Models\GotogoSuperFastParcel;
use App\Models\GotogoBusinessParcel;
use App\Models\GotogoRegisteredParcel;
use App\Models\IndiaPostSpeedPostParcel;
use App\Models\IndiaPostBusinessParcel;
use App\Models\IndiaPostRegisteredParcel;
use App\Http\Controllers\admin\FranchiseDailyBookingReportController;
use Illuminate\Support\Facades\Mail;
use App\Mail\RegistrationMail;
use Carbon\Carbon;
use App\Mail\ForgotPasswordMail;
use App\Models\FranchiseKyc;
use App\Models\FranchisePayment;
use App\Models\RegistrationPayment;
use Razorpay\Api\Api;
use Illuminate\Support\Arr;
use Config;
use DB;
use App\Notifications\SMSNotification;

class LoginController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (Auth::guard('franchise')->check()) {
                return redirect()->route('franchise.dashboard');
            }
            return $next($request);
        })->only('login');
    }

    public function generateRandomNumber()
    {
        do {
            $randomNumber = rand(1, 99999);
            $formattedNumber = str_pad($randomNumber, 5, '0', STR_PAD_LEFT);
            $exists = Franchise::where('franchise_no', $formattedNumber)->exists();
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

            if ($franchise = Franchise::where('generated_id', $request->get('username'))->first()) {
                $payment = FranchisePayment::where('franchise_id', $franchise->id)->where('status', 'completed')->first();

                if (!empty($payment) || $franchise->payment_status == 1) {
                    if (Hash::check($request->get('password'), $franchise->password)) {
                        Auth::guard('franchise')->login($franchise, $request->get('remember'));
                        return redirect()->route('franchise.dashboard');
                    }
                } else {
                    if($franchise->package == 1){
                        $cms= CMS::where('generated_id', $request->get('username'))->first();
                        return view('franchise.combo.makePayment', [
                            'franchise_id' => $franchise->id,
                            'cph_id' => $cms->id,
                            'amount' => $amount->combo * 100,
                        ]);
                    }

                    return view('franchise.auth.makePayment', [
                        'franchise_id' => $franchise->id,
                        'amount' => $amount->franchise * 100,
                         'mobile' => $franchise->mobile,
    'email' => $franchise->email,
                    ]);
                }
            }

            if ($employee = FranchiseRoleUser::where('email', $request->get('username'))->first()) {
                if (Hash::check($request->get('password'), $employee->password)) {
                    Auth::guard('franchiseRoleUser')->login($employee, $request->get('remember'));
                    return redirect()->route('franchise.dashboard');
                }
            }

            return redirect()->back()->with(['error' => 'Invalid Username or Password'])->withInput();
        }

        return view('franchise.auth.login');
    }

    // Optional: Keep OTP functions if needed for forgot password
    public function sendOtp(Request $request)
    {
        if ($request->method() == 'POST') {
            $this->validate($request, [
                'username' => 'required',
            ], [
                'username.required' => 'The username field is required.',
            ]);

            if ($franchise = Franchise::where('generated_id', $request->get('username'))->first()) {
                $otp =  rand(10000, 99999);
                $franchise->verification_otp = $otp;
                $franchise->save();

                $notification = new SMSNotification($franchise->mobile, 'OTP', [$otp]);
                $response = $notification->sendMessage();

                Mail::to($franchise->email)->send(new ForgotPasswordMail([
                    'otp' =>  $franchise->verification_otp,
                ]));

                return view('franchise.auth.verifyOtp', ['otp' => $franchise->verification_otp, 'username' => $request->get('username')]);
            }

            if ($employee = FranchiseRoleUser::where('email', $request->get('username'))->first()) {
                $employee->verification_otp = rand(10000, 99999);
                $employee->save();
                return view('franchise.auth.verifyOtp', ['otp' => $employee->verification_otp, 'username' => $request->get('username')]);
            }

            return redirect()->back()->with(['error' => 'Invalid Username or Password'])->withInput();
        }

        return view('franchise.auth.sendOtp');
    }

    public function verifyOtp(Request $request)
    {
        if ($request->method() == 'POST') {
            $this->validate($request, [
                'otp' => 'required',
            ], [
                'otp.required' => 'The OTP field is required.',
            ]);

            $enteredOtp = implode('', $request->get('otp'));

            if ($franchise = Franchise::where('generated_id', $request->get('username'))->first()) {
                if ($franchise->verification_otp === $enteredOtp) {
                    Auth::guard('franchise')->login($franchise);
                    return redirect()->route('franchise.dashboard');
                } else {
                    return redirect()->route('franchise.verifyOtp')->with([
                        'error' => 'Invalid OTP',
                        'otp_error' => 'Invalid OTP',
                        'username' => $request->get('username'),
                        'otp' => $franchise ? $franchise->verification_otp : null,
                    ])->withInput();
                }
            }

            if ($employee = FranchiseRoleUser::where('email', $request->get('username'))->first()) {
                if (Hash::check($request->get('password'), $employee->password)) {
                    Auth::guard('franchiseRoleUser')->login($employee, $request->get('remember'));
                    return redirect()->route('franchise.dashboard');
                }
            }

            return redirect()->route('franchise.verifyOtp')->with(['error' => 'Invalid Username or Password'])->withInput();
        }

        return view('franchise.auth.verifyOtp', ['username' => null]);
    }

    // 🎯 **UPDATED REGISTER FUNCTION - NO FOLDER CREATION**
   public function register(Request $request)
{
    // GET request - show registration form
    if ($request->method() == 'GET') {
        $franchise = Franchise::where('status', 1)->get();
        return view('franchise.auth.register', ['franchise' => $franchise]);
    }

    // POST request - process registration immediately
    if ($request->method() == 'POST') {
        // Validation
        $this->validate(
            $request,
            [
                'name' => 'required|array|min:1',
                'father_name' => 'required',
                'mobile' => 'required|digits:10|unique:franchises,mobile',
                'email' => 'required|email|unique:franchises,email',
                'pincode' => 'required|digits:6',
                'city' => 'required',
                'district' => '',
                'state' => '',
                'address' => '',
                'society_name' => 'required',
                'cph_link' => '',
                'gender' => 'required',
                'age' => 'required|numeric|min:18',
                'natality' => 'required',
            ],
            [
                'name.required' => 'At least one partner name is required',
                'mobile.unique' => 'This mobile number is already registered',
                'email.unique' => 'This email is already registered',
                'age.min' => 'Minimum age should be 18 years',
            ]
        );

        DB::beginTransaction();
        
        try {
            // Generate franchise number and ID
            $franchiseNo = $this->generateRandomNumber();
            $generatedId = 'FR' . $franchiseNo;
            
            // Get pincode based generated ID if available
            if ($request->has('generated_id') && !empty($request->generated_id)) {
                $generatedId = $request->generated_id;
            }
            
            // 🎯 **NO DIRECTORY CREATION - SIMPLE SAVE ONLY**

            // 📝 **STEP 1: CREATE FRANCHISE RECORD**
            $franchise = new Franchise();
            $franchise->franchise_no = $franchiseNo;
            $franchise->name = implode(',', $request->name);
            $franchise->register_type = $request->register_type ?? 'proprietor';
            $franchise->father_name = $request->father_name;
            $franchise->mobile = $request->mobile;
            $franchise->email = $request->email;
            $franchise->pincode = $request->pincode;
            $franchise->city = $request->city;
            $franchise->district = $request->district;
            $franchise->state = $request->state;
            $franchise->address = $request->address;
            $franchise->latitude = $request->latitude ?? null;
            $franchise->longitude = $request->longitude ?? null;
            $franchise->natality = $request->natality;
            $franchise->age = $request->age;
            $franchise->gender = $request->gender;
            $franchise->society = $request->society_name;
            $franchise->sector = $request->sector;
            $franchise->generated_id = $generatedId;
           
            $franchise->cph_link = $request->cph_link ?? null;
            $franchise->location = $request->city ?? null;
            
            // 🔐 **FIXED: Generate random 8-digit password**
            $password = $this->generateRandomPassword(8); // Generate 8-digit random password
            $franchise->password = Hash::make($password);
            
            // Default status
            $franchise->status = 0;
            $franchise->payment_status = 0;
            
            $franchise->save();

            // 📝 **STEP 2: CREATE KYC RECORD (Without files for now)**
            $kyc = new FranchiseKyc();
            $kyc->franchise_id = $franchise->id;
            $kyc->adhar_card = $request->adhar_card ?? null;
            $kyc->pan_card = $request->pan_card ?? null;
            $kyc->ifsc_code = $request->ifsc_code ?? null;
            $kyc->bank_name = $request->bank_name ?? null;
            $kyc->branch_name = $request->branch_name ?? null;
            $kyc->account_number = $request->account_number ?? null;
           
            $kyc->status = 2; // Pending verification
            
            // 🎯 **FILES WILL BE UPLOADED LATER IN PROFILE**
            $kyc->adhar_front_img = 'pending';
            $kyc->adhar_back_img = 'pending';
            $kyc->pan_img = 'pending';
            $kyc->cheque_img = 'pending';
            $kyc->photo = 'pending';
            $kyc->other_document = 'pending';
            $kyc->video_kyc = 'pending';
            
            $kyc->save();

            // 📝 **STEP 3: SEND WELCOME SMS (Optional)**
            try {
                $notification = new SMSNotification($franchise->mobile, 'WELCOME', [$franchise->generated_id, $password]);
                $response = $notification->sendMessage();
            } catch (\Exception $smsError) {
                // SMS fail hua toh bhi registration complete
                \Log::info('SMS sending failed, but registration successful');
            }

            DB::commit();

            // 📝 **STEP 4: REDIRECT TO LOGIN WITH SUCCESS**
            return redirect()->route('franchise.login')
                ->with('success', '✅ Registration Successful! Your Franchise ID: ' . $franchise->generated_id . ' and Password: ' . $password)
                ->with('id', $franchise->generated_id)
                ->with('password', $password);

        } catch (\Exception $th) {
            DB::rollBack();
            return back()
                ->with('error', 'Registration failed: ' . $th->getMessage())
                ->withInput();
        }
    }
}

/**
 * Generate a random password of specified length
 * 
 * @param int $length
 * @return string
 */
private function generateRandomPassword($length = 8)
{
    // For mixed alphanumeric + special characters
    $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXY';
    $password = '';
    $characterCount = strlen($characters);
    
    for ($i = 0; $i < $length; $i++) {
        $password .= $characters[rand(0, $characterCount - 1)];
    }
    
    return $password;
}

    // 🎯 **SIMPLIFIED UPLOAD FILES FUNCTION**
    function uploadFiles($files, $uploadDir)
    {
        // 🚨 TEMPORARILY DISABLE FILE UPLOAD
        return 'pending';
        
        /*
        $uploadedFiles = [];

        if (!is_array($files) || empty($files)) {
            return '';
        }

        $files = Arr::flatten($files);

        foreach ($files as $file) {
            if ($file instanceof \Illuminate\Http\UploadedFile) {
                $extension = $file->getClientOriginalExtension();
                $imageName = time() . '_' . uniqid() . '.' . $extension;
                
                // Simple move without directory check
                try {
                    $file->move($uploadDir, $imageName);
                    $uploadedFiles[] = $imageName;
                } catch (\Exception $e) {
                    // If move fails, skip this file
                    continue;
                }
            }
        }

        return implode(',', $uploadedFiles);
        */
    }

    // 🏠 **Show Make Payment Page**
    public function makePayment(Request $request, $id)
    {
        $franchise = Franchise::findOrFail($id);
        $amount = RegistrationPayment::first();
        
        return view('franchise.auth.makePayment', [
            'franchise_id' => $franchise->id,
            'amount' => $amount ? ($amount->franchise * 100) : 0,
            'franchise' => $franchise,
                'mobile' => $franchise->mobile,
    'email' => $franchise->email
        ]);
    }

    public function logout()
    {
        Auth::guard('franchise')->logout();
        return redirect()->route('franchise.login');
    }

    public function profile(Request $request)
    {
        $id = Franchise::getFranchiseId();
        $franchise = Franchise::findorfail($id);
        $franchise_id = $id;

        $models = [
            GotogoSpeedPostParcel::getServiceType(1) => GotogoSpeedPostParcel::class,
            GotogoSuperFastParcel::getServiceType(2) => GotogoSuperFastParcel::class,
            GotogoBusinessParcel::getServiceType(3) => GotogoBusinessParcel::class,
            GotogoRegisteredParcel::getServiceType(4) => GotogoRegisteredParcel::class,
            IndiaPostSpeedPostParcel::getServiceType(5) => IndiaPostSpeedPostParcel::class,
            IndiaPostBusinessParcel::getServiceType(6) => IndiaPostBusinessParcel::class,
            IndiaPostRegisteredParcel::getServiceType(7) => IndiaPostRegisteredParcel::class,
        ];

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
            $parcels = $model::where('franchise_id', $franchise_id)
                ->whereDate('created_at', Carbon::today()->toDateString())
                ->get(['id', 'barcode_no', 'pickup_name', 'pickup_pincode', 'consignee_name', 'consignee_pincode']);

            $serviceNumber = $serviceNumbers[$serviceType] ?? null;

            $parcels->each(function ($parcel) use ($serviceType, $serviceNumber) {
                $parcel->service_type = $serviceType;
                $parcel->service_number = $serviceNumber;
            });

            $allParcels = $allParcels->merge($parcels);
        }

        $dailybookingdata = FranchiseDailyBookingReportController::eachFranchise($id);

        if ($request->date && $request->type === "parcel") {
            $date = Carbon::createFromFormat('d-m-Y', $request->date)->startOfDay()->toDateString();

            $allParcels = collect();

            foreach ($models as $serviceType => $model) {
                $parcels = $model::where('franchise_id', $franchise_id)
                    ->whereDate('created_at', $date)
                    ->get(['id', 'barcode_no', 'pickup_name', 'pickup_pincode', 'consignee_name', 'consignee_pincode']);

                $serviceNumber = $serviceNumbers[$serviceType] ?? null;

                $parcels->each(function ($parcel) use ($serviceType, $serviceNumber) {
                    $parcel->service_type = $serviceType;
                    $parcel->service_number = $serviceNumber;
                });

                $allParcels = $allParcels->merge($parcels);
            }

            $html = '';
            $i = 0;

            foreach ($allParcels as $item) {
                $html .= '<tr>';
                $html .= '<td>' . ++$i . '</td>';
                $html .= '<td>' . htmlspecialchars($item->service_type) . '</td>';
                $html .= '<td>' . htmlspecialchars($item->pickup_name) . '</td>';
                $html .= '<td>' . htmlspecialchars($item->pickup_pincode) . '</td>';
                $html .= '<td>' . htmlspecialchars($item->consignee_name) . '</td>';
                $html .= '<td>' . htmlspecialchars($item->consignee_pincode) . '</td>';
                $html .= '<td>';
                $html .= '<div class="dropdown">';
                $html .= '<button class="btn btn-sm btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">';
                $html .= 'Actions';
                $html .= '</button>';
                $html .= '<ul class="dropdown-menu dropdown-menu-right">';

                $viewRoute = '#';
                if ($item->service_type == 1) {
                    $viewRoute = route('franchise.go-speed-post-parcel.view', $item->id);
                } elseif ($item->service_type == 3) {
                    $viewRoute = route('franchise.go-business-parcel.view', $item->id);
                } elseif ($item->service_type == 4) {
                    $viewRoute = route('franchise.go-registered.view', $item->id);
                } elseif ($item->service_type == 5) {
                    $viewRoute = route('franchise.india-post-speed-post.view', $item->id);
                } elseif ($item->service_type == 6) {
                    $viewRoute = route('franchise.india-post-business.view', $item->id);
                } elseif ($item->service_type == 7) {
                    $viewRoute = route('franchise.india-post-registered.view', $item->id);
                }

                $html .= '<li><a class="dropdown-item" href="' . $viewRoute . '"><i class="fa-regular fa-eye m-r-5"></i> View</a></li>';
                $html .= '<li><a class="dropdown-item" href="#" onclick="delete_modal(\'' . $item->id . '\')"><i class="fa-regular fa-trash-can m-r-5"></i> Remove</a></li>';
                $html .= '</ul>';
                $html .= '</div>';
                $html .= '</td>';
                $html .= '</tr>';
            }

            return response()->json([
                'status' => 200,
                'message' => 'Filter data successful',
                'html' => $html
            ]);
        }

        if ($request->date && $request->type === "bookings") {
            $dailybookingdata = FranchiseDailyBookingReportController::eachFranchisefilterbyDate($id, $request->date);
            return response()->json(['status' => 200, 'message' => 'filter data succesfull', 'data' => $dailybookingdata]);
        }

        return view(
            'franchise.auth.profile',
            [
                'franchise' => $franchise,
                'parcel' => $allParcels,
                'bookingData' => $dailybookingdata
            ]
        );
    }

    public function update(Request $request, $id)
    {
        if ($request->method() == 'POST') {
            $this->validate(
                $request,
                [
                    'name' => 'required',
                    'father_name' => 'required',
                    'mobile' => 'required|digits:10',
                    'email' => 'required|email',
                    'pincode' => 'required|digits:6',
                    'city' => 'required',
                    'district' => 'required',
                    'state' => 'required',
                    'address' => 'required',
                ],
            );

            try {
                $date = Carbon::now();
                $post = Franchise::find($id);
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
                    $kyc = FranchiseKyc::where('franchise_id', $id)->first();
                    $kyc->adhar_card = $request->adhar_card;
                    $kyc->pan_card = $request->pan_card;
                    $kyc->ifsc_code = $request->ifsc_code;
                    $kyc->bank_name = $request->bank_name;
                    $kyc->branch_name = $request->branch_name;
                    $kyc->account_number = $request->account_number;
                    $kyc->approved_at = $date;
                    
                    // File upload in profile (users can upload later)
                    if ($request->hasFile('adhar_front_img')) {
                        try {
                            $file1 = $request->file('adhar_front_img');
                            $extension1 = $file1->getClientOriginalName();
                            $img1 = time() . '_' . $extension1;
                            $file1->move('admin/franchise/' . $post->generated_id . '/', $img1);
                            $kyc->adhar_front_img = $img1;
                        } catch (\Exception $e) {
                            // Skip if upload fails
                        }
                    }
                    // ... similar for other files
                    
                    $kyc->save();
                }

                return response()->json([
                    'success' => true,
                    'message' => 'Franchise updated successfully!',
                ]);
            } catch (\Exception $th) {
                return back()->with('error', $th->getMessage())->withInput();
            }
        }

        return view('franchise.auth.register');
    }

   public function paymentStore(Request $request)
{
    $input = $request->all();

    $api = new Api(
        env('RAZORPAY_KEY'),
        env('RAZORPAY_SECRET')
    );

    try {

        /*
        |--------------------------------------------------------------------------
        | Find Franchise
        |--------------------------------------------------------------------------
        */
        $franchiseRecord = Franchise::find($request->franchise_id);

        if (!$franchiseRecord) {
            return response()->json([
                'success' => false,
                'message' => 'Franchise not found.'
            ], 404);
        }

        /*
        |--------------------------------------------------------------------------
        | Razorpay Payment ID Check
        |--------------------------------------------------------------------------
        */
        if (empty($request->razorpay_payment_id)) {
            return response()->json([
                'success' => false,
                'message' => 'Payment ID is missing.'
            ], 400);
        }

        /*
        |--------------------------------------------------------------------------
        | Create a NEW payment record for this payment
        |--------------------------------------------------------------------------
        */
        $paymentRecord = FranchisePayment::create([
            'franchise_id' => $franchiseRecord->id,
            'razorpay_payment_id' => $request->razorpay_payment_id,
            'amount' => $request->final_amount,
            'status' => 'pending',
            'type' => 'register',
            'method' => 'razorpay',
        ]);

        /*
        |--------------------------------------------------------------------------
        | Fetch Payment From Razorpay
        |--------------------------------------------------------------------------
        */
        try {

            $payment = $api->payment->fetch(
                $request->razorpay_payment_id
            );

        } catch (\Exception $e) {

            $paymentRecord->update([
                'status' => 'failed'
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error fetching payment details from Razorpay: '
                    . $e->getMessage()
            ], 500);
        }

        /*
        |--------------------------------------------------------------------------
        | Capture Payment
        |--------------------------------------------------------------------------
        */
        try {

            $response = $payment->capture([
                'amount' => $payment['amount'],
            ]);

        } catch (\Exception $e) {

            $paymentRecord->update([
                'status' => 'failed'
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error capturing payment: '
                    . $e->getMessage()
            ], 500);
        }

        /*
        |--------------------------------------------------------------------------
        | Payment Successful
        |--------------------------------------------------------------------------
        */
        if ($response['status'] == 'captured') {

            // Payment completed
            $paymentRecord->update([
                'status' => 'completed'
            ]);

            // Update franchise payment status
            $franchiseRecord->payment_status = 1;
            $franchiseRecord->save();

            /*
            |--------------------------------------------------------------------------
            | IMPORTANT:
            | Login the SAME franchise after successful payment
            |--------------------------------------------------------------------------
            */
            Auth::guard('franchise')->login($franchiseRecord);

            /*
            |--------------------------------------------------------------------------
            | Return Success Response
            |--------------------------------------------------------------------------
            */
            return response()->json([
                'success' => true,
                'message' => 'Payment successful.',
                'redirect' => route('franchise.dashboard')
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Payment Failed
        |--------------------------------------------------------------------------
        */
        $paymentRecord->update([
            'status' => 'failed'
        ]);

        return response()->json([
            'success' => false,
            'message' => 'Payment was not captured.'
        ], 400);

    } catch (\Exception $e) {

        return response()->json([
            'success' => false,
            'message' => 'Payment processing failed: '
                . $e->getMessage()
        ], 500);
    }
}

    public function getLocation(Request $request)
    {
        $request->validate([
            'city' => 'required|string|max:255',
        ]);

        $locations = CMS::where('city', 'LIKE', $request->city . '%')->get();

        if ($locations->isEmpty()) {
            return response("<select name='cph_link' class='form-control'><option disabled>No CPH found</option></select>");
        }

        $html = "<select name='cph_link' class='form-control'>";
        $html .= "<option selected disabled>Select CPH</option>";

        foreach ($locations as $location) {
            $html .= "<option value='{$location->id}'>" .
                        "{$location->name} - {$location->address}, {$location->city} - {$location->pincode}" .
                     "</option>";
        }

        $html .= "</select>";

        return response($html);
    }
}