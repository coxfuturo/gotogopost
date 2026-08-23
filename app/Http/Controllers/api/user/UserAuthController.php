<?php

namespace App\Http\Controllers\api\user;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Support\Facades\Validator;
use App\Mail\ForgotPasswordMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use DB;
use App\Notifications\SMSNotification;
use Illuminate\Support\Facades\Config;

class UserAuthController extends Controller

{


    public function register(Request $request)
    {


        if ($request->isMethod('post')) {
            $validator = Validator::make($request->all(), [
                'name' => 'required',
                'phone' => 'required|digits:10|unique:users',
                'email' => 'required|email|unique:users',
                'pincode' => 'required|digits:6',
                'address' => 'required',
                'password' => 'required',
                // 'confirm' => 'required', 
            ]);


            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => $validator->errors(),
                    'showMessage' => 1
                ], 422);
            }

            // // Collect the necessary data for registration
            $registerData = $request->only([
                'name',
                'phone',
                'email',
                'pincode',
                'address',
                'password',
                'verification_code',
                'fcm_token'
            ]);


            try {
                $mobile = $request->phone;
                $otp = rand(10000, 99999);

                // Hash the password before saving
                $registerData['verification_code'] = $otp;

                $registerData['password'] = Hash::make($registerData['password']);

                \Cache::put('register_data_' . $mobile, $registerData, now()->addMinutes(10));
                \Cache::put('otp_' . $mobile, $otp, now()->addMinutes(10));

                // // Send OTP email
                // Mail::to($request->email)->send(new ForgotPasswordMail([
                //     'otp' =>  $otp,
                // ]));

                // Send OTP SMS notification
                $notification = new SMSNotification($mobile, 'OTP', [$otp]);
                $response = $notification->sendMessage();

                return response()->json([
                    'success' => true,
                    'message' => "Otp sent successfully",
                    'otp' => $otp,
                    'mobile' => $mobile,
                ]);
            } catch (\Exception $e) {
                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage(),
                ], 500);
            }
        }

        return response()->json([
            'success' => false,
            'message' => 'Invalid request method'
        ], 405);
    }

    // OTP verification for new user registration
    public function verify(Request $request)
    {

        // Validate request data
        $request->validate([
            'otp' => 'required',
            'mobile' => 'required'
        ]);

        // Get OTP and mobile number from request
        $enteredOtp = $request->input('otp');
        $mobile = $request->input('mobile');

        // Retrieve the OTP and register data from the cache
        $cachedOtp = \Cache::get('otp_' . $mobile);
        $registerData = \Cache::get('register_data_' . $mobile);

        Log::info('Checking OTP and register data for mobile: ' . $mobile);

        // Check if OTP or register data is missing or expired
        if (!$cachedOtp || !$registerData) {
            return response()->json([
                'success' => false,
                'message' => 'OTP expired or invalid, please try again.',
                'showMessage' => 1
            ], 400);
        }

        // Verify OTP
        if ($cachedOtp != $enteredOtp) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid OTP, please enter the correct OTP.',
                'showMessage' => 1
            ], 400);
        }

        try {
            Log::info('OTP verified successfully, creating new User.');

            // Create new User record
            $post = new User();
            $post->name = $registerData['name'];
            $post->phone = $registerData['phone'];
            $post->email = $registerData['email'];
            $post->pincode = $registerData['pincode'];
            $post->address = $registerData['address'];
            $post->fcm_token = $registerData['fcm_token'];
            $post->status = 1;
            $post->password = $registerData['password']; // Hash the password
            $post->save();

            // Clear OTP and registration data from cache
            \Cache::forget('otp_' . $mobile);
            \Cache::forget('register_data_' . $mobile);

            return response()->json([
                'success' => true,
                'message' => "User created successfully!"
            ]);
        } catch (\Exception $th) {
            Log::error('Error occurred while creating user: ' . $th->getMessage());
            return response()->json([
                'success' => false,
                'error' => 'An error occurred. Please try again.'
            ], 500);
        }
    }


    public function login(Request $request)
    {

        // Validate the request
        $request->validate([
            'phone' => 'required',
            'password' => 'required',
        ]);

        $credentials = $request->only('phone', 'password');

        try {
            // Attempt to generate a token for the 'apiuser' guard
            if (!$token = Auth::guard('apiuser')->attempt($credentials)) {

                return response()->json([
                    'status' => 'error',
                    'message' => 'Unauthorized',
                    'showMessage' => 1
                ], 401);
            }

            // Get the authenticated user
            $user = Auth::guard('apiuser')->user();

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



    public function logout()
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






    public function update(Request $request)

    {

        // Validate the request data
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:191',
            'pincode' => 'required',
            'address' => 'required',
            'phone' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation errors',
                'errors' => $validator->errors(),
            ], 422);
        }


        try {

            $user = Auth::guard('apiuser')->user();

            $user->name = $request->name;
            // $user->email = $request->email;
            $user->pincode = $request->pincode;
            $user->address = $request->address;
            $user->phone = $request->phone;

            $image = null;
            if ($request->hasFile('image')) {
                $file = $request->file('image');
                $extension = $file->getClientOriginalExtension();
                $img = time() . '.' . $extension;
                $file->move(public_path('tenancy/assets/admin/User/'), $img);
                $image = $img;
                $user->image = $image;
            }
            if ($request->password) {
                $user->password = Hash::make($request->password);
            }

            $user->save();
            $updatedUser = Auth::guard('apiuser')->user();

            return response()->json([
                'success' => true,
                'message' => 'User updated successfully',
                'data' => $updatedUser,
                'imageUrl' => $updatedUser->image ? asset('tenancy/assets/admin/User/' . $updatedUser->image) : null,
            ], 200);
        } catch (\Exception $th) {

            return back()->with('error', $th->getMessage());
        }
    }



    public function delete($id)

    {

        try {

            User::findorfail($id)->delete();

            return back()->with('success', 'User Deleted Successfully');
        } catch (\Throwable $th) {

            return back()->with('error', $th->getMessage());
        }
    }



    public function status_update(Request $request)

    {

        try {

            $user = User::findorfail($request->id);

            $status = $request->status;

            $user->status = $status;

            $user->save();

            return response()->json(['success' => true, 'status' => $user->status]);
        } catch (\Throwable $th) {

            return response()->json(['success' => false]);
        }
    }


    public function forgotPassword(Request $request)
    {


        if ($request->method() == 'POST') {

            $this->validate($request, [
                'phone' => 'required',
            ]);

            if ($user = User::where('phone', $request->get('phone'))->first()) {

                $phone = $request->get('phone');
                $otp = rand(10000, 99999);
                $user->verification_code = $otp;
                $user->save();

                Mail::to($user->email)->send(new ForgotPasswordMail([
                    'otp' =>  $user->verification_code,
                ]));

                // Mail::to('fuloriadeepak999@gmail.com')->send(new ForgotPasswordMail([
                //     'otp' =>  $user->verification_otp,
                // ]));

                $notification = new SMSNotification($phone, 'OTP', [$otp]);
                $response = $notification->sendMessage();
                return response()->json([
                    'success' => true,
                    'message' => 'OTP has been sent to your phone.',
                    'otp' => $otp,
                    'phone' => $phone,

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


    public function updateToken(Request $request)
    {

        if ($request->method() == 'POST') {

            $this->validate($request, [
                'fcm_token' => 'required',
            ]);

            $user = Auth::guard('apiuser')->user();
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



    public function verifyOtp(Request $request)
    {
        // Validate input
        $validator = \Validator::make($request->all(), [
            'otp' => 'required',
            'phone' => 'required|exists:users,phone',
        ], [
            'otp.required' => 'The OTP field is required.',
            'phone.required' => 'The phone field is required.',
            'phone.exists' => 'No user found with this phone number.',
        ]);

        // Check validation
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed.',
                'errors' => $validator->errors(),
            ], 422);
        }

        // Get the entered OTP
        $enteredOtp = $request->get('otp');

        // Find the user by phone
        $user = User::where('phone', $request->get('phone'))->first();

        if ($user) {
            // Check if OTP is correct
            if ($user->verification_code === $enteredOtp) {
                // Clear the OTP
                $user->verification_code = null;
                $user->save();

                // Manually create the token
                $token = JWTAuth::fromUser($user);

                return response()->json([
                    'success' => true,
                    'message' => 'OTP verified successfully. Please reset your password.',
                    'token' => $token,
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
            'message' => 'User not found.',
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
                'message' => 'Validation failed.',
                'errors' => $validator->errors(),
            ], 422);
        }

        // Get the authenticated user
        $user = Auth::guard('apiuser')->user();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized.',
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

    public function updatePassword(Request $request)
    {
        // Validate the request data
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email', // Ensure the email exists in the database
            'password' => 'required', // Add password confirmation
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation errors',
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            // Find the user by email
            $user = User::where('email', $request->email)->first();

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not found',
                ], 404);
            }

            // Update the password
            $user->password = bcrypt($request->password); // Hash the new password
            $user->save();

            return response()->json([
                'success' => true,
                'message' => 'Password updated successfully',
            ], 200);
        } catch (\Exception $th) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred',
                'error' => $th->getMessage(),
            ], 500);
        }
    }

    public function sendMessageForOtp($phone, $otp)
    {
        try {
            \Log::info("Registration notification process started");

            $phone = $phone['phone']; // Assuming 'mobile' is the correct field
            if (!$phone) {
                \Log::warning("Mobile number not found for user");
                return;
            }

            \Log::info("Mobile number found", ['mobile' => $phone]);

            // Call SMSNotification with correct parameters
            $notification = new SMSNotification($phone, 'OTP', [$otp]);
            $response = $notification->sendMessage();

            \Log::info("SMS Notification response", ['response' => $response]);

            if (isset($response['error'])) {
                \Log::warning("SMS Notification failed", ['error' => $response['error']]);
                return;
            }

            \Log::info("SMS sent successfully", ['status' => 'Success']);
        } catch (\Exception $e) {
            \Log::error("Error in sendMessageForOtp", [
                'mobile' => $phone ?? 'N/A',
                'error' => $e->getMessage(),
                'exception' => $e
            ]);
        }
    }

    public function deleteUserAccount(Request $request)
    {
        try {
            $user = Auth::guard('apiuser')->user();

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not found or unauthorized',
                ], 404);
            }
            $user->delete();
            JWTAuth::invalidate(JWTAuth::getToken());

            return response()->json([
                'success' => true,
                'message' => 'User deleted successfully',
            ]);
        } catch (\Exception $e) {
            Log::error('Exception: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'An unexpected error occurred',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
