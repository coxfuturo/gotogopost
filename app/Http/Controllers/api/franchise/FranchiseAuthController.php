<?php

namespace App\Http\Controllers\api\franchise;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Franchise;
use App\Models\FranchiseNotification;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use App\Mail\ForgotPasswordMail;
use Illuminate\Support\Facades\Mail;

class FranchiseAuthController extends Controller
{


    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required'
        ], [
            'username.required' => 'The username field is required.',
        ]);

        $credentials = [
            'generated_id' => $request->get('username'),
            'password' => $request->get('password')
        ];

        try {
            // Attempt to generate a token for the 'apiuser' guard
            if (!$token = Auth::guard('apifranchise')->attempt($credentials)) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Unauthorized',
                ], 401);
            }

            // Get the authenticated user
            $user = Auth::guard('apifranchise')->user();

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

        // Config::set('mail.mailers.smtp.host', 'smtp.gmail.com');
        // Config::set('mail.mailers.smtp.port', 587);
        // Config::set('mail.mailers.smtp.username', 'snehalsharan10@gmail.com');
        // Config::set('mail.mailers.smtp.password', 'aipn fdol xxjb rshv');
        // Config::set('mail.mailers.smtp.encryption', 'tls');
        // Config::set('mail.from.address', 'deferfe1214@gmail.com');
        // Config::set('mail.from.name', 'gotogopost');


        if ($request->method() == 'POST') {

            $this->validate($request, [
                'email' => 'required',
            ], [
                'email.required' => 'The email field is required.',
            ]);

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


    public function verifyOtp(Request $request)
    {
        if ($request->method() == 'POST') {
            // Validate the input to ensure the OTP is provided
            $this->validate($request, [
                'otp' => 'required',
            ], [
                'otp.required' => 'The OTP field is required.',
            ]);

            // Convert the OTP array into a string
            $enteredOtp = $request->get('otp');

            // Find the franchise by the username (generated_id)
            if ($franchise = Franchise::where('generated_id', $request->get('email'))->first()) {
                // Check if the entered OTP matches the saved OTP
                if ($franchise->verification_otp === $enteredOtp) {
                    Auth::guard('franchise')->login($franchise);
                    // Redirect to the dashboard

                    return response()->json([
                        'success' => true,
                        'message' => 'OTP verified successfully.',
                    ], 200);
                } else {
                    return response()->json([
                        'success' => false,
                        'message' => 'Invalid OTP.',
                        'otp' => $franchise->verification_otp,
                    ], 400);
                }
            }

            // If no user is found, return an error response
            return response()->json([
                'success' => false,
                'message' => 'User not found.',
            ], 404); // HTTP status 404 for user not found
        }
    }

   
}
