<?php

namespace App\Http\Controllers\api\delivered;

use App\Http\Controllers\Controller;
use App\Models\DeliveryBoy;
use App\Models\DeliveryBoyKyc;
use App\Models\DeliveryBoyNotification;
use App\Models\Franchise;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use App\Mail\ForgotPasswordMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use App\Mail\RegistrationMail;
use App\Notifications\RegisterPushNotification;
use App\Notifications\SMSNotification;
use App\Mail\SendOtpMail;
use GuzzleHttp\Client;

use App\SmsSerices;

class DeliveredAuthController extends Controller
{


    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required',
            'password' => 'required'
        ], [
            'username.required' => 'The username field is required.',
            'password.required' => 'The password field is required.'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors(),
                "showMessage" => 1
            ], 422);
        }


        $delboy = DeliveryBoy::where('generated_id', $request->get('username'))->first();


        if (!$delboy) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid username or password.',
                "showMessage" => 1
            ], 401);
        }


        if ($delboy->status == 0) {
            return response()->json([
                'success' => false,
                "active" => false,
                'message' => 'Your account is inactive.',
                "showMessage" => 1
            ], 403);
        }

        if ($delboy->status == 2) {

            return response()->json([
                'success' => false,
                "deleted" => true,
                'message' => 'Account is deleted',
                "showMessage" => 1
            ], 403);
        }


        $credentials = [
            'generated_id' => $request->get('username'),
            'password' => $request->get('password')
        ];

        try {
            // Attempt to generate a token for the 'apiuser' guard

            if (!$token = Auth::guard('apidelboy')->attempt($credentials)) {

                return response()->json([
                    'status' => 'error',
                    'message' => 'Unauthorized',
                    "showMessage" => 1
                ], 401);
            }

            // Get the authenticated user
            $user = Auth::guard('apidelboy')->user();

            $userNew = DeliveryBoy::where('id', $user->id)->with('kyc')->first();

            $user->image = $userNew->kyc->photo ?? null;

            return response()->json([
                'success' => true,
                'user' => $user,

                'authorization' => [
                    'token' => $token,
                    'type' => 'bearer',
                ],
            ]);
        } catch (JWTException $e) {
            // Log the error for debugging
            Log::error('JWTException: ' . $e->getMessage());

            // Return a detailed error response
            return response()->json([
                'success' => false,
                'message' => 'Could not create token',
                'error' => $e->getMessage(),
            ], 500);
        } catch (\Exception $e) {
            // Log unexpected errors
            Log::error('Exception: ' . $e->getMessage());

            // Return a general error response
            return response()->json([
                'success' => false,
                'message' => 'An unexpected error occurred',
                'error' => $e->getMessage(),
            ], 500);
        }
    }



    public function logout(Request $request)
    {

        try {
            // Invalidate the token
            JWTAuth::invalidate(JWTAuth::getToken());

            return response()->json([
                'success' => true,
                'message' => 'Logout successful',
            ], 200);
        } catch (JWTException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to logout, please try again.',
            ], 500);
        }
    }

    public function sendOtp(Request $request)
    {

        if ($request->method() == 'POST') {

            $validator = Validator::make($request->all(), [
                'email' => 'required',
            ], [
                'email.required' => 'The email field is required.',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => $validator->errors(),
                    "showMessage" => 1
                ], 422);
            }

            if ($franchise = Franchise::where('generated_id', $request->get('email'))->first()) {

                $franchise->verification_otp = rand(10000, 99999);
                $franchise->save();

                Mail::to($franchise->email)->send(new ForgotPasswordMail([
                    'otp' =>  $franchise->verification_otp,
                ]));

                // Mail::to('fuloriadeepak999@gmail.com')->send(new ForgotPasswordMail([
                //     'otp' =>  $franchise->verification_otp,
                // ]));
                return response()->json([
                    'success' => true,
                    'message' => 'OTP has been sent to your email.',
                    'otp' => $franchise->verification_otp,
                    'email' => $request->get('email'),
                ], 200);
            }

            return response()->json([
                'success' => false,
                'message' => 'Invalid email address.',
            ], 404);
        }


        return response()->json([
            'success' => false,
            'message' => 'Invalid request method.',
        ], 405);
    }


    public function sendRegisterNotification($deliveryBoy, $password)
    {
        try {
            \Log::info("Register notification process started");

            $token = $deliveryBoy->fcm_token;

            if (!$token) {
                \Log::warning("FCM token not found for delivery boy");
                return;
            }

            \Log::info("FCM token found", ['token' => $token]);

            // Prepare Notification
            $title = "Welcome to GOTOGO Post!";
            $body = "🎉 Congratulations {$deliveryBoy->name},\n\n" .
                "You have been successfully registered as a Delivery Boy.\n\n" .
                "📌 Username: {$deliveryBoy->generated_id}\n" .
                "🔑 Password: {$password}\n\n" .
                "🚀 Login now and start delivering orders!";

            \Log::info("Sending push notification", ['title' => $title, 'body' => $body]);

            // Send Push Notification
            $notification = new RegisterPushNotification($token, $title, $body);
            $status = $notification->sendPushNotification();

            \Log::info("Notification status", ['status' => $status]);

            if ($status == 0) {
                return;
            }

            // Store Notification in Database
            DeliveryBoyNotification::create([
                'delivery_boy_id' => $deliveryBoy->id,
                'message' => "Delivery Boy registration is complete.",
                'body' => $body,
                'current_location' => $deliveryBoy->address,
            ]);

            \Log::info("Delivery Boy registration notification saved in database", [
                'delivery_boy_id' => $deliveryBoy->id,
            ]);
        } catch (\Exception $e) {
            \Log::error("Error in sendRegisterNotification", [
                'delivery_boy_id' => $deliveryBoy->id ?? 'N/A',
                'error' => $e->getMessage(),
                'exception' => $e
            ]);
        }
    }


    public function sendOtpNotification($deliveryBoy, $password)
    {
        try {
            \Log::info("Register notification process started");

            $token = $deliveryBoy->fcm_token;

            if (!$token) {
                \Log::warning("FCM token not found for delivery boy");
                return;
            }

            \Log::info("FCM token found", ['token' => $token]);

            // Prepare Notification
            $title = "Welcome to GOTOGO Post!";
            $body = "🔐 OTP Verification\n\n" .
                "Hello {$deliveryBoy->name},\n\n" .
                "Your OTP for verification is: {$password}.\n\n" .
                "⚠️ Please do not share this OTP with anyone.\n\n" .
                "✅ Enter the OTP to complete your verification process.\n\n" .
                "Thank you!";


            \Log::info("Sending push notification", ['title' => $title, 'body' => $body]);

            // Send Push Notification
            $notification = new RegisterPushNotification($token, $title, $body);
            $status = $notification->sendPushNotification();

            \Log::info("Notification status", ['status' => $status]);

            if ($status == 0) {
                return;
            }

            // Store Notification in Database
            DeliveryBoyNotification::create([
                'delivery_boy_id' => $deliveryBoy->id,
                'message' => "Otp sent successfully",
                'body' => $body,
                'current_location' => $deliveryBoy->address,
            ]);

            \Log::info("Delivery Boy registration notification saved in database", [
                'delivery_boy_id' => $deliveryBoy->id,
            ]);
        } catch (\Exception $e) {
            \Log::error("Error in sendRegisterNotification", [
                'delivery_boy_id' => $deliveryBoy->id ?? 'N/A',
                'error' => $e->getMessage(),
                'exception' => $e
            ]);
        }
    }

    public function register(Request $request)
    {
        $franchise = Franchise::where('status', 1)->get();

        if ($request->method() == 'POST') {
            $validator = Validator::make($request->all(), [
                'name' => 'required',
                'father_name' => 'required',
                'mobile' => 'required|digits:10|unique:delivery_boys,mobile',
                'email' => 'required|email',
                'pincode' => 'required|digits:6',
                'city' => 'required',
                'district' => 'required',
                'state' => 'required',
                'address' => 'required',
                'age' => 'required',
                'gender' => 'required',
                'pan_number' => 'required',
                'driving_licence' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => $validator->errors(),
                    'showMessage' => 1,
                ], 422);
            }

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
                'fcm_token',
                'age',
                'gender',
                'pan_number',
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

                if ($request->hasFile('driving_licence')) {
                    $file6 = $request->file('driving_licence');
                    $extension6 = $file5->getClientOriginalName();
                    $img6 = time() . '_' . $extension6;
                    $file6->move(public_path('tenancy/assets/delboy/' . $generatedId), $img6);
                    $allData['driving_licence'] = $img6;
                }

                \Cache::put('register_data_' . $mobile, $allData, now()->addMinutes(10));
                \Cache::put('otp_' . $mobile, $otp, now()->addMinutes(10));


                $notification = new SMSNotification($registerData['mobile'], 'OTP', [$otp]);
                $response = $notification->sendMessage();

                return response()->json([
                    'success' => true,
                    'message' => "Otp sent successfully",
                    'otp' => $otp,
                    'mobile' => $mobile,
                ]);
            } catch (\Exception $th) {

                if ($validator->fails()) {
                    return response()->json([
                        'success' => false,
                        'errors' => $validator->errors(),
                    ], 422);
                }
            }
        }
    }


    public function validatePrimery(Request $request)
    {
        if ($request->isMethod('post')) {
            $validator = Validator::make($request->all(), [
                'name' => 'required',
                'father_name' => 'required',
                'mobile' => 'required|digits:10|unique:delivery_boys,mobile',
                'email' => 'required|email|unique:delivery_boys,email',
                'gender' => 'required',
                'age' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => $validator->errors(),
                    'showMessage' => 1,
                ], 422);
            }

            return response()->json([
                'success' => true,
                'message' => 'Primary data validated successfully.',
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Invalid request method.',
        ], 405);
    }


    public function validateSecondary(Request $request)
    {
        if ($request->isMethod('post')) {
            $validator = Validator::make($request->all(), [
                'pan_number' => 'required|unique:delivery_boys,pan_number',
                'adhar_card' => 'required|unique:delivery_boys,adhar_card',
                'driving_licence' => 'required|unique:delivery_boys,driving_licence',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => $validator->errors(),
                    'showMessage' => 1,
                ], 422);
            }

            return response()->json([
                'success' => true,
                'message' => 'Secondary data validated successfully.',
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Invalid request method.',
        ], 405);
    }


    public function verifyPhoneNumberOtp(Request $request)
    {

        $franchise = Franchise::where('status', 1)->get();

        if ($request->method() == 'POST') {
            // Get OTP entered by the user
            $enteredOtp = $request->get('otp');


            $mobile = $request->get('mobile');

            if (!$mobile) {
                return response()->json([
                    'status' => false,
                    'message' => 'Mobile number missing.',
                    "showMessage" => 1
                ], 400);
            }

            // Retrieve the OTP and register data from the cache
            $cachedOtp = \Cache::get('otp_' . $mobile);
            $registerData = \Cache::get('register_data_' . $mobile);


            // Check if OTP or register data is missing or expired
            if (!$cachedOtp || !$registerData) {
                return response()->json([
                    'status' => false,
                    'message' => 'OTP expired or invalid, please try again.',
                    "showMessage" => 1
                ], 400);
            }

            // Verify OTP
            if ($cachedOtp != $enteredOtp) {
                return response()->json([
                    'status' => false,
                    'message' => 'Invalid OTP, please enter the correct OTP.',
                    "showMessage" => 1
                ], 400);
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
                $post->pan_number = $registerData['pan_number'];
                $post->fcm_token = $registerData['fcm_token'];
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

                    if (isset($registerData['driving_licence'])) {

                        $kyc->driving_licence_img = $registerData['driving_licence'];
                    }
                    $kyc->save();
                    // If KYC fails, delete the DeliveryBoy record
                    if (!$kyc) {
                        $post->delete();
                    }
                }

                // Generate and cache a new OTP for the next step if needed
                // $otp = rand(10000, 99999);
                // \Cache::put('otp_' . $request->phone, $otp, now()->addMinutes(10));

                // Send registration email with the generated password
                Mail::to($registerData['email'])->send(new RegistrationMail([
                    'username' => $post->generated_id,
                    'password' => $password,
                    'route' => route('deliveryBoy.login')
                ]));


                $post->password = $password;
                return response()->json([
                    'success' => true,
                    'message' => "Delivery Boy created successfully!",
                    'user' => $post,
                ]);
            } catch (\Exception $th) {

                return response()->json([
                    'success' => false,
                    'error' => $th->getMessage(),
                ]);
            }
        }

        return view('deliveryBoy.auth.register', ['franchise' => $franchise]);
    }


    public function allFranchise(Request $request)
    {
        try {

            $franchise = Franchise::where('status', 1)->get();
            return response()->json([
                'success' => true,
                'franchise' =>  $franchise,
            ], 200);
        } catch (JWTException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to get franchise, please try again.',
            ], 500);
        }
    }


    public function forgotPassword(Request $request)
    {


        if ($request->method() == 'POST') {

            $validator = Validator::make($request->all(), [
                'mobile' => 'required',
            ], [
                'mobile.required' => 'The mobile field is required.',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => $validator->errors(),
                    "showMessage" => 1
                ], 422);
            }

            if ($user = DeliveryBoy::where('mobile', $request->get('mobile'))->first()) {

                $phone = $request->get('mobile');
                $otp = rand(10000, 99999);
                $user->verification_otp = $otp;
                $user->save();

                Mail::to($user->email)->send(new ForgotPasswordMail([
                    'otp' =>  $user->verification_code,
                ]));


                $notification = new SMSNotification($phone, 'OTP', [$otp]);
                $response = $notification->sendMessage();

                return response()->json([
                    'success' => true,
                    'message' => 'OTP has been sent to your phone.',
                    'otp' => $otp,
                    'mobile' => $phone,

                ], 200);
            }

            return response()->json([
                'success' => false,
                'message' => 'Invalid phone number.',
            ], 404);
        }

        return response()->json([
            'success' => false,
            'message' => 'Invalid request method.',
        ], 405);
    }


    public function verifyOtp(Request $request)
    {
        // Validate input
        $validator = \Validator::make($request->all(), [
            'otp' => 'required',
            'mobile' => 'required|exists:delivery_boys,mobile',
        ], [
            'otp.required' => 'The OTP field is required.',
            'mobile.required' => 'The phone field is required.',
            'mobile.exists' => 'No delivery boy found with this phone number.',
        ]);

        // Check validation
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors(),
                "showMessage" => 1
            ], 422);
        }

        // Get the entered OTP
        $enteredOtp = $request->get('otp');

        // Find the delivery boy by phone
        $deliveryBoy = DeliveryBoy::where('mobile', $request->get('mobile'))->first();

        if ($deliveryBoy) {
            // Check if OTP is correct
            if ($deliveryBoy->verification_otp === $enteredOtp) {
                // Clear the OTP
                $deliveryBoy->verification_otp = null;
                $deliveryBoy->save();

                // Manually create the token for delivery boy
                $token = Auth::guard('apidelboy')->login($deliveryBoy);

                return response()->json([
                    'success' => true,
                    'message' => 'OTP verified successfully. Please reset your password.',
                    'token' => $token,
                    'user' => $deliveryBoy
                ], 200);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid OTP.',
                ], 400);
            }
        }

        return response()->json([
            'success' => false,
            'message' => 'Delivery boy not found.',
        ], 404);
    }

    //reset password for user
    public function resetPassword(Request $request)
    {
        // Validate new password
        $validator = \Validator::make($request->all(), [
            'new_password' => 'required|min:6',
            'confirm_password' => 'required|same:new_password',
        ]);

        // Check validation
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors(),
                "showMessage" => 1
            ], 422);
        }

        // Get the authenticated user
        $user = Auth::guard('apidelboy')->user();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized.',
                "showMessage" => 1
            ], 401);
        }

        // Update password
        $user->password = Hash::make($request->new_password);
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'Password reset successfully. You are now logged in.',
        ], 200);
    }


    public function updateToken(Request $request)
    {

        if ($request->method() == 'POST') {

            $validator = Validator::make($request->all(), [
                'fcm_token' => 'required',
            ], [
                'fcm_token.required' => 'The FCM token field is required.',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => $validator->errors(),
                    "showMessage" => 1
                ], 422);
            }

            $user = Auth::guard('apidelboy')->user();
            $user->fcm_token = $request->fcm_token;
            $user->save();

            return response()->json([
                'success' => true,
                'message' => 'updated successfully',
            ], 200);
        }

        return response()->json([
            'success' => false,
            'message' => 'Invalid request method.',
        ], 405);
    }


    public function update(Request $request, $id)
    {
        if ($request->method() !== 'POST') {
            return response()->json([
                'success' => false,
                'message' => 'Invalid request method',
            ], 405);
        }

        // Validation
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:191',
            'mobile' => 'required|digits:10',
            'email' => 'required|email',
            'pincode' => 'required|digits:6',
            'address' => 'required|string',
            'gender' => 'nullable|string',
            'password' => 'nullable|string|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation errors',
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            $post = DeliveryBoy::find($id);
            if (!$post) {
                return response()->json([
                    'success' => false,
                    'message' => 'Delivery Boy not found',
                ], 404);
            }

            // Updating details
            $post->name = $request->name;
            $post->mobile = $request->mobile;
            $post->email = $request->email;
            $post->pincode = $request->pincode;
            $post->address = $request->address;
            $post->gender = $request->gender;

            if (!empty($request->password)) {
                $post->password = Hash::make($request->password);
            }

            $post->save();

            // Handle KYC record
            $kyc = DeliveryBoyKyc::where('delivery_boy_id', $id)->first();
            if (!$kyc) {
                $kyc = new DeliveryBoyKyc();
                $kyc->delivery_boy_id = $id;
            }

            if ($request->hasFile('photo')) {
                $file = $request->file('photo');
                $filename = time() . '_' . $file->getClientOriginalName();
                $file->move(public_path('tenancy/assets/delboy/' . $post->generated_id), $filename);
                $kyc->photo = $filename;
            }

            $kyc->save();

            return response()->json([
                'success' => true,
                'message' => 'Delivery Boy updated successfully!',
                'data' => [
                    'delivery_boy' => $post,
                    'kyc' => $kyc,
                ]
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong!',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
