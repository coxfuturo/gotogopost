<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;

use App\Models\CMS;
use App\Models\Franchise;
use App\Models\CMSKyc;
use App\Models\CMSCommissionDetail;
use App\Models\FranchiseBag;
use Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Contracts\Support\Renderable;

use Illuminate\Http\JsonResponse;

use Illuminate\Http\RedirectResponse;

use Illuminate\Support\Facades\Hash;

use App\Models\GotogoSpeedPostParcel;
use App\Models\GotogoSuperFastParcel;
use App\Models\GotogoBusinessParcel;
use App\Models\GotogoRegisteredParcel;
use App\Models\IndiaPostSpeedPostParcel;
use App\Models\IndiaPostBusinessParcel;
use App\Models\IndiaPostRegisteredParcel;
use App\Http\Controllers\admin\CMSDailyBookingReportController;
use Illuminate\Support\Facades\View;
use App\Http\Controllers\franchise\RateCalculator;
use App\Models\FranchiseCommissionDetail;
use App\Models\FranchiseCredit;
use App\Models\CMSPayment;
use App\Mail\RegistrationMail;
use Illuminate\Support\Facades\Mail;

use DB;



class cmsController extends Controller

{

    public function index(Request $request)

    {

        $datas = CMS::get();

        return view('admin.cms.index', compact('datas'));
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



    public function create(Request $request)

    {

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

                ],
            );



            try {



                $date = Carbon::now();

                $post = new CMS();

                $post->cms_no = $this->generateRandomNumber();

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

                $post->generated_id = $request->generated_id;

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

                        $kyc->cms_img = $img6;
                    }

                    $kyc->save();

                    if (!$kyc) {

                        $post->delete();
                    }
                }


                try {
                    Mail::to($request->email)->send(new RegistrationMail([
                        'username' => $post->generated_id,
                        'password' => $password,
                        'route' => route('franchise.login')
                    ]));
                } catch (\Exception $e) {
                    \Log::error('Mail sending failed: ' . $e->getMessage());
                }

                return redirect()->route('admin.cms.index')->with('success', 'CMS created successfully! ID:' . $post->generated_id . ' Password:' . $password);
            } catch (\Exception $th) {

                return back()->with('error', $th->getMessage())->withInput();
            }
        }

        return view('admin.cms.create');
    }

    public function edit(Request $request, $id)

    {

        // return $request;

        $post = CMS::findorfail($id);

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

                ]

            );



            try {



                $date = Carbon::now();



                $post->name = $request->name;

                $post->father_name = $request->father_name;

                $post->mobile = $request->mobile;

                $post->email = $request->email;

                $post->pincode = $request->pincode;

                $post->city = $request->city;

                $post->district = $request->district;

                $post->state = $request->state;

                $post->address = $request->address;

                $post->status = $request->status;
                $post->payment_status = $request->payment_status;

                $post->latitude = $request->latitude;

                $post->longitude = $request->longitude;

                $post->commission = $request->commission;

                $post->save();



                return redirect()->route('admin.cms.index')->with('success', 'CMS updated successfully!');
            } catch (\Exception $th) {

                return back()->with('error', $th->getMessage())->withInput();
            }
        }

        $data = $post;

        return view('admin.cms.edit', compact('data'));
    }









    public function status(Request $request)

    {

        try {

            $cms = CMS::findorfail($request->id);

            $cms->status = $request->status;

            $cms->save();

            return response()->json(['success' => true, 'status' => $cms->status]);
        } catch (\Throwable $th) {

            return response()->json(['success' => false]);
        }
    }



    public function serviceStatus(Request $request)
    {
        try {

            $serviceType = $request->service_type;
            $status = $request->status;

            $franchise = CMS::findorfail($request->cms_id);

            if ($serviceType == GotogoSpeedPostParcel::SERVICE_TYPE_GOTO_POST_SPEED) {
                $franchise->gotogo_speed_post = $status;
            }
            if ($serviceType == GotogoSpeedPostParcel::SERVICE_TYPE_GOTO_POST_BUSINESS_PARCEL) {
                $franchise->gotogo_business_parcel = $status;
            }
            if ($serviceType == GotogoSpeedPostParcel::SERVICE_TYPE_GOTO_POST_REGISTERED) {
                $franchise->gotogo_post_registered = $status;
            }
            if ($serviceType == GotogoSpeedPostParcel::SERVICE_TYPE_INDIA_POST_SPEED) {
                $franchise->india_post_speed = $status;
            }
            if ($serviceType == GotogoSpeedPostParcel::SERVICE_TYPE_INDIA_POST_BUSINESS) {
                $franchise->india_post_business = $status;
            }

            if ($serviceType == GotogoSpeedPostParcel::SERVICE_TYPE_INDIA_POST_REGISTERED) {
                $franchise->india_post_registered = $status;
            }
            if ($serviceType == GotogoSpeedPostParcel::E2E) {
                $franchise->e2e = $status;
            }
            if ($serviceType == GotogoSpeedPostParcel::E2H) {
                $franchise->e2h = $status;
            }

            $franchise->save();

            return response()->json([
                'success' => true,
                'status' => $status,
                'serviceType' => $serviceType
            ]);
        } catch (\Throwable $th) {
            return response()->json(['success' => false]);
        }
    }


    public function view(Request $request, $id)
    {


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
                    ->get(['barcode_no', 'pickup_name', 'pickup_pincode', 'consignee_name', 'consignee_pincode']);


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

            $dailybookingdata = CMSDailyBookingReportController::eachCMSfilterbyDate($id, $request->date);
            return response()->json(['status' => 200, 'message' => 'filter data succesfull', 'data' => $dailybookingdata]);
        }

        return view(
            'admin.cms.view',
            [
                'data' => $frachinse,
                'parcel' => $allParcels,
                'bookingData' => $dailybookingdata
            ]
        );
    }



    public function delete($id)

    {

        try {
            $cms = CMS::findorfail($id)->delete();
            
            return redirect()->route('admin.cms.index')->with('success', 'CMS Deleted Successfully');
        } catch (\Exception $th) {

            return back()->with('error', $th->getMessage())->withInput();
        }
    }




    public function commissions(Request $request)
    {

        $data = CMS::all();
        if ($request->method() == 'POST') {

            $post = CMS::findorfail($request->id);

            try {

                $walletBalanceIncrement = $request->input('wallet_balance', 0);
                $commissionIncrement = $request->input('commission', 0);
                $post->increment('wallet_balance', $walletBalanceIncrement);
                $post->increment('commission', $commissionIncrement);
                $post->remaining_balance = $post->wallet_balance + $post->commission;

                $post->save();


                return redirect()->back()->with('success', 'Franchise updated successfully!');
            } catch (\Exception $th) {
                return back()->with('error', $th->getMessage())->withInput();
            }
        }


        return view('admin.cms.commissions', compact('data'));
    }


    public function commissionDetail(Request $request, $id)
    {

        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $serviceTypes = [1, 3, 4]; // Allowed service types

        if ($startDate && $endDate) {
            $startDate = Carbon::parse($startDate)->startOfDay();
            $endDate = Carbon::parse($endDate)->endOfDay();
        } else {
            $startDate = Carbon::today()->startOfDay();
            $endDate = Carbon::today()->endOfDay();
        }

        // Fetch commission details for existing service types
        $commissionData = CMSCommissionDetail::where('cms_id', $id)
            ->whereIn('service_type', $serviceTypes)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->selectRaw('service_type, COALESCE(SUM(amount), 0) as total_amount, COALESCE(SUM(commission), 0) as total_commission, MAX(created_at) as created_at')
            ->groupBy('service_type')
            ->get()
            ->keyBy('service_type'); // Index by service_type for easy lookup

        // Ensure all service types are present with 0 values if missing
        $data = collect($serviceTypes)->map(function ($serviceType) use ($commissionData) {
            return (object) [
                'service_type' => GotogoSpeedPostParcel::getServiceType($serviceType),
                'total_amount' => $commissionData[$serviceType]->total_amount ?? 0,
                'total_commission' => $commissionData[$serviceType]->total_commission ?? 0,
                'created_at' => $commissionData[$serviceType]->created_at ?? null, // Avoid error on created_at
            ];
        });

        $franchiseDetails = CMS::findOrFail($id);

        return view('admin.cms.commissionDetail', compact('data', 'franchiseDetails'));
    }



    public function printCommissionDetail(Request $request, $id)
    {

        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $serviceTypes = [1, 3, 4]; // Allowed service types
        $gstRate = 18; // GST percentage
        $tdsRate = 5; // TDS percentage

        if ($startDate && $endDate) {
            $startDate = \Carbon\Carbon::parse($startDate)->startOfDay();
            $endDate = \Carbon\Carbon::parse($endDate)->endOfDay();
        } else {
            $startDate = Carbon::today()->startOfDay();
            $endDate = Carbon::today()->endOfDay();
        }

        $data = CMSCommissionDetail::where('cms_id', $id)
            ->whereIn('service_type', $serviceTypes)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->selectRaw('SUM(commission) as total_commission')
            ->first();



        $totalCommission = $data->total_commission ?? 0;


        // GST and TDS Calculation
        $gstAmount = ($totalCommission * $gstRate) / 100;
        $tdsAmount = ($totalCommission * $tdsRate) / 100;
        $totalAmount = $totalCommission + $gstAmount - $tdsAmount;

        // Fetch PPH Details
        $pphDetails = CMS::findOrFail($id);

        // Render View
        $otherPageContent = View('admin.cms.printCommissionDetail', [
            'pphDetails' => $pphDetails,
            'data' => [
                'commission' => number_format($totalCommission, 2),
                'gst' => number_format($gstAmount, 2),
                'tds' => number_format($tdsAmount, 2),
                'total' => number_format($totalAmount, 2),
            ]
        ])->render();

        return response()->json([
            'otherPageContent' => $otherPageContent
        ]);
    }



    public function paymentHistory(Request $request)
    {

        try {

            $paymentHistory = CMSPayment::with('cms')->orderBy('id', 'DESC');

            // Filter by start date if provided in the request
            if ($request->has('start_date') && !empty($request->start_date)) {
                $startDate = Carbon::parse($request->start_date)->startOfDay(); // Convert to start of the day
                $paymentHistory = $paymentHistory->where('created_at', '>=', $startDate);
            }

            // Filter by end date if provided in the request
            if ($request->has('end_date') && !empty($request->end_date)) {
                $endDate = Carbon::parse($request->end_date)->endOfDay(); // Convert to end of the day
                $paymentHistory = $paymentHistory->where('created_at', '<=', $endDate);
            }

            // Execute the query and get the results
            $paymentHistory = $paymentHistory->get();

            // Return the view with the payment history
            return view('admin.payment.cmstHistory', compact('paymentHistory'));
        } catch (Exception $e) {
            // Handle any general exceptions
            return response()->json([
                'success' => false,
                'message' => 'Error fetching payment: ' . $e->getMessage(),
            ]);
        }
    }

    public function printPaymentHistory(Request $request)

    {

        $data = CMSPayment::where('id', $request->id)->first();

        $franchiseDetails = CMS::findOrFail($data->cms_id);


        $otherPageContent = View('admin.payment.printCommissionDetail', ['data' => $data, 'franchiseDetails' => $franchiseDetails])->render();

        return response()->json([

            'otherPageContent' => $otherPageContent

        ]);
    }
}
