<?php

namespace App\Http\Controllers\market;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Franchise;
use App\Models\MManager;
use App\Models\MManagerKyc;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;
use App\Mail\ForgotPasswordMail;
use Illuminate\Support\Arr;
use Config;
use DB;
use App\Notifications\SMSNotification;

class LoginController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (Auth::guard('market')->check()) {
                return redirect()->route('market.dashboard');
            }
            return $next($request);
        })->only('login');
    }

    public function generateRandomNumber()
    {
        do {
            $randomNumber = rand(1, 99999);
            $formattedNumber = str_pad($randomNumber, 5, '0', STR_PAD_LEFT);
            $exists = MManager::where('customer_no', $formattedNumber)->exists();
        } while ($exists);

        return $formattedNumber;
    }
    
  
public function generateEmployeeId()
{
   
    $lastEmployee = MManager::where('generated_id', 'LIKE', '%@gotogopost.in')
                    ->orderBy('id', 'desc')
                    ->first();
    
    if ($lastEmployee) {

        $lastNumber = explode('@', $lastEmployee->generated_id)[0];
        $nextNumber = intval($lastNumber) + 1;
    } else {

        $nextNumber = 110020001;
    }
    

    return $nextNumber . '@gotogopost.in';
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

            if ($delboy = MManager::where('email', $request->get('username'))->first()) {
                if (Hash::check($request->get('password'), $delboy->password)) {
                    Auth::guard('market')->login($delboy, $request->get('remember'));
                    return redirect()->route('market.dashboard');
                }
            }
            return redirect()->back()->with(['error' => 'Invalid Username or Password'])->withInput();
        }
        return view('market.auth.login');
    }

    public function logout()
    {
        Auth::guard('market')->logout();
        return redirect()->route('market.login');
    }

    public function profile(Request $request)
    {
        $id = Auth::guard('market')->user()->id;
        $franchise = MManager::findOrFail($id);
        return view('market.auth.profile', compact('franchise'));
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

    /**
     * 🚀 डायरेक्ट रजिस्ट्रेशन - बिना OTP वेरिफिकेशन के
     */
    public function register(Request $request)
    {
        $franchise = Franchise::where('status', 1)->get();

        if ($request->method() == 'POST') {
            $this->validate(
                $request,
                [
                    'name' => 'required',
                    'father_name' => 'required',
                    'mobile' => 'required|digits:10|unique:m_managers,mobile',
                    'email' => 'required|email|unique:m_managers,email',
                    'pincode' => 'required|digits:6',
                    'city' => 'required',
                    'district' => 'required',
                    'state' => 'required',
                    'address' => 'required',
                    'cph_link' => 'required',
                    'natality' => 'required',
                    'age' => 'required',
                    'gender' => 'required',
                ]
            );

            try {
       
                $franchiseData = Franchise::find($request->cph_link);
                if (!$franchiseData) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Selected franchise not found.'
                    ], 404);
                }

    $password = substr(str_shuffle('0123456789'), 0, 10);
                
                $generatedId = $this->generateEmployeeId();
            
                $manager = new MManager();
                $manager->customer_no = $this->generateRandomNumber();
                $manager->name = $request->name;
                $manager->father_name = $request->father_name;
                $manager->mobile = $request->mobile;
                $manager->email = $request->email;
                $manager->pincode = $request->pincode;
                $manager->city = $request->city;
                $manager->district = $request->district;
                $manager->state = $request->state;
                $manager->address = $request->address;
                $manager->latitude = $request->latitude ?? null;
                $manager->longitude = $request->longitude ?? null;
                $manager->natality = $request->natality;
                $manager->age = $request->age;
                $manager->gender = $request->gender;
                $manager->gst_number = $request->gst_number ?? null;
                $manager->franchise_id = $request->cph_link;
                $manager->location = $franchiseData->city ?? $request->city;
                $manager->generated_id = $generatedId;
                $manager->status = 1;
                $manager->password = Hash::make($password);
                $manager->save();

                // ✅ KYC डिटेल्स सेव करें
                if ($manager) {
                   $uploadDir = public_path('admin/franchise/' . $generatedId . '/');
                    
                    // डायरेक्टरी बनाएं
                    if (!file_exists($uploadDir)) {
                        mkdir($uploadDir, 0777, true);
                    }

                    $kyc = new MManagerKyc();
                    $kyc->m_manager_id = $manager->id;
                    $kyc->collage = $request->collage;
                    $kyc->marksheetType = $request->marksheetType;
                    $kyc->grade = $request->grade;
                    $kyc->percentage = $request->percentage;
                    $kyc->experience = $request->experience;
                    $kyc->start_date = $request->start_date;
                    $kyc->end_date = $request->end_date;
                    $kyc->adhar_card = $request->adhar_card ?? null;
                    $kyc->pan_card = $request->pan_card ?? null;
                    $kyc->ifsc_code = $request->ifsc_code ?? null;
                    $kyc->bank_name = $request->bank_name ?? null;
                    $kyc->branch_name = $request->branch_name ?? null;
                    $kyc->account_number = $request->account_number ?? null;
                    $kyc->status = 2;

                    // 📁 फाइल अपलोड हैंडलिंग
                    if ($request->hasFile('adhar_front_img')) {
                        $kyc->adhar_front_img = $this->uploadFiles($request->file('adhar_front_img'), $uploadDir);
                    }
                    if ($request->hasFile('adhar_back_img')) {
                        $kyc->adhar_back_img = $this->uploadFiles($request->file('adhar_back_img'), $uploadDir);
                    }
                    if ($request->hasFile('pan_img')) {
                        $kyc->pan_img = $this->uploadFiles($request->file('pan_img'), $uploadDir);
                    }
                    if ($request->hasFile('cheque_img')) {
                        $kyc->cheque_img = $this->uploadFiles($request->file('cheque_img'), $uploadDir);
                    }
                    if ($request->hasFile('photo')) {
                        $kyc->photo = $this->uploadFiles($request->file('photo'), $uploadDir);
                    }
                    if ($request->hasFile('marksheet')) {
                        $kyc->marksheet = $this->uploadFiles($request->file('marksheet'), $uploadDir);
                    }
                    if ($request->hasFile('resume')) {
                        $kyc->resume = $this->uploadFiles($request->file('resume'), $uploadDir);
                    }
                    if ($request->hasFile('other_document')) {
                        $kyc->other_document = $this->uploadFiles($request->file('other_document'), $uploadDir);
                    }
                    if ($request->hasFile('video_kyc')) {
                        $file = $request->file('video_kyc');
                        $fileName = time() . '_' . $file->getClientOriginalName();
                        $file->move($uploadDir, $fileName);
                        $kyc->video_kyc = $fileName;
                    }

                    $kyc->save();
                }

                // ✅ सक्सेस रिस्पॉन्स - बिना OTP के
                return response()->json([
                    'success' => true,
                    'message' => 'Registration completed successfully!',
                    'data' => [
                        'name' => $manager->name,
                        'username' => $manager->email,
                        'password' => $password,
                        'mobile' => $manager->mobile,
                        'generated_id' => $manager->generated_id
                    ]
                ]);

            } catch (\Exception $th) {
                return response()->json([
                    'success' => false,
                    'message' => 'Registration failed: ' . $th->getMessage()
                ], 500);
            }
        }

        return view('market.auth.register', compact('franchise'));
    }

    public function getLocation(Request $request)
    {
        $request->validate([
            'city' => 'required|string|max:255',
        ]);

        $locations = Franchise::where('city', 'LIKE', $request->city . '%')->get();

        if ($locations->isEmpty()) {
            return response("<select name='cph_link' class='form-control'><option disabled>No Franchise found</option></select>");
        }

        $html = "<select name='cph_link' class='form-control'>";
        $html .= "<option selected disabled>Select Franchise</option>";

        foreach ($locations as $location) {
            $html .= "<option value='{$location->id}'>" .
                        "{$location->name} - {$location->address}, {$location->city} - {$location->pincode}" .
                     "</option>";
        }

        $html .= "</select>";
        return response($html);
    }

    
    public function otpViewPage(Request $request)
    {
        return redirect()->route('market.register');
    }

    public function verifyPhoneNumberOtp(Request $request)
    {
        return redirect()->route('market.register');
    }

    public function resendPhoneNumberOtp(Request $request)
    {
        return redirect()->route('market.register');
    }
}