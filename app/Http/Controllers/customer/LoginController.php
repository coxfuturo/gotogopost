<?php

namespace App\Http\Controllers\customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\CodGotogoBusinessParcel;
use App\Models\NoRegisterCustomer;
use App\Models\DeliveryBoy;
use App\Models\Franchise;
use App\Models\ECustomer;
use App\Models\ECustomerKyc;
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
            if (Auth::guard('customer')->check()) {
                return redirect()->route('customer.dashboard');
            }

            return $next($request);
        })->only('login');
    }


    public function generateRandomNumber()
    {
        do {
            $randomNumber = rand(1, 99999);
            $formattedNumber = str_pad($randomNumber, 5, '0', STR_PAD_LEFT);
            $exists = ECustomer::where('customer_no', $formattedNumber)->exists();
        } while ($exists);

        return $formattedNumber;
    }

    public function login(Request $request)

    {
        // return $request->all();
        if ($request->method() == 'POST') {

            $this->validate($request, [

                // 'username' => 'required',

                'password' => 'required'

            ], [

                // 'username.required' => 'The username field is required.',

            ]);


           if($request->type == 'premium'){
            if ($delboy = ECustomer::where('email', $request->get('username'))->first()) {

                if (Hash::check($request->get('password'), $delboy->password)) {
                      
                         Auth::guard('customer')->login($delboy, $request->get('remember'));

                          return redirect()->route('customer.dashboard');
                    
                }
            }
            
        }else{
             if ($delboy = NoRegisterCustomer::where('phone', $request->get('number'))->first()) {

                if (Hash::check($request->get('password'), $delboy->password)) {
                      
                         Auth::guard('prepaid')->login($delboy, $request->get('remember'));
                        // return 1223;
                          return redirect()->route('prepaid.dashboard');
                    
                }
            }
        }


            return redirect()->back()->with(['error' => 'Invalid Username or Password'])->withInput();
        }

        return view('customer.auth.login');
    }

     public function logout()

    {

        Auth::guard('customer')->logout();

        return redirect()->route('customer.login');
    }

    public function profile(Request $request)
    {

        $id = Auth::guard('customer')->user()->id;
        $franchise = ECustomer::findOrFail($id);

        return view('customer.auth.profile', compact('franchise'));
    }



    public function sendOtp(Request $request)
    {
        
  
        if ($request->method() == 'POST') {
            //   return $request->all();
            $this->validate($request, [
                'username' => 'required',
            ], [
                'username.required' => 'The username field is required.',
            ]);

            if ($franchise = ECustomer::where('email', $request->get('username'))->first()) {

                $otp =  rand(10000, 99999);
                $franchise->verification_otp = $otp;
                $franchise->save();

                $notification = new SMSNotification($franchise->mobile, 'OTP', [$otp]);
                $response = $notification->sendMessage();

                Mail::to($franchise->email)->send(new ForgotPasswordMail([
                    'otp' =>  $franchise->verification_otp,
                ]));

                // Mail::to('fuloriadeepak999@gmail.com')->send(new ForgotPasswordMail([
                //     'otp' =>  $franchise->verification_otp,
                // ]));
                return view('customer.auth.verifyOtp', ['otp' => $franchise->verification_otp, 'username' => $request->get('username')]);
            }

        

            return redirect()->back()->with(['error' => 'Invalid Username or Password'])->withInput();
        }

        return view('customer.auth.sendOtp');
    }


    public function verifyOtp(Request $request)
    {

        // return 556;
        if ($request->method() == 'POST') {
            // Validate the input to ensure the OTP is provided
            $this->validate($request, [
                'otp' => 'required',
            ], [
                'otp.required' => 'The OTP field is required.',
            ]);

            // Convert the OTP array into a string
            $enteredOtp = implode('', $request->get('otp'));

            // Find the franchise by the username (generated_id)
            if ($franchise = ECustomer::where('email', $request->get('username'))->first()) {
                // Check if the entered OTP matches the saved OTP
                if ($franchise->verification_otp === $enteredOtp) {
                    Auth::guard('customer')->login($franchise);
                    // Redirect to the dashboard
                    return redirect()->route('customer.dashboard');
                } else {
                    return redirect()->route('customer.verifyOtp')->with([
                        'error' => 'Invalid OTP',
                        'otp_error' => 'Invalid OTP', // Separate key for OTP error
                        'username' => $request->get('username'), // Ensure username is passed back
                        'otp' => $franchise ? $franchise->verification_otp : null, // Pass OTP if franchise is found
                    ])->withInput();
                }
            }

            // If no user is found, return with an error message
            return redirect()->route('franchise.verifyOtp')->with(['error' => 'Invalid Username or Password'])->withInput();
        }

        return view('franchise.auth.verifyOtp', ['username' => null]);
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
     
         $franchise = ECustomer::where('status', 1)->get();

         if ($request->method() == 'POST') {

        
            $this->validate(
                $request,
                [
                    'name' => 'required',
                    'father_name' => 'required',
                    'mobile' => 'required|digits:10|unique:e_customers,mobile',
                    'email' => 'required|email|unique:e_customers,email',
                    'pincode' => 'required|digits:6',
                    'city' => 'required',
                    'district' => 'required',
                    'state' => 'required',
                    'address' => 'required',
                    'society_name' => 'required',
                    'location' => 'required',
                    'cph_link' => 'required',
                    
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
                'society_name',
                'sector',
                'pan_card',
                'ifsc_code',
                'bank_name',
                'branch_name',
                'account_number',
                'gst_number',
                'cph_link',
                'location',
            ]);

            try {
                $mobile = $request->mobile;
                $otp = rand(10000, 99999);

                // Prepare all data for registration, including files
                $allData = $registerData;

                $generatedId = $request->generated_id;

                $uploadDir = 'admin/franchise/' . $generatedId . '/';

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
                    $file7->move('admin/franchise/' . $generatedId . '/', $img7);
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

        return view('customer.auth.register', ['franchise' => $franchise]);
    }


    public function otpViewPage(Request $request)
    {
      
        return view('customer.auth.otpViewPageForPhoneVerification', ['otp' => $request->otp, 'mobile' => $request->mobile]);
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
                // Create new DeliveryBoy record
                // Create new Franchise record
                $post = new ECustomer();
                $post->customer_no = $this->generateRandomNumber();
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
                $post->society = $registerData['society_name'];
                $post->sector = $registerData['sector'];
                $post->gst_number = $registerData['gst_number'] ?? null;
                $post->cph_link = $registerData['cph_link'] ?? null;
                $post->location = $registerData['location'] ?? null;
                $post->generated_id = $registerData['generated_id'];
                $post->status = 1;
                // Generate a random password
                $password = substr(str_shuffle('0123456789'), 0, 10);
                $post->password = Hash::make($password);

                // Save the Franchise record
                $post->save();
                // Save KYC information
                if ($post) {
                    $kyc = new ECustomerKyc();
                    $kyc->e_customer_id = $post->id;
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

                // Config::set('mail.mailers.smtp.host', 'smtp.gmail.com');
                // Config::set('mail.mailers.smtp.port', 587);
                // Config::set('mail.mailers.smtp.username', 'snehalsharan10@gmail.com');
                // Config::set('mail.mailers.smtp.password', 'aipn fdol xxjb rshv');
                // Config::set('mail.mailers.smtp.encryption', 'tls');
                // Config::set('mail.from.address', 'deferfe1214@gmail.com');
                // Config::set('mail.from.name', 'gotogopost');

                // // Send registration email with the generated password
        
//email not working so muted

                // Mail::to($registerData['email'])->send(new RegistrationMail([
                //     'username' => $post->generated_id,
                //     'password' => $password,
                //     'route' => route('franchise.login')
                // ]));

                $uname = $registerData['email'];
                
                

                $notification = new SMSNotification($mobile, 'WELCOME', [$uname,$password]);
                $response = $notification->sendMessage();

                return redirect()->route('customer.login')->with('success', 'E-Customer Service created successfully! ID: ' . $post->email . ' Password: ' . $password);
                // return redirect()->route('franchise.login')->with('success', 'Franchise created successfully!');
            } catch (\Exception $th) {
                return back()->with('error', $th->getMessage())->withInput();
            }
        }

        return view('customer.auth.register');
    }




    public function resendPhoneNumberOtp(Request $request)
    {
      
        if ($request->method() == 'POST') {
            
            $this->validate($request, [
                'username' => 'required',
            ], [
                'username.required' => 'The username field is required.',
            ]);

            if ($franchise = ECustomer::where('email', $request->get('username'))->first()) {

                $franchise->verification_otp = rand(10000, 99999);
                $franchise->save();

                Mail::to($franchise->email)->send(new ForgotPasswordMail([
                    'otp' =>  $franchise->verification_otp,
                ]));


                return view('customer.auth.verifyOtp', ['otp' => $franchise->verification_otp, 'username' => $request->get('username')]);
            }

            return redirect()->back()->with(['error' => 'Invalid Username or Password'])->withInput();
        }

        return view('customer.auth.sendOtp');
    }

    public function update(Request $request, $id)
    {

        // return $request;

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
                $post = ECustomer::find($id);
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
                    $kyc = ECustomerKyc::where('e_customer_id', $id)->first();
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
                        $file1->move('admin/franchise/' . $post->generated_id . '/', $img1);
                        $kyc->adhar_front_img = $img1;
                    }
                    if ($request->hasFile('adhar_back_img')) {
                        $file2 = $request->file('adhar_back_img');
                        $extension2 = $file2->getClientOriginalName();
                        $img2 = time() . '_' . $extension2;
                        $file2->move('admin/franchise/' . $post->generated_id . '/', $img2);
                        $kyc->adhar_back_img = $img2;
                    }

                    if ($request->hasFile('pan_img')) {
                        $file3 = $request->file('pan_img');
                        $extension3 = $file3->getClientOriginalName();
                        $img3 = time() . '_' . $extension3;
                        $file3->move('admin/franchise/' . $post->generated_id . '/', $img3);
                        $kyc->pan_img = $img3;
                    }

                    if ($request->hasFile('cheque_img')) {
                        $file4 = $request->file('cheque_img');
                        $extension4 = $file4->getClientOriginalName();
                        $img4 = time() . '_' . $extension4;
                        $file4->move('admin/franchise/' . $post->generated_id . '/', $img4);
                        $kyc->cheque_img = $img4;
                    }
                    if ($request->hasFile('photo')) {
                        $file5 = $request->file('photo');
                        $extension5 = $file5->getClientOriginalName();
                        $img5 = time() . '_' . $extension5;
                        $file5->move('admin/franchise/' . $post->generated_id . '/', $img5);
                        $kyc->photo = $img5;
                    }
                    if ($request->hasFile('other_document')) {
                        $file6 = $request->file('other_document');
                        $extension6 = $file6->getClientOriginalName();
                        $img6 = time() . '_' . $extension6;
                        $file6->move('admin/franchise/' . $post->generated_id . '/', $img6);
                        $kyc->other_document = $img6;
                    }
                    if ($request->hasFile('video_kyc')) {
                        $file7 = $request->file('video_kyc');
                        $extension7 = $file7->getClientOriginalName();
                        $img7 = time() . '_' . $extension7;
                        $file7->move('admin/franchise/' . $post->generated_id . '/', $img7);
                        $kyc->video_kyc = $img7;
                    }
                    $kyc->save();
                    if (!$kyc) {
                        $post->delete();
                    }
                }

                return response()->json([
                    'success' => true,
                    'message' => 'Customer updated successfully!',
                ]);

                // return back()->with('success', 'Franchise updated successfully!');
            } catch (\Exception $th) {
                return back()->with('error', $th->getMessage())->withInput();
            }
        }

        return view('customer.auth.register');
    }

       public function getLocation(Request $request)
{
    // Validate the input
    $request->validate([
        'city' => 'required|string|max:255',
    ]);

    // Fetch CMS data matching city (prefix match)
    $locations = Franchise::where('city', 'LIKE', $request->city . '%')->get();

    // If no data found
    if ($locations->isEmpty()) {
        return response("<select name='cph_link' class='form-control'><option disabled>No Franchise found</option></select>");
    }

    // Start building the select HTML
    $html = "<select name='cph_link' class='form-control'>";
    $html .= "<option selected disabled>Select Franchise</option>"; // Optional default

    foreach ($locations as $location) {
        $html .= "<option value='{$location->id}'>" .
                    "{$location->name} - {$location->address}, {$location->city} - {$location->pincode}" .
                 "</option>";
    }

    $html .= "</select>";

    return response($html);
}

}
