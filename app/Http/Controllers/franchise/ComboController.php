<?php

namespace App\Http\Controllers\franchise;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Franchise;
use App\Models\Admin;
use App\Models\CMS;
use App\Models\PPH;
use App\Models\CMSKyc;
use App\Models\CMSPayment;
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
use App\Models\FranchiseKyc;
use App\Models\FranchisePayment;
use Razorpay\Api\Api;
use Illuminate\Support\Arr;
use Config;
use DB;
use App\Rules\UniqueAcrossFranchiseAndCMS;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use App\Notifications\SMSNotification;

class ComboController extends Controller
{

     public function generateRandomNumber()
    {
        do {
            $randomNumber = rand(1, 99999);
            $formattedNumber = str_pad($randomNumber, 5, '0', STR_PAD_LEFT);
            $exists = Franchise::where('franchise_no', $formattedNumber)->exists();
        } while ($exists);

        return $formattedNumber;
    }

    public function index(){
        // return $franchise = Franchise::all();
        // return $franchise = CMS::all();
        // return $franchise = CMS::where('status',1)->get();
        // return CMSPayment::all();

    return view('franchise.combo.index');
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


   public function store(Request $request)
{
    $franchise = Franchise::where('status', 1)->get();

    if ($request->isMethod('POST')) {

        $request->validate([
            'name'         => 'required',
            'father_name'  => 'required',
            'mobile'       => ['required', 'digits:10', new UniqueAcrossFranchiseAndCMS('mobile')],
            'email'        => ['required', 'email', new UniqueAcrossFranchiseAndCMS('email')],
            'pincode'      => 'required|digits:6',
            'city'         => 'required',
            'district'     => 'required',
            'state'        => 'required',
            'address'      => 'required',
            'society_name' => 'required',
            'pph_link'     => 'required',
            'gender'       => 'required',
            'age'          => 'required',
            'natality'     => 'required',
        ]);

        // Get validated input
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
            'pph_link',
            'gender',
            'age',
            'natality',
        ]);

        try {
            $mobile = $request->mobile;
            $otp = rand(10000, 99999);
            $generatedId = $request->generated_id;
            $uploadDir = 'admin/franchise/' . $generatedId . '/';

            $allData = $registerData;

            // File uploads
            $fileFields = [
                'adhar_front_img',
                'adhar_back_img',
                'pan_img',
                'cheque_img',
                'photo',
                'other_document',
            ];

            foreach ($fileFields as $field) {
                if ($request->hasFile($field)) {
                    $allData[$field] = $this->uploadFiles($request->file($field), $uploadDir);
                }
            }

            // Video KYC - special handling
            if ($request->hasFile('video_kyc')) {
                $video = $request->file('video_kyc');
                $videoName = time() . '_' . $video->getClientOriginalName();
                $video->move($uploadDir, $videoName);
                $allData['video_kyc'] = $videoName;
            }

            // Store in cache
            Cache::put('register_data_' . $mobile, $allData, now()->addMinutes(10));
            Cache::put('otp_' . $mobile, $otp, now()->addMinutes(10));

            // Send OTP via SMSNotification class
            $notification = new SMSNotification($mobile, 'OTP', [$otp]);
            $response = $notification->sendMessage();

            // Log success
            Log::info("OTP sent successfully to $mobile", [
                'otp' => $otp,
                'response' => $response,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'OTP sent successfully',
                'data' => [
                    'otp' => $otp,
                    'mobile' => $mobile,
                ]
            ]);

        } catch (\Throwable $th) {
            // Log full error
            Log::error('Registration failed', [
                'error' => $th->getMessage(),
                'trace' => $th->getTraceAsString(),
            ]);

            return back()->with('error', 'Something went wrong. Please try again.')->withInput();
        }
    }


        return view('franchise.combo.index', ['franchise' => $franchise]);
    }


    public function otpViewPage(Request $request)
    {
        return view('franchise.combo.otpViewPageForPhoneVerification', ['otp' => $request->otp, 'mobile' => $request->mobile]);
    }

    public function verifyPhoneNumberOtp(Request $request)
{
    if ($request->isMethod('post')) {
        \Log::info('OTP verification request received.');

        try {
            $enteredOtp = implode('', $request->get('otp'));
            $mobile = $request->get('mobile');

            if (!$mobile) {
                \Log::warning('Mobile number missing in request.');
                return back()->with('error', 'Mobile number missing.');
            }

            $cachedOtp = \Cache::get('otp_' . $mobile);
            $registerData = \Cache::get('register_data_' . $mobile);

            \Log::info('Entered OTP: ' . $enteredOtp);
            \Log::info('Cached OTP: ' . $cachedOtp);

            if (!$cachedOtp || !$registerData) {
                \Log::warning('OTP or register data missing or expired.', ['mobile' => $mobile]);
                return back()->with('error', 'OTP expired or invalid, please try again.');
            }

            if ($cachedOtp != $enteredOtp) {
                \Log::warning('Invalid OTP entered.', ['entered' => $enteredOtp, 'expected' => $cachedOtp]);
                return back()->with('error', 'Invalid OTP, please enter the correct OTP.');
            }

            // Generate password
            $password = substr(str_shuffle('0123456789'), 0, 10);

            // Create CMS
            $cms = new CMS();
            $cms->cms_no = $this->generateRandomNumber();
            $cms->name = implode(',', $registerData['name']);
            $cms->register_type = $registerData['register_type'];
            $cms->father_name = $registerData['father_name'];
            $cms->mobile = $registerData['mobile'];
            $cms->email = $registerData['email'];
            $cms->pincode = $registerData['pincode'];
            $cms->city = $registerData['city'];
            $cms->district = $registerData['district'];
            $cms->state = $registerData['state'];
            $cms->address = $registerData['address'];
            $cms->latitude = $registerData['latitude'] ?? null;
            $cms->longitude = $registerData['longitude'] ?? null;
            $cms->cph_link = $registerData['pph_link'] ?? null;
            $cms->location = $registerData['city'] ?? null;
            $cms->natality = $registerData['natality'];
            $cms->age = $registerData['age'];
            $cms->gender = $registerData['gender'];
            $cms->generated_id = $registerData['generated_id'];
            $cms->gst_number = $registerData['gst_number'];
            $cms->package = 1;
            $cms->password = Hash::make($password);

            if (!$cms->save()) {
                \Log::error('Failed to save CMS.', ['data' => $registerData]);
                return back()->with('error', 'Failed to save CMS data.');
            }

            // CMS KYC
            $cmsKyc = new CMSKyc();
            $cmsKyc->cms_id = $cms->id;
            $cmsKyc->adhar_card = $registerData['adhar_card'] ?? null;
            $cmsKyc->pan_card = $registerData['pan_card'] ?? null;
            $cmsKyc->ifsc_code = $registerData['ifsc_code'] ?? null;
            $cmsKyc->bank_name = $registerData['bank_name'] ?? null;
            $cmsKyc->branch_name = $registerData['branch_name'] ?? null;
            $cmsKyc->account_number = $registerData['account_number'] ?? null;
            $cmsKyc->status = 2;

            // Optional file/image fields
            $cmsKyc->adhar_front_img = $registerData['adhar_front_img'] ?? null;
            $cmsKyc->adhar_back_img = $registerData['adhar_back_img'] ?? null;
            $cmsKyc->pan_img = $registerData['pan_img'] ?? null;
            $cmsKyc->cheque_img = $registerData['cheque_img'] ?? null;
            $cmsKyc->photo = $registerData['photo'] ?? null;
            $cmsKyc->other_document = $registerData['other_document'] ?? null;
            $cmsKyc->video_kyc = $registerData['video_kyc'] ?? null;

            if (!$cmsKyc->save()) {
                $cms->delete(); // Rollback
                \Log::error('Failed to save CMSKyc. Rolled back CMS.');
                return back()->with('error', 'Failed to save CMS KYC data.');
            }

            // Create Franchise
            $franchise = new Franchise();
            $franchise->franchise_no = $this->generateRandomNumber();
            $franchise->name = implode(',', $registerData['name']);
            $franchise->register_type = $registerData['register_type'];
            $franchise->father_name = $registerData['father_name'];
            $franchise->mobile = $registerData['mobile'];
            $franchise->email = $registerData['email'];
            $franchise->pincode = $registerData['pincode'];
            $franchise->city = $registerData['city'];
            $franchise->district = $registerData['district'];
            $franchise->state = $registerData['state'];
            $franchise->address = $registerData['address'];
            $franchise->latitude = $registerData['latitude'] ?? null;
            $franchise->longitude = $registerData['longitude'] ?? null;
            $franchise->society = $registerData['society_name'];
            $franchise->sector = $registerData['sector'];
            $franchise->natality = $registerData['natality'] ?? null;
            $franchise->age = $registerData['age'];
            $franchise->gender = $registerData['gender'];
            $franchise->generated_id = $registerData['generated_id'];
            $franchise->gst_number = $registerData['gst_number'];
            $franchise->cph_link = $cms->id ?? null;
            $franchise->location = $registerData['city'] ?? null;
            $franchise->package = 1;
            $franchise->password = Hash::make($password);

            if (!$franchise->save()) {
                \Log::error('Failed to save Franchise.', ['data' => $registerData]);
                return back()->with('error', 'Failed to save franchise data.');
            }

            // Franchise KYC
            $franchiseKyc = new FranchiseKyc();
            $franchiseKyc->franchise_id = $franchise->id;
            $franchiseKyc->adhar_card = $registerData['adhar_card'] ?? null;
            $franchiseKyc->pan_card = $registerData['pan_card'] ?? null;
            $franchiseKyc->ifsc_code = $registerData['ifsc_code'] ?? null;
            $franchiseKyc->bank_name = $registerData['bank_name'] ?? null;
            $franchiseKyc->branch_name = $registerData['branch_name'] ?? null;
            $franchiseKyc->account_number = $registerData['account_number'] ?? null;
            $franchiseKyc->status = 2;

            $franchiseKyc->adhar_front_img = $registerData['adhar_front_img'] ?? null;
            $franchiseKyc->adhar_back_img = $registerData['adhar_back_img'] ?? null;
            $franchiseKyc->pan_img = $registerData['pan_img'] ?? null;
            $franchiseKyc->cheque_img = $registerData['cheque_img'] ?? null;
            $franchiseKyc->photo = $registerData['photo'] ?? null;
            $franchiseKyc->other_document = $registerData['other_document'] ?? null;
            $franchiseKyc->video_kyc = $registerData['video_kyc'] ?? null;

            if (!$franchiseKyc->save()) {
                $franchise->delete();
                \Log::error('Failed to save FranchiseKyc. Rolled back Franchise.');
                return back()->with('error', 'Failed to save franchise KYC data.');
            }

            // Send SMS (email is commented out)
            $uname = $registerData['generated_id'];
            $notification = new SMSNotification($mobile, 'WELCOME', [$uname, $password]);
            $response = $notification->sendMessage();
            \Log::info('SMS sent.', ['response' => $response]);

            return redirect()->route('franchise.login')->with('success', 'Franchise created successfully! ID: ' . $uname . ' Password: ' . $password);
        } catch (\Exception $ex) {
            \Log::error('Exception during OTP verification', [
                'message' => $ex->getMessage(),
                'trace' => $ex->getTraceAsString()
            ]);
            return back()->with('error', 'An unexpected error occurred.')->withInput();
        }
    }

    return view('franchise.combo.index');
}




    public function resendPhoneNumberOtp(Request $request)
    {
        //   return $request->all();
        if ($request->method() == 'POST') {
            //  return  $franchise = Franchise::where('mobile', $request->get('mobile'))->first();
            // $this->validate($request, [
            //     'username' => 'required',
            // ], [
            //     'username.required' => 'The username field is required.',
            // ]);
              
            if ($franchise = Franchise::where('generated_id', $request->get('username'))->first()) {
            //   if ($franchise = Franchise::where('mobile', $request->get('mobile'))->first()) {
                $franchise->verification_otp = rand(10000, 99999);
                $franchise->save();

                // Mail::to($franchise->email)->send(new ForgotPasswordMail([
                //     'otp' =>  $franchise->verification_otp,
                // ]));


                return view('franchise.auth.verifyOtp', ['otp' => $franchise->verification_otp, 'username' => $request->get('username')]);
            }

            return redirect()->back()->with(['error' => 'Invalid Username or Password'])->withInput();
        }
     
        return view('franchise.auth.sendOtp');
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
        return response("<select name='pph_link' class='form-control'><option disabled>No PPH found</option></select>");
    }

    // Start building the select HTML
    $html = "<select name='pph_link' class='form-control'>";
    $html .= "<option selected disabled>Select PPH</option>"; // Optional default

    foreach ($locations as $location) {
        $html .= "<option value='{$location->id}'>" .
                    "{$location->name} - {$location->address}, {$location->city} - {$location->pincode}" .
                 "</option>";
    }

    $html .= "</select>";

    return response($html);
}


 public function paymentStore(Request $request)
    {
        $input = $request->all();
   
        // Initialize Razorpay API
        // $api = new Api(env('RAZORPAY_KEY'), env('RAZORPAY_SECRET'));
           $api = new Api('rzp_live_hZ7MLP0RaGm3Dx', 'XVMFy4TcNEkX9Yf2x2nhjUPn');
        // $api = new Api('rzp_test_hJW9oH6mPsYkla', 'XVMFy4TcNEkX9Yf2x2nhjUPn');
        try {
            // Crecate an initial payment record with status 'pending'
            $franchise = FranchisePayment::where('franchise_id', $request->franchise_id)->first();
            $cms = CMSPayment::where('cms_id', $request->cph_id)->first();
             $amount = $request->final_amount / 100;
            if (empty($franchise)) {
                $paymentRecord = FranchisePayment::create([
                    'franchise_id' => $request->franchise_id,
                    'razorpay_payment_id' => $request->razorpay_payment_id,
                    'amount' => $amount,
                    'status' => 'pending',
                    'method' => 'razorpay',
                ]);

                $paymentRecords = CMSPayment::create([
                    'cms_id' => $request->cph_id,
                    'razorpay_payment_id' => $request->razorpay_payment_id,
                    'amount' => $amount,
                    'status' => 'pending',
                    'method' => 'razorpay',
                ]);

            } else {
                $paymentRecord = FranchisePayment::find($franchise->id);
                $paymentRecords = CMSPayment::find($cms->id);
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
                        $paymentRecords->update(['status' => 'completed']);
                    } else {
                        $paymentRecord->update(['status' => 'failed']);
                        $paymentRecords->update(['status' => 'failed']);
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
                    'message' => 'Franchise and CPH created and payment processed successfully!',
                    'data' => [
                        'id' => $request->generated_id,
                        'password' => $request->password, // Fixed the issue here
                    ]
                ]);
            } else {
                // Update the payment status to 'failed' if payment ID is empty
                try {
                    $paymentRecord->update(['status' => 'failed']);
                    $paymentRecords->update(['status' => 'failed']);
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
