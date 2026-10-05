<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\ECustomer;
use App\Models\ECustomerKyc;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Arr;
use App\Models\Franchise;
use App\Models\GotogoSpeedPostParcel;
use Carbon\Carbon;
use App\Models\GotogoBusinessParcel;
use App\Http\Controllers\franchise\RateCalculator;
use App\Models\FranchisePayment;

class ECustomerController extends Controller
{
    public function index()
    {
        $customers = ECustomer::with('kyc')
            ->orderBy('id', 'desc')
            ->get();

        return view('admin.e-customer.index', compact('customers'));
    }


    public function create()
    {
        return view('admin.e-customer.create');
    }


    /*
    |--------------------------------------------------------------------------
    | Generate Customer Number
    |--------------------------------------------------------------------------
    */
    private function generateRandomNumber()
    {
        do {
            $randomNumber = rand(1, 99999);
            $formattedNumber = str_pad($randomNumber, 5, '0', STR_PAD_LEFT);

            $exists = ECustomer::where(
                'customer_no',
                $formattedNumber
            )->exists();

        } while ($exists);

        return $formattedNumber;
    }


    /*
    |--------------------------------------------------------------------------
    | Upload Multiple Files
    |--------------------------------------------------------------------------
    */
    private function uploadFiles($files, $uploadDir)
    {
        $uploadedFiles = [];

        if (!$files) {
            return '';
        }

        if (!is_array($files)) {
            $files = [$files];
        }

        $files = Arr::flatten($files);

        /*
        | Make sure directory exists
        */
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        foreach ($files as $file) {

            if ($file instanceof \Illuminate\Http\UploadedFile) {

                $extension = $file->getClientOriginalExtension();

                $fileName = time() . '_' . uniqid() . '.' . $extension;

                $file->move($uploadDir, $fileName);

                $uploadedFiles[] = $fileName;
            }
        }

        return implode(',', $uploadedFiles);
    }


    /*
    |--------------------------------------------------------------------------
    | Store Business Bulk Customer
    |--------------------------------------------------------------------------
    */
    public function store(Request $request)
    {
        $request->validate([
            'father_name' => 'required|string|max:255',

            'mobile' => [
                'required',
                'digits:10',
                'unique:e_customers,mobile',
            ],

            'email' => [
                'required',
                'email',
                'unique:e_customers,email',
            ],

            'pincode' => 'required|digits:6',
            'city' => 'required|string|max:255',
            'district' => 'required|string|max:255',
            'state' => 'required|string|max:255',
            'address' => 'required|string',
            'society_name' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'cph_link' => 'required',
        ]);


        try {

            /*
            |--------------------------------------------------------------------------
            | Customer Number
            |--------------------------------------------------------------------------
            */
            $customerNo = $this->generateRandomNumber();


            /*
            |--------------------------------------------------------------------------
            | Directory
            |--------------------------------------------------------------------------
            |
            | Existing system uses:
            | admin/franchise/{generated_id}/
            |
            */
            $generatedId = $request->generated_id ?: $customerNo;

            $uploadDir = public_path(
                'admin/franchise/' . $generatedId . '/'
            );


            /*
            |--------------------------------------------------------------------------
            | Create Customer
            |--------------------------------------------------------------------------
            */
            $customer = new ECustomer();

            $customer->customer_no = $customerNo;

            /*
            | Existing form sends name[]
            */
            if (is_array($request->name)) {
                $customer->name = implode(',', $request->name);
            } else {
                $customer->name = $request->name;
            }

            $customer->register_type = $request->register_type;
            $customer->father_name = $request->father_name;
            $customer->mobile = $request->mobile;
            $customer->email = $request->email;

            $customer->pincode = $request->pincode;
            $customer->city = $request->city;
            $customer->district = $request->district;
            $customer->state = $request->state;

            $customer->address = $request->address;
            $customer->latitude = $request->latitude;
            $customer->longitude = $request->longitude;

            $customer->society = $request->society_name;
            $customer->sector = $request->sector;

            $customer->gst_number = $request->gst_number;
            $customer->cph_link = $request->cph_link;
            $customer->location = $request->location;

            $customer->generated_id = $generatedId;

            /*
            | Admin-created customer is active
            */
            $customer->status = 1;


            /*
            |--------------------------------------------------------------------------
            | Password
            |--------------------------------------------------------------------------
            |
            | Same concept as existing customer registration.
            |
            */
            $password = substr(
                str_shuffle('0123456789'),
                0,
                10
            );

            $customer->password = Hash::make($password);

            $customer->save();


            /*
            |--------------------------------------------------------------------------
            | Create KYC
            |--------------------------------------------------------------------------
            */
            $kyc = new ECustomerKyc();

            $kyc->e_customer_id = $customer->id;

            $kyc->adhar_card = $request->adhar_card;
            $kyc->pan_card = $request->pan_card;
            $kyc->ifsc_code = $request->ifsc_code;
            $kyc->bank_name = $request->bank_name;
            $kyc->branch_name = $request->branch_name;
            $kyc->account_number = $request->account_number;

            /*
            | Existing system uses status = 2
            */
            $kyc->status = 2;


            /*
            |--------------------------------------------------------------------------
            | Documents
            |--------------------------------------------------------------------------
            */

            if ($request->hasFile('adhar_front_img')) {

                $kyc->adhar_front_img = $this->uploadFiles(
                    $request->file('adhar_front_img'),
                    $uploadDir
                );
            }


            if ($request->hasFile('adhar_back_img')) {

                $kyc->adhar_back_img = $this->uploadFiles(
                    $request->file('adhar_back_img'),
                    $uploadDir
                );
            }


            if ($request->hasFile('pan_img')) {

                $kyc->pan_img = $this->uploadFiles(
                    $request->file('pan_img'),
                    $uploadDir
                );
            }


            if ($request->hasFile('cheque_img')) {

                $kyc->cheque_img = $this->uploadFiles(
                    $request->file('cheque_img'),
                    $uploadDir
                );
            }


            if ($request->hasFile('photo')) {

                $kyc->photo = $this->uploadFiles(
                    $request->file('photo'),
                    $uploadDir
                );
            }


            if ($request->hasFile('other_document')) {

                $kyc->other_document = $this->uploadFiles(
                    $request->file('other_document'),
                    $uploadDir
                );
            }


            /*
            |--------------------------------------------------------------------------
            | Video KYC
            |--------------------------------------------------------------------------
            */
            if ($request->hasFile('video_kyc')) {

                $video = $request->file('video_kyc');

                $extension = $video->getClientOriginalExtension();

                $videoName = time() . '_' . uniqid() . '.' . $extension;

                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }

                $video->move($uploadDir, $videoName);

                $kyc->video_kyc = $videoName;
            }


            $kyc->save();


            /*
            |--------------------------------------------------------------------------
            | Success
            |--------------------------------------------------------------------------
            */
            return redirect()
                ->route('admin.e-customer.index')
                ->with(
                    'success',
                    'Business Bulk Customer created successfully. Customer ID: '
                    . $customer->customer_no
                    . ' | Password: '
                    . $password
                );

        } catch (\Exception $e) {

            return redirect()
                ->back()
                ->withInput()
                ->with(
                    'error',
                    $e->getMessage()
                );
        }
    }


    /*
    |--------------------------------------------------------------------------
    | Status
    |--------------------------------------------------------------------------
    */
    public function status(Request $request)
    {
        $customer = ECustomer::findOrFail($request->id);

        $customer->status = $request->status;

        $customer->save();

        return response()->json([
            'success' => true
        ]);
    }

    public function view(Request $request, $id)
{
    $customer = ECustomer::with('kyc')->findOrFail($id);

    $query = GotogoBusinessParcel::where('cod_customer_id', $customer->id)
        ->where('insert_type', 1)
        ->orderBy('created_at', 'desc');

    if ($request->filled('searchKey')) {
        $searchKey = $request->searchKey;

        $query->where(function ($q) use ($searchKey) {
            $q->where('pickup_name', 'like', '%' . $searchKey . '%')
                ->orWhere('pickup_mobile', 'like', '%' . $searchKey . '%')
                ->orWhere('pickup_email', 'like', '%' . $searchKey . '%')
                ->orWhere('pickup_pincode', 'like', '%' . $searchKey . '%')
                ->orWhere('pickup_city', 'like', '%' . $searchKey . '%')
                ->orWhere('pickup_state', 'like', '%' . $searchKey . '%')
                ->orWhere('pickup_address', 'like', '%' . $searchKey . '%')
                ->orWhere('consignee_name', 'like', '%' . $searchKey . '%')
                ->orWhere('consignee_mobile', 'like', '%' . $searchKey . '%')
                ->orWhere('consignee_email', 'like', '%' . $searchKey . '%')
                ->orWhere('consignee_pincode', 'like', '%' . $searchKey . '%')
                ->orWhere('consignee_city', 'like', '%' . $searchKey . '%')
                ->orWhere('consignee_state', 'like', '%' . $searchKey . '%')
                ->orWhere('consignee_address', 'like', '%' . $searchKey . '%')
                ->orWhere('barcode_no', 'like', '%' . $searchKey . '%');
        });
    }

    if ($request->filled('fromdate') && $request->filled('todate')) {
        $startDate = Carbon::parse($request->fromdate)->startOfDay();
        $endDate = Carbon::parse($request->todate)->endOfDay();

        $query->whereBetween('created_at', [$startDate, $endDate]);
    }

    $datas = $query->get();

    $totalParcels = $datas->count();

    $codParcels = $datas->where('payment_method', 'cod')->count();

    $prepaidParcels = $datas->where('payment_method', 'prepaid')->count();

    $totalAmount = $datas->sum('payment_amount');

    return view(
        'admin.e-customer.view',
        compact(
            'customer',
            'datas',
            'totalParcels',
            'codParcels',
            'prepaidParcels',
            'totalAmount'
        )
    );
}


    /*
    |--------------------------------------------------------------------------
    | Edit
    |--------------------------------------------------------------------------
    */
    public function edit($id)
    {
        $customer = ECustomer::with('kyc')
            ->findOrFail($id);

        return view(
            'admin.e-customer.edit',
            compact('customer')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Update
    |--------------------------------------------------------------------------
    */
    public function update(Request $request, $id)
    {
        $customer = ECustomer::findOrFail($id);

        $request->validate([
            'father_name' => 'required|string|max:255',

            'mobile' => [
                'required',
                'digits:10',
                'unique:e_customers,mobile,' . $id,
            ],

            'email' => [
                'required',
                'email',
                'unique:e_customers,email,' . $id,
            ],

            'pincode' => 'required|digits:6',
            'city' => 'required|string|max:255',
            'district' => 'required|string|max:255',
            'state' => 'required|string|max:255',
            'address' => 'required|string',
        ]);


        try {

            /*
            |--------------------------------------------------------------------------
            | Basic Details
            |--------------------------------------------------------------------------
            */
            if ($request->has('name')) {

                if (is_array($request->name)) {
                    $customer->name = implode(',', $request->name);
                } else {
                    $customer->name = $request->name;
                }
            }

            $customer->register_type = $request->register_type;
            $customer->father_name = $request->father_name;
            $customer->mobile = $request->mobile;
            $customer->email = $request->email;

            $customer->pincode = $request->pincode;
            $customer->city = $request->city;
            $customer->district = $request->district;
            $customer->state = $request->state;

            $customer->address = $request->address;
            $customer->latitude = $request->latitude;
            $customer->longitude = $request->longitude;

            $customer->society = $request->society_name;
            $customer->sector = $request->sector;

            $customer->gst_number = $request->gst_number;
            $customer->cph_link = $request->cph_link;
            $customer->location = $request->location;


            /*
            |--------------------------------------------------------------------------
            | Password
            |--------------------------------------------------------------------------
            */
            if ($request->filled('password')) {

                $customer->password = Hash::make(
                    $request->password
                );
            }


            $customer->save();


            /*
            |--------------------------------------------------------------------------
            | KYC
            |--------------------------------------------------------------------------
            */
            $kyc = ECustomerKyc::firstOrNew([
                'e_customer_id' => $customer->id
            ]);

            $kyc->adhar_card = $request->adhar_card;
            $kyc->pan_card = $request->pan_card;
            $kyc->ifsc_code = $request->ifsc_code;
            $kyc->bank_name = $request->bank_name;
            $kyc->branch_name = $request->branch_name;
            $kyc->account_number = $request->account_number;


            /*
            |--------------------------------------------------------------------------
            | Document Directory
            |--------------------------------------------------------------------------
            */
            $uploadDir = public_path(
                'admin/franchise/' . $customer->generated_id . '/'
            );

            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }


            /*
            |--------------------------------------------------------------------------
            | Documents
            |--------------------------------------------------------------------------
            */
            if ($request->hasFile('adhar_front_img')) {

                $kyc->adhar_front_img = $this->uploadFiles(
                    $request->file('adhar_front_img'),
                    $uploadDir
                );
            }

            if ($request->hasFile('adhar_back_img')) {

                $kyc->adhar_back_img = $this->uploadFiles(
                    $request->file('adhar_back_img'),
                    $uploadDir
                );
            }

            if ($request->hasFile('pan_img')) {

                $kyc->pan_img = $this->uploadFiles(
                    $request->file('pan_img'),
                    $uploadDir
                );
            }

            if ($request->hasFile('cheque_img')) {

                $kyc->cheque_img = $this->uploadFiles(
                    $request->file('cheque_img'),
                    $uploadDir
                );
            }

            if ($request->hasFile('photo')) {

                $kyc->photo = $this->uploadFiles(
                    $request->file('photo'),
                    $uploadDir
                );
            }

            if ($request->hasFile('other_document')) {

                $kyc->other_document = $this->uploadFiles(
                    $request->file('other_document'),
                    $uploadDir
                );
            }

            if ($request->hasFile('video_kyc')) {

                $video = $request->file('video_kyc');

                $extension = $video->getClientOriginalExtension();

                $videoName = time() . '_' . uniqid() . '.' . $extension;

                $video->move($uploadDir, $videoName);

                $kyc->video_kyc = $videoName;
            }


            $kyc->save();


            return redirect()
                ->route('admin.e-customer.index')
                ->with(
                    'success',
                    'Business Bulk Customer updated successfully.'
                );

        } catch (\Exception $e) {

            return redirect()
                ->back()
                ->withInput()
                ->with(
                    'error',
                    $e->getMessage()
                );
        }
    }


/*
|--------------------------------------------------------------------------
| Gotogo Post Speed - Business Bulk
|--------------------------------------------------------------------------
*/
public function speed_post_parcel(Request $request, $id)
{
    // Business Bulk customer
    $customer = ECustomer::findOrFail($id);

    // Business Bulk -> Franchise mapping
    // e_customers.cph_link = franchises.id
    $franchise = Franchise::findOrFail($customer->cph_link);

    $query = GotogoSpeedPostParcel::where(
        'franchise_id',
        $franchise->id
    )
    ->where('status', 0)
    ->orderBy('created_at', 'desc');

    /*
    |--------------------------------------------------------------------------
    | Search
    |--------------------------------------------------------------------------
    */
    if ($request->filled('searchKey')) {

        $searchKey = $request->searchKey;

        $query->where(function ($q) use ($searchKey) {

            $q->where('pickup_name', 'like', '%' . $searchKey . '%')
                ->orWhere('pickup_mobile', 'like', '%' . $searchKey . '%')
                ->orWhere('pickup_email', 'like', '%' . $searchKey . '%')
                ->orWhere('consignee_name', 'like', '%' . $searchKey . '%')
                ->orWhere('consignee_mobile', 'like', '%' . $searchKey . '%')
                ->orWhere('consignee_email', 'like', '%' . $searchKey . '%')
                ->orWhere('barcode_no', 'like', '%' . $searchKey . '%');
        });
    }

    /*
    |--------------------------------------------------------------------------
    | Date Filter
    |--------------------------------------------------------------------------
    */
    if ($request->filled('fromdate') && $request->filled('todate')) {

        $startDate = Carbon::parse($request->fromdate)->startOfDay();
        $endDate = Carbon::parse($request->todate)->endOfDay();

        $query->whereBetween('created_at', [
            $startDate,
            $endDate
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Data
    |--------------------------------------------------------------------------
    */
    $datas = $query->get();

    /*
    |--------------------------------------------------------------------------
    | Totals
    |--------------------------------------------------------------------------
    */
    $totalAmount = $datas->sum('payment_amount');
    $totalParcels = $datas->count();

    return view(
        'admin.services.super_speed',
        compact(
            'datas',
            'totalAmount',
            'totalParcels',
            'franchise',
            'customer'
        )
    );
}





public function securityDetails(Request $request, $id, RateCalculator $rateCalculator)
{
    // Business Bulk customer
    $customer = ECustomer::findOrFail($id);

    // Business Bulk -> Franchise
    $franchiseDetails = Franchise::findOrFail($customer->cph_link);

    // Existing franchise payment history
    if ($request->filled(['start_date', 'end_date'])) {

        $startDate = Carbon::parse(
            $request->input('start_date')
        )->startOfDay();

        $endDate = Carbon::parse(
            $request->input('end_date')
        )->endOfDay();

        $data = FranchisePayment::where(
                'franchise_id',
                $franchiseDetails->id
            )
            ->whereBetween('created_at', [$startDate, $endDate])
            ->where('razorpay_payment_id', 'admin')
            ->orderBy('created_at', 'desc')
            ->get();

    } else {

        $data = FranchisePayment::where(
                'franchise_id',
                $franchiseDetails->id
            )
            ->where('razorpay_payment_id', 'admin')
            ->orderBy('created_at', 'desc')
            ->get();
    }

    // POST
    if ($request->isMethod('post')) {

        $validated = $request->validate([
            'amount' => 'required|numeric|min:0',
            'type' => 'required|string|in:gotogo,indiaPost',
        ]);

        try {

            FranchisePayment::create([
                'franchise_id' => $franchiseDetails->id,
                'razorpay_payment_id' => 'admin',
                'amount' => $validated['amount'],
                'status' => 'success',
                'type' => $validated['type'],
            ]);

            // Same balance logic as Franchise
            $field = ($validated['type'] === 'gotogo')
                ? 'gotogo_balance'
                : 'indiapost_balance';

            $franchiseDetails->increment(
                $field,
                $validated['amount']
            );

            return redirect()
                ->back()
                ->with('success', 'Business Bulk updated successfully!');

        } catch (\Exception $e) {

            return back()
                ->with('error', $e->getMessage())
                ->withInput();
        }
    }

    return view(
        'admin.franchise.securityDetails',
        compact(
            'data',
            'franchiseDetails',
            'customer'
        )
    );
}
public function bussiness_parcel(Request $request, $id)
{
    $customer = ECustomer::findOrFail($id);

    $query = GotogoBusinessParcel::where('cod_customer_id', $customer->id)
        ->where('insert_type', 1)
        ->orderBy('created_at', 'desc');

    if ($request->filled('searchKey')) {
        $searchKey = $request->searchKey;

        $query->where(function ($q) use ($searchKey) {
            $q->where('pickup_name', 'like', '%' . $searchKey . '%')
                ->orWhere('pickup_mobile', 'like', '%' . $searchKey . '%')
                ->orWhere('pickup_email', 'like', '%' . $searchKey . '%')
                ->orWhere('consignee_name', 'like', '%' . $searchKey . '%')
                ->orWhere('consignee_mobile', 'like', '%' . $searchKey . '%')
                ->orWhere('consignee_email', 'like', '%' . $searchKey . '%')
                ->orWhere('barcode_no', 'like', '%' . $searchKey . '%');
        });
    }

    if ($request->filled('fromdate') && $request->filled('todate')) {
        $startDate = Carbon::parse($request->fromdate)->startOfDay();
        $endDate = Carbon::parse($request->todate)->endOfDay();

        $query->whereBetween('created_at', [$startDate, $endDate]);
    }

    $datas = $query->get();

    $totalParcels = $datas->count();
    $totalAmount = $datas->sum('payment_amount');

    return view(
        'admin.services.business_bulk',
        compact(
            'datas',
            'totalParcels',
            'totalAmount',
            'customer'
        )
    );
}



    /*
    |--------------------------------------------------------------------------
    | Delete
    |--------------------------------------------------------------------------
    */
    public function delete($id)
    {
        $customer = ECustomer::findOrFail($id);

        /*
        | Delete KYC first
        */
        ECustomerKyc::where(
            'e_customer_id',
            $customer->id
        )->delete();

        $customer->delete();

        return redirect()
            ->route('admin.e-customer.index')
            ->with(
                'success',
                'Business Bulk customer deleted successfully.'
            );
    }
}
