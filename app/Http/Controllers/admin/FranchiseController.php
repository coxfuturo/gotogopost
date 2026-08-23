<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Franchise;
use App\Models\FranchisePayment;
use App\Models\FranchiseKyc;
use App\Models\GotogoSpeedPostParcel;
use App\Models\GotogoSuperFastParcel;
use App\Models\GotogoBusinessParcel;
use App\Models\GotogoRegisteredParcel;
use App\Models\IndiaPostSpeedPostParcel;
use App\Models\IndiaPostBusinessParcel;
use App\Models\IndiaPostRegisteredParcel;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\admin\FranchiseDailyBookingReportController;
use App\Http\Controllers\franchise\RateCalculator;
use App\Models\FranchiseCommissionDetail;
use App\Models\FranchiseCredit;
use Illuminate\Support\Facades\View;
use App\Mail\RegistrationMail;
use Illuminate\Support\Facades\Mail;

class FranchiseController extends Controller
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


    public function index(Request $request)
    {
        $datas = Franchise::get();
        return view('admin.franchise.index', compact('datas'));
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
                    'email' => 'required|email|unique:franchises,email',
                    'pincode' => 'required|digits:6',
                    'city' => 'required',
                    'district' => 'required',
                    'state' => 'required',
                    'address' => 'required',
                    'society_name' => 'required',

                ],
            );

            try {

                $date = Carbon::now();
                $post = new Franchise();
                $post->franchise_no = $this->generateRandomNumber();
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
                $post->society = $request->society_name;
                $post->sector = $request->sector;
                $post->generated_id = $request->generated_id;
                $numbers = str_shuffle('0123456789');
                $password = substr($numbers, 0, 10);
                $post->password = Hash::make($password);
                $post->save();

                if ($post) {
                    $kyc = new FranchiseKyc();
                    $kyc->franchise_id = $post->id;
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

                try {
                    Mail::to($request->email)->send(new RegistrationMail([
                        'username' => $post->generated_id,
                        'password' => $password,
                        'route' => route('franchise.login')
                    ]));
                } catch (\Exception $e) {
                    \Log::error('Mail sending failed: ' . $e->getMessage());
                }


                return redirect()->route('admin.franchise.index')->with('success', 'Franchise created successfully! ID:' . $post->generated_id . ' Password:' . $password);
            } catch (\Exception $th) {
                return back()->with('error', $th->getMessage())->withInput();
            }
        }
        return view('admin.franchise.create');
    }
    public function edit(Request $request, $id)
    {
        $post = Franchise::findorfail($id);
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
                $post->latitude = $request->latitude;
                $post->longitude = $request->longitude;
                $post->society = $request->society_name;
                $post->sector = $request->sector;
                $post->status = $request->status;
                $post->payment_status = $request->payment_status;
                $post->save();
                return redirect()->route('admin.franchise.index')->with('success', 'Franchise updated successfully!');
            } catch (\Exception $th) {
                return back()->with('error', $th->getMessage())->withInput();
            }
        }
        $data = $post;
        return view('admin.franchise.edit', compact('data'));
    }


    public function delete(Request $request)
    {
        try {
            $franchise = Franchise::findorfail($request->id);
            $franchise->delete();
            return redirect()->route('admin.franchise.index')->with('success', 'Franchise deleted successfully!');
        } catch (\Throwable $th) {
            return back()->with('error', $th->getMessage())->withInput();
        }
    }


    public function status(Request $request)
    {
        try {
            $franchise = Franchise::findorfail($request->id);
            $franchise->status = $request->status;
            $franchise->save();
            return response()->json(['success' => true, 'status' => $franchise->status]);
        } catch (\Throwable $th) {
            return response()->json(['success' => false]);
        }
    }


    public function serviceStatus(Request $request)
    {
        try {

            $serviceType = $request->service_type;
            $status = $request->status;

            $franchise = Franchise::findorfail($request->franchise_id);

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

        $frachinse =  Franchise::findorfail($id);

        $franchise_id = $id; // Replace $id with the actual franchise ID

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
            $parcels = $model::where('franchise_id', $franchise_id)
                ->whereDate('created_at', Carbon::today()->toDateString())
                ->get(['barcode_no', 'pickup_name', 'pickup_pincode', 'consignee_name', 'consignee_pincode']);

            $serviceNumber = $serviceNumbers[$serviceType] ?? null; // Get the service number from the mapping

            $parcels->each(function ($parcel) use ($serviceType, $serviceNumber) {
                $parcel->service_type = $serviceType;
                $parcel->service_number = $serviceNumber;
            });

            $allParcels = $allParcels->merge($parcels);
        }


        // return $parcel;
        $dailybookingdata = FranchiseDailyBookingReportController::eachFranchise($id);


        if ($request->date && $request->type === "parcel") {

            $date = Carbon::createFromFormat('d-m-Y', $request->date)->startOfDay()->toDateString();

            $allParcels = collect();

            foreach ($models as $serviceType => $model) {
                $parcels = $model::where('franchise_id', $franchise_id)
                    ->whereDate('created_at', $date)
                    ->get(['barcode_no', 'pickup_name', 'pickup_pincode', 'consignee_name', 'consignee_pincode']);

                $serviceNumber = $serviceNumbers[$serviceType] ?? null; // Get the service number from the mapping

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

            $dailybookingdata = FranchiseDailyBookingReportController::eachFranchisefilterbyDate($id, $request->date);
            return response()->json(['status' => 200, 'message' => 'filter data succesfull', 'data' => $dailybookingdata]);
        }

        return view(
            'admin.franchise.view',
            [
                'franchise' => $frachinse,
                'parcel' => $allParcels,
                'bookingData' => $dailybookingdata
            ]
        );
    }


    public function chat($id)
    {
        return view('admin.franchise.chat');
    }


    public function commissions(Request $request)
    {

        $data = Franchise::all();

        return view('admin.franchise.commissions', compact('data'));
    }



    public function commissionDetail(Request $request, $id, RateCalculator $rateCalculator)
    {


        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        if ($startDate && $endDate) {
            $startDate = \Carbon\Carbon::parse($startDate)->startOfDay();
            $endDate = \Carbon\Carbon::parse($endDate)->endOfDay();
            $data = FranchiseCommissionDetail::where('franchise_id', $id)
                ->selectRaw('
                id, 
                franchise_id, 
                service_type, 
                created_at, 
                SUM(amount) as total_amount, 
                SUM(commission) as total_commission,
                SUM(CASE WHEN LOWER(payment_method) = "prepaid" THEN amount ELSE 0 END) as prepaid_amount,
                SUM(CASE WHEN LOWER(payment_method) = "cod" THEN amount ELSE 0 END) as cod_amount,
                SUM(CASE WHEN LOWER(payment_method) = "prepaid" THEN commission ELSE 0 END) as prepaid_commission,
                SUM(CASE WHEN LOWER(payment_method) = "cod" THEN commission ELSE 0 END) as cod_commission
            ')
                ->whereBetween('created_at', [$startDate, $endDate])
                ->groupBy('service_type')
                ->get();
        } else {
            $today = Carbon::today();
            $data = FranchiseCommissionDetail::where('franchise_id', $id)
                ->selectRaw('
                id, 
                franchise_id, 
                service_type, 
                created_at, 
                SUM(amount) as total_amount, 
                SUM(commission) as total_commission,
                SUM(CASE WHEN LOWER(payment_method) = "prepaid" THEN amount ELSE 0 END) as prepaid_amount,
                SUM(CASE WHEN LOWER(payment_method) = "cod" THEN amount ELSE 0 END) as cod_amount,
                SUM(CASE WHEN LOWER(payment_method) = "prepaid" THEN commission ELSE 0 END) as prepaid_commission,
                SUM(CASE WHEN LOWER(payment_method) = "cod" THEN commission ELSE 0 END) as cod_commission
            ')
                ->whereDate('created_at', $today)
                ->groupBy('service_type')
                ->get();
        }

        // Calculate India Post commissions
        $indiaPostSpeedPostCommission = $rateCalculator->calculateCommissionForIndiaPostByRevenue(5, $id, $startDate, $endDate);
        $indiaPostBusinessCommission = $rateCalculator->calculateCommissionForIndiaPostByRevenue(6, $id, $startDate, $endDate);

        foreach ($data as &$entry) {
            $entry->service_type = intval($entry->service_type);
            if ($entry->service_type == 5) {
                $entry->total_commission = $indiaPostSpeedPostCommission['commission'];
                $entry->total_amount = $indiaPostSpeedPostCommission['amount'];
            } elseif ($entry->service_type == 6) {
                $entry->total_commission = $indiaPostBusinessCommission['commission'];
                $entry->total_amount = $indiaPostBusinessCommission['amount'];
            }
        }

        // Ensure all service types are present
        $allServiceTypes = [1, 3, 4, 5, 6, 9];
        $dataMap = $data->keyBy('service_type');

        foreach ($allServiceTypes as $serviceType) {
            if (!isset($dataMap[$serviceType])) {
                $data->push((object)[
                    'id' => null,
                    'franchise_id' => $id,
                    'service_type' => $serviceType,
                    'created_at' => null,
                    'total_amount' => "0",
                    'total_commission' => "0",
                    'prepaid_amount' => "0",
                    'cod_amount' => "0",
                    'prepaid_commission' => "0",
                    'cod_commission' => "0",
                    'service_name' => GotogoSpeedPostParcel::getServiceType($serviceType),
                ]);
            }
        }

        foreach ($data as &$entry) {
            $entry->service_name = GotogoSpeedPostParcel::getServiceType($entry->service_type);
        }

        // Split data into groups
        $gotogoCommission = [];
        $indiaPostCommission = [];
        $totalGotogoCommission = 0;
        $totalIndiaPostCommission = 0;

        foreach ($data as &$entry) {
            if (in_array($entry->service_type, [5, 6])) {
                $indiaPostCommission[] = $entry;
                $totalIndiaPostCommission += (float) $entry->total_commission;
            } else {
                $gotogoCommission[] = $entry;
                $totalGotogoCommission += (float) $entry->total_commission;
            }
        }

        $franchiseDetails = Franchise::findOrFail($id);

        return view('admin.franchise.commissionDetail', compact(
            'gotogoCommission',
            'indiaPostCommission',
            'totalGotogoCommission',
            'totalIndiaPostCommission',
            'franchiseDetails'
        ));
    }


   public function creditDetails(Request $request, $id, RateCalculator $rateCalculator)
{
   
    // Get the franchise or fail
    $franchiseDetails = Franchise::findOrFail($id);

    // Handle date filtering
    if ($request->filled(['start_date', 'end_date'])) {
        $startDate = \Carbon\Carbon::parse($request->input('start_date'))->startOfDay();
        $endDate = \Carbon\Carbon::parse($request->input('end_date'))->endOfDay();

        $data = FranchiseCredit::where('franchise_id', $id)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->orderBy('created_at', 'desc')
            ->get();
    } else {
        // If no dates provided, get all records
        $data = FranchiseCredit::where('franchise_id', $id)
            ->orderBy('created_at', 'desc')
            ->get();
    }

    // Handle POST submission
    if ($request->isMethod('post')) {
        //  return $request->all();
        $validated = $request->validate([
            'amount' => 'required|numeric|min:0',
            'type' => 'required|string|in:gotogo,indiaPost', // adjust 'india' to match your actual types
        ]);

        try {
            // Create the credit entry
            FranchiseCredit::create([
                'credit_amount' => $validated['amount'],
                'type' => $validated['type'],
                'franchise_id' => $id,
            ]);

            // Increment the appropriate credit field
            $field = ($validated['type'] === 'gotogo') ? 'credit_balance' : 'india_credit_amount';
            // return $field;
            $franchiseDetails->increment($field, $validated['amount']);

            return redirect()->back()->with('success', 'Franchise updated successfully!');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage())->withInput();
        }
    }

    return view('admin.franchise.creditDetail', compact('data', 'franchiseDetails'));
}

 public function securityDetails(Request $request, $id, RateCalculator $rateCalculator)
{
   
    // Get the franchise or fail
    $franchiseDetails = Franchise::findOrFail($id);

    // Handle date filtering
    if ($request->filled(['start_date', 'end_date'])) {
        $startDate = \Carbon\Carbon::parse($request->input('start_date'))->startOfDay();
        $endDate = \Carbon\Carbon::parse($request->input('end_date'))->endOfDay();

        $data = FranchisePayment::where('franchise_id', $id)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->where('razorpay_payment_id', 'admin')
            ->orderBy('created_at', 'desc')
            ->get();
    } else {
        // If no dates provided, get all records
        $data = FranchisePayment::where('franchise_id', $id)
            ->where('razorpay_payment_id', 'admin')
            ->orderBy('created_at', 'desc')
            ->get();
    }

    // Handle POST submission
    if ($request->isMethod('post')) {
        //  return $request->all();
        $validated = $request->validate([
            'amount' => 'required|numeric|min:0',
            'type' => 'required|string|in:gotogo,indiaPost', // adjust 'india' to match your actual types
        ]);

        try {
            // Create the credit entry
            FranchisePayment::create([
                    'franchise_id' => $id,
                    'razorpay_payment_id' => 'admin',
                    'amount' => $validated['amount'],
                    'status' => 'success',
                    'type' => $validated['type'],
                ]);

            // Increment the appropriate credit field
            $field = ($validated['type'] === 'gotogo') ? 'gotogo_balance' : 'indiapost_balance';
            // return $field;
            $franchiseDetails->increment($field, $validated['amount']);

            return redirect()->back()->with('success', 'Franchise updated successfully!');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage())->withInput();
        }
    }

    return view('admin.franchise.securityDetails', compact('data', 'franchiseDetails'));
}


    public function printCommissionDetail(Request $request, $id, RateCalculator $rateCalculator)
    {


        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        if ($startDate && $endDate) {
            $startDate = \Carbon\Carbon::parse($startDate)->startOfDay();
            $endDate = \Carbon\Carbon::parse($endDate)->endOfDay();
            $data = FranchiseCommissionDetail::where('franchise_id', $id)
                ->selectRaw('id, franchise_id, service_type, created_at, SUM(amount) as total_amount, SUM(commission) as total_commission')
                ->whereBetween('created_at', [$startDate, $endDate])
                ->groupBy('service_type')
                ->get();
        } else {
            $today = Carbon::today();
            $data = FranchiseCommissionDetail::where('franchise_id', $id)
                ->selectRaw('id, franchise_id, service_type, created_at, SUM(amount) as total_amount, SUM(commission) as total_commission')
                ->whereDate('created_at', $today)
                ->groupBy('service_type')
                ->get();
        }

        // India Post Commission Calculation
        $indiaPostSpeedPostCommission = $rateCalculator->calculateCommissionForIndiaPostByRevenue(5, $id, $startDate ?? null, $endDate ?? null);
        $indiaPostBusinessCommission = $rateCalculator->calculateCommissionForIndiaPostByRevenue(6, $id, $startDate ?? null, $endDate ?? null);

        foreach ($data as &$entry) {
            $entry->service_type = intval($entry->service_type);
            if ($entry->service_type == 5) {
                $entry->total_commission = $indiaPostSpeedPostCommission['commission'];
                $entry->total_amount = $indiaPostSpeedPostCommission['amount'];
            } elseif ($entry->service_type == 6) {
                $entry->total_commission = $indiaPostBusinessCommission['commission'];
                $entry->total_amount = $indiaPostBusinessCommission['amount'];
            }
        }

        // Missing service types ko zero data dena
        $allServiceTypes = [1, 3, 4, 5, 6, 9];
        $dataMap = $data->keyBy('service_type');

        foreach ($allServiceTypes as $serviceType) {
            if (!isset($dataMap[$serviceType])) {
                $data->push((object)[
                    'id' => null,
                    'franchise_id' => $id,
                    'service_type' => $serviceType,
                    'created_at' => null,
                    'total_amount' => "0",
                    'total_commission' => "0",
                    'service_name' => GotogoSpeedPostParcel::getServiceType($serviceType),
                ]);
            }
        }

        foreach ($data as &$entry) {
            $entry->service_name = GotogoSpeedPostParcel::getServiceType($entry->service_type);
        }

        // Data ko do groups me split karna
        $gotogoCommission = [];
        $indiaPostCommission = [];
        $totalGotogoCommission = 0;
        $totalIndiaPostCommission = 0;

        foreach ($data as &$entry) {
            if (in_array($entry->service_type, [5, 6])) {
                $indiaPostCommission[] = $entry;
                $totalIndiaPostCommission += (float) $entry->total_commission;
            } else {
                $gotogoCommission[] = $entry;
                $totalGotogoCommission += (float) $entry->total_commission;
            }
        }

        // GST and TDS Calculation
        $gstRate = 18;
        $tdsRate = 5;



        $gstGotogo = ($totalGotogoCommission * $gstRate) / 100;
        $tdsGotogo = ($totalGotogoCommission * $tdsRate) / 100;
        $totalGotogo = $totalGotogoCommission + $gstGotogo - $tdsGotogo;


        $gstIndiaPost = ($totalIndiaPostCommission * $gstRate) / 100;
        $tdsIndiaPost = ($totalIndiaPostCommission * $tdsRate) / 100;
        $totalIndiaPost = $totalIndiaPostCommission + $gstIndiaPost - $tdsIndiaPost;

        $franchiseDetails = Franchise::findOrFail($id);

        // Same data pass for printing
        $otherPageContent = View('admin.franchise.printCommissionDetail', compact(
            'gotogoCommission',
            'indiaPostCommission',
            'totalGotogoCommission',
            'totalIndiaPostCommission',
            'totalGotogo',
            'totalIndiaPost',
            'gstGotogo',
            'gstIndiaPost',
            'tdsGotogo',
            'tdsIndiaPost',
            'franchiseDetails'
        ))->render();

        return response()->json([
            'otherPageContent' => $otherPageContent
        ]);
    }

   public function paymentHistory(Request $request)
{
    try {
        $paymentHistory = FranchisePayment::with('franchise')
            ->where('type', 'register')
            ->orderBy('id', 'DESC');

        // Initialize date filters
        $startDate = null;
        $endDate = null;

        // If user provides start_date
        if ($request->has('start_date') && !empty($request->start_date)) {
            $startDate = Carbon::parse($request->start_date)->startOfDay();
            $paymentHistory = $paymentHistory->where('created_at', '>=', $startDate);
        }

        // If user provides end_date
        if ($request->has('end_date') && !empty($request->end_date)) {
            $endDate = Carbon::parse($request->end_date)->endOfDay();
            $paymentHistory = $paymentHistory->where('created_at', '<=', $endDate);
        }

        // If no date provided, default to today
        if (!$request->has('start_date') && !$request->has('end_date')) {
            $todayStart = Carbon::today()->startOfDay();
            $todayEnd = Carbon::today()->endOfDay();
            $paymentHistory = $paymentHistory->whereBetween('created_at', [$todayStart, $todayEnd]);
        }

        // Clone for total calculation
        $totalQuery = clone $paymentHistory;
        $total = $totalQuery->sum('amount');

        // Fetch records
        $paymentHistory = $paymentHistory->get();

        // Return view with total
        return view('admin.payment.franchiseHistory', compact('paymentHistory', 'total'));

    } catch (Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Error fetching payment: ' . $e->getMessage(),
        ]);
    }
}


    //   public function gotogopaymentHistory(Request $request)
    // {
    //     try {
    //                $todayDate = Carbon::today()->toDateString();
    //                $total = FranchisePayment::with('franchise')->where('created_at',$todayDate)->where('type','gotogo')->sum('amount');

    //         $paymentHistory = FranchisePayment::with('franchise')->where('type','gotogo')->orderBy('id', 'DESC');

    //         // Filter by start date if provided in the request
    //         if ($request->has('start_date') && !empty($request->start_date)) {
    //             $startDate = Carbon::parse($request->start_date)->startOfDay(); // Convert to start of the day
    //             $paymentHistory = $paymentHistory->where('created_at', '>=', $startDate);
    //         }

    //         // Filter by end date if provided in the request
    //         if ($request->has('end_date') && !empty($request->end_date)) {
    //             $endDate = Carbon::parse($request->end_date)->endOfDay(); // Convert to end of the day
    //             $paymentHistory = $paymentHistory->where('created_at', '<=', $endDate);
    //         }

    //         // Execute the query and get the results
    //         $paymentHistory = $paymentHistory->get();

          

    //         // Return the view with the payment history
    //         return view('admin.payment.gotogofranchiseHistory', compact('paymentHistory'));
    //     } catch (Exception $e) {
    //         // Handle any general exceptions
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Error fetching payment: ' . $e->getMessage(),
    //         ]);
    //     }
    // }

    public function gotogopaymentHistory(Request $request)
{
    try {
        $paymentHistory = FranchisePayment::with('franchise')
            ->where('type', 'gotogo')
            ->orderBy('id', 'DESC');

        // Initialize date filters
        $startDate = null;
        $endDate = null;

        // Filter by start date if provided
        if ($request->has('start_date') && !empty($request->start_date)) {
            $startDate = Carbon::parse($request->start_date)->startOfDay();
            $paymentHistory = $paymentHistory->where('created_at', '>=', $startDate);
        }

        // Filter by end date if provided
        if ($request->has('end_date') && !empty($request->end_date)) {
            $endDate = Carbon::parse($request->end_date)->endOfDay();
            $paymentHistory = $paymentHistory->where('created_at', '<=', $endDate);
        }

        // If no date filters provided, default to today
        if (!$request->has('start_date') && !$request->has('end_date')) {
            $todayStart = Carbon::today()->startOfDay();
            $todayEnd = Carbon::today()->endOfDay();
            $paymentHistory = $paymentHistory->whereBetween('created_at', [$todayStart, $todayEnd]);
        }

        // Clone for total
        $totalQuery = clone $paymentHistory;
        $total = $totalQuery->sum('amount');

        // Get the filtered data
        $paymentHistory = $paymentHistory->get();

        // Return view
        return view('admin.payment.gotogofranchiseHistory', compact('paymentHistory', 'total'));

    } catch (Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Error fetching payment: ' . $e->getMessage(),
        ]);
    }
}


     public function indiapaymentHistory(Request $request)
{
    try {
        $paymentHistory = FranchisePayment::with('franchise')
            ->where('type', 'indiapost')
            ->orderBy('id', 'DESC');

        // Initialize date filters
        $startDate = null;
        $endDate = null;

        // If user selects start_date
        if ($request->has('start_date') && !empty($request->start_date)) {
            $startDate = Carbon::parse($request->start_date)->startOfDay();
            $paymentHistory = $paymentHistory->where('created_at', '>=', $startDate);
        }

        // If user selects end_date
        if ($request->has('end_date') && !empty($request->end_date)) {
            $endDate = Carbon::parse($request->end_date)->endOfDay();
            $paymentHistory = $paymentHistory->where('created_at', '<=', $endDate);
        }

        // If no date filter provided, show only today's records
        if (!$request->has('start_date') && !$request->has('end_date')) {
            $todayStart = Carbon::today()->startOfDay();
            $todayEnd = Carbon::today()->endOfDay();
            $paymentHistory = $paymentHistory->whereBetween('created_at', [$todayStart, $todayEnd]);
        }

        // Clone for total calculation
        $totalQuery = clone $paymentHistory;
        $total = $totalQuery->sum('amount');

        // Get the filtered data
        $paymentHistory = $paymentHistory->get();

        // Return view with total
        return view('admin.payment.indiapostfranchiseHistory', compact('paymentHistory', 'total'));

    } catch (Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Error fetching payment: ' . $e->getMessage(),
        ]);
    }
}


    public function printPaymentHistory(Request $request)

    {

        $data = FranchisePayment::where('id', $request->id)->first();
        $franchiseDetails = Franchise::findOrFail($data->franchise_id);
        $otherPageContent = View('admin.payment.printCommissionDetail', ['data' => $data, 'franchiseDetails' => $franchiseDetails])->render();

        return response()->json([

            'otherPageContent' => $otherPageContent

        ]);
    }

    public function paymentCreate($membertype){
       $data = Franchise::orderBy('name','asc')->get();
       
        return view('admin.payment.paymentCreated',compact('data'));
    }

    public function createdpaymentHistory(Request $request){

        try {
        $paymentHistory = FranchisePayment::with('franchise')
            ->where('type', 'created')
            ->orderBy('id', 'DESC');

        // Initialize date filters
        $startDate = null;
        $endDate = null;

        // If user selects start_date
        if ($request->has('start_date') && !empty($request->start_date)) {
            $startDate = Carbon::parse($request->start_date)->startOfDay();
            $paymentHistory = $paymentHistory->where('created_at', '>=', $startDate);
        }

        // If user selects end_date
        if ($request->has('end_date') && !empty($request->end_date)) {
            $endDate = Carbon::parse($request->end_date)->endOfDay();
            $paymentHistory = $paymentHistory->where('created_at', '<=', $endDate);
        }

        // If no date filter provided, show only today's records
        if (!$request->has('start_date') && !$request->has('end_date')) {
            $todayStart = Carbon::today()->startOfDay();
            $todayEnd = Carbon::today()->endOfDay();
            $paymentHistory = $paymentHistory->whereBetween('created_at', [$todayStart, $todayEnd]);
        }

        // Clone for total calculation
        $totalQuery = clone $paymentHistory;
        $total = $totalQuery->sum('amount');

        // Get the filtered data
        $paymentHistory = $paymentHistory->get();

        // Return view with total
        return view('admin.payment.paymentCreatedHistory', compact('paymentHistory', 'total'));

    } catch (Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Error fetching payment: ' . $e->getMessage(),
        ]);
    }
        
    }

    public function store(Request $request)
    {
        $input = $request->all();

           $amount = $input['amount'];

            $franchiseDetails = Franchise::findorfail($input['franchise']);

            FranchiseCredit::create([
                'credit_amount' => $amount,
                'franchise_id' => $input['franchise']
            ]);

            try {
                $walletBalanceIncrement = $request->input('amount', 0);
                $franchiseDetails->increment('credit_balance', $walletBalanceIncrement);
                $franchiseDetails->save();
                return redirect()->back()->with('success', 'Franchise updated successfully!');
            } catch (\Exception $th) {
                return back()->with('error', $th->getMessage())->withInput();
            }
        


                return redirect()->back()->with('success', 'Franchise Created Amount Successfully!');

           

                
    }

    public function speed_post_parcel(Request $request,$id,  RateCalculator $rateCalculator){
     // return $request->all();
        $totalParcels = 0;
        $totalAmount = 0;
        $CodtotalAmount = 0;
        $type = $request->type;
        $searchKey = $request->input("searchKey");

        $fromdate = $request->input("fromdate");
        $todate = $request->input("todate");
        $franchise = Franchise::find($id);

        $query = GotogoSpeedPostParcel::where("franchise_id", $id)
       
            // ->where("insert_type", $request->insert_type)
            ->where("status", 0)
            ->orderBy("created_at", "desc");

        // if ($request->type == "cod") {
        //     $query->where("payment_method", "cod");
        // } else {
        //     $query->where("payment_method", "prepaid");
        // }

        if ($searchKey) {
            $query->where(function ($q) use ($searchKey) {
                $q->where("pickup_name", "LIKE", "%{$searchKey}%")

                    ->orWhere("pickup_mobile", "LIKE", "%{$searchKey}%")

                    ->orWhere("pickup_email", "LIKE", "%{$searchKey}%")

                    ->orWhere("pickup_pincode", "LIKE", "%{$searchKey}%")

                    ->orWhere("pickup_city", "LIKE", "%{$searchKey}%")

                    ->orWhere("pickup_state", "LIKE", "%{$searchKey}%")

                    ->orWhere("pickup_address", "LIKE", "%{$searchKey}%")

                    ->orWhere("consignee_name", "LIKE", "%{$searchKey}%")

                    ->orWhere("consignee_mobile", "LIKE", "%{$searchKey}%")

                    ->orWhere("consignee_email", "LIKE", "%{$searchKey}%")

                    ->orWhere("consignee_pincode", "LIKE", "%{$searchKey}%")

                    ->orWhere("consignee_city", "LIKE", "%{$searchKey}%")

                    ->orWhere("consignee_state", "LIKE", "%{$searchKey}%")

                    ->orWhere("consignee_address", "LIKE", "%{$searchKey}%")

                    ->orWhere("barcode_no", "LIKE", "%{$searchKey}%");
            });

            if (!empty($fromdate) || !empty($todate)) {
                if (!empty($fromdate) && !empty($todate)) {
                    $fromdate = Carbon::createFromFormat("d-m-Y", $fromdate)
                        ->startOfDay()
                        ->toDateString();
                    $todate = Carbon::createFromFormat("d-m-Y", $todate)
                        ->startOfDay()
                        ->toDateString();
                    $query->whereBetween("created_at", [$fromdate, $todate]);
                } elseif (!empty($fromdate)) {
                    // सिर्फ fromdate मिला
                    $fromdate = Carbon::createFromFormat(
                        "d-m-Y",
                        $fromdate
                    )->toDateString();
                    $query->whereDate("created_at", $fromdate);
                } elseif (!empty($todate)) {
                    // सिर्फ todate मिला
                    $todate = Carbon::createFromFormat(
                        "d-m-Y",
                        $todate
                    )->toDateString();
                    $query->whereDate("created_at", $todate);
                }
            }

            $datas = $query->get();
            // $CodtotalAmount = (clone $query)->sum("cod_amount");
            $totalAmount = (clone $query)->sum("payment_amount");
            $totalParcels = (clone $query)->count();

            return view(
                "admin.services.super_speed",
                compact(
                    "datas",
                    "type",
                    "totalAmount",
                    "totalParcels",
                    // "CodtotalAmount",
                    "franchise"
                )
            );
        }

        if (!empty($fromdate) || !empty($todate)) {
            if (!empty($fromdate) && !empty($todate)) {
                $fromdate = Carbon::createFromFormat("d-m-Y", $fromdate)
                    ->startOfDay()
                    ->toDateString();
                $todate = Carbon::createFromFormat("d-m-Y", $todate)
                    ->startOfDay()
                    ->toDateString();
                $query->whereBetween("created_at", [$fromdate, $todate]);
            } elseif (!empty($fromdate)) {
                // सिर्फ fromdate मिला
                $fromdate = Carbon::createFromFormat(
                    "d-m-Y",
                    $fromdate
                )->toDateString();
                $query->whereDate("created_at", $fromdate);
            } elseif (!empty($todate)) {
                // सिर्फ todate मिला
                $todate = Carbon::createFromFormat(
                    "d-m-Y",
                    $todate
                )->toDateString();
                $query->whereDate("created_at", $todate);
            }
            $datas = $query->get();
            // $CodtotalAmount = (clone $query)->sum("cod_amount");
            $totalAmount = (clone $query)->sum("payment_amount");
            $totalParcels = (clone $query)->count();
            return view(
                "admin.services.super_speed",
                compact(
                    "datas",
                    "type",
                    "totalAmount",
                    "totalParcels",
                    // "CodtotalAmount",
                    "franchise"
                )
            );
        } else {
            $query->whereDate("created_at", Carbon::today());
            $datas = $query->get();
            // $CodtotalAmount = (clone $query)->sum("cod_amount");
            $totalAmount = (clone $query)->sum("payment_amount");
            $totalParcels = (clone $query)->count();

            return view(
                "admin.services.super_speed",
                compact(
                    "datas",
                    "type",
                    "totalAmount",
                    "totalParcels",
                    // "CodtotalAmount",
                    "franchise"
                )
            );
        }
    }

    public function bussiness_parcel($id){
        return $id;
      return $data= IndiaPostSpeedPostParcel::where('franchise_id',$id)->get();
        return view(); return view('admin.services.super_speed', compact('data', 'total'));
    }

    public function registered(){
        
    }

//   public function india_post_speed($id)
// {
//     // Build query
//     $query = IndiaPostSpeedPostParcel::where('franchise_id', $id);

//     // Fetch data
//     $datas = $query->get();

//     // Stats
//     $CodtotalAmount = $query->sum("cod_amount");
//     $totalAmount = $query->sum("payment_amount");
//     $totalParcels = $query->count();

//     // Return view with all variables
//     return view(
//         'admin.services.india_post',
//         compact('datas', 'CodtotalAmount', 'totalAmount', 'totalParcels')
//     );
// }

   public function india_post_speed(Request $request,$id,  RateCalculator $rateCalculator)
    {
        // return $request->all();
        $totalParcels = 0;
        $totalAmount = 0;
        $CodtotalAmount = 0;
        $type = $request->type;
        $searchKey = $request->input("searchKey");

        $fromdate = $request->input("fromdate");
        $todate = $request->input("todate");
        $franchise = Franchise::find($id);

        $query = IndiaPostSpeedPostParcel::where("franchise_id", $id)
       
            // ->where("insert_type", $request->insert_type)
            ->where("status", 0)
            ->orderBy("created_at", "desc");

        if ($request->type == "cod") {
            $query->where("payment_method", "cod");
        } else {
            $query->where("payment_method", "prepaid");
        }

        if ($searchKey) {
            $query->where(function ($q) use ($searchKey) {
                $q->where("pickup_name", "LIKE", "%{$searchKey}%")

                    ->orWhere("pickup_mobile", "LIKE", "%{$searchKey}%")

                    ->orWhere("pickup_email", "LIKE", "%{$searchKey}%")

                    ->orWhere("pickup_pincode", "LIKE", "%{$searchKey}%")

                    ->orWhere("pickup_city", "LIKE", "%{$searchKey}%")

                    ->orWhere("pickup_state", "LIKE", "%{$searchKey}%")

                    ->orWhere("pickup_address", "LIKE", "%{$searchKey}%")

                    ->orWhere("consignee_name", "LIKE", "%{$searchKey}%")

                    ->orWhere("consignee_mobile", "LIKE", "%{$searchKey}%")

                    ->orWhere("consignee_email", "LIKE", "%{$searchKey}%")

                    ->orWhere("consignee_pincode", "LIKE", "%{$searchKey}%")

                    ->orWhere("consignee_city", "LIKE", "%{$searchKey}%")

                    ->orWhere("consignee_state", "LIKE", "%{$searchKey}%")

                    ->orWhere("consignee_address", "LIKE", "%{$searchKey}%")

                    ->orWhere("barcode_no", "LIKE", "%{$searchKey}%");
            });

            if (!empty($fromdate) || !empty($todate)) {
                if (!empty($fromdate) && !empty($todate)) {
                    $fromdate = Carbon::createFromFormat("d-m-Y", $fromdate)
                        ->startOfDay()
                        ->toDateString();
                    $todate = Carbon::createFromFormat("d-m-Y", $todate)
                        ->startOfDay()
                        ->toDateString();
                    $query->whereBetween("created_at", [$fromdate, $todate]);
                } elseif (!empty($fromdate)) {
                    // सिर्फ fromdate मिला
                    $fromdate = Carbon::createFromFormat(
                        "d-m-Y",
                        $fromdate
                    )->toDateString();
                    $query->whereDate("created_at", $fromdate);
                } elseif (!empty($todate)) {
                    // सिर्फ todate मिला
                    $todate = Carbon::createFromFormat(
                        "d-m-Y",
                        $todate
                    )->toDateString();
                    $query->whereDate("created_at", $todate);
                }
            }

            $datas = $query->get();
            $CodtotalAmount = (clone $query)->sum("cod_amount");
            $totalAmount = (clone $query)->sum("payment_amount");
            $totalParcels = (clone $query)->count();

            return view(
                "admin.services.india_post",
                compact(
                    "datas",
                    "type",
                    "totalAmount",
                    "totalParcels",
                    "CodtotalAmount",
                    "franchise"
                )
            );
        }

        if (!empty($fromdate) || !empty($todate)) {
            if (!empty($fromdate) && !empty($todate)) {
                $fromdate = Carbon::createFromFormat("d-m-Y", $fromdate)
                    ->startOfDay()
                    ->toDateString();
                $todate = Carbon::createFromFormat("d-m-Y", $todate)
                    ->startOfDay()
                    ->toDateString();
                $query->whereBetween("created_at", [$fromdate, $todate]);
            } elseif (!empty($fromdate)) {
                // सिर्फ fromdate मिला
                $fromdate = Carbon::createFromFormat(
                    "d-m-Y",
                    $fromdate
                )->toDateString();
                $query->whereDate("created_at", $fromdate);
            } elseif (!empty($todate)) {
                // सिर्फ todate मिला
                $todate = Carbon::createFromFormat(
                    "d-m-Y",
                    $todate
                )->toDateString();
                $query->whereDate("created_at", $todate);
            }
            $datas = $query->get();
            $CodtotalAmount = (clone $query)->sum("cod_amount");
            $totalAmount = (clone $query)->sum("payment_amount");
            $totalParcels = (clone $query)->count();
            return view(
                "admin.services.india_post",
                compact(
                    "datas",
                    "type",
                    "totalAmount",
                    "totalParcels",
                    "CodtotalAmount",
                    "franchise"
                )
            );
        } else {
            $query->whereDate("created_at", Carbon::today());
            $datas = $query->get();
            $CodtotalAmount = (clone $query)->sum("cod_amount");
            $totalAmount = (clone $query)->sum("payment_amount");
            $totalParcels = (clone $query)->count();

            return view(
                "admin.services.india_post",
                compact(
                    "datas",
                    "type",
                    "totalAmount",
                    "totalParcels",
                    "CodtotalAmount",
                    "franchise"
                )
            );
        }
    }


   public function bussiness_post(Request $request,$id,  RateCalculator $rateCalculator)
    {
        // return $request->all();
        $totalParcels = 0;
        $totalAmount = 0;
        $CodtotalAmount = 0;
        $type = $request->type;
        $searchKey = $request->input("searchKey");

        $fromdate = $request->input("fromdate");
        $todate = $request->input("todate");
        $franchise = Franchise::find($id);

        $query = IndiaPostBusinessParcel::where("franchise_id", $id)->where('parcel_type',1)
       
            // ->where("insert_type", $request->insert_type)
            ->where("status", 0)
            ->orderBy("created_at", "desc");

        if ($request->type == "cod") {
            $query->where("payment_method", "cod");
        } else {
            $query->where("payment_method", "prepaid");
        }

        if ($searchKey) {
            $query->where(function ($q) use ($searchKey) {
                $q->where("pickup_name", "LIKE", "%{$searchKey}%")

                    ->orWhere("pickup_mobile", "LIKE", "%{$searchKey}%")

                    ->orWhere("pickup_email", "LIKE", "%{$searchKey}%")

                    ->orWhere("pickup_pincode", "LIKE", "%{$searchKey}%")

                    ->orWhere("pickup_city", "LIKE", "%{$searchKey}%")

                    ->orWhere("pickup_state", "LIKE", "%{$searchKey}%")

                    ->orWhere("pickup_address", "LIKE", "%{$searchKey}%")

                    ->orWhere("consignee_name", "LIKE", "%{$searchKey}%")

                    ->orWhere("consignee_mobile", "LIKE", "%{$searchKey}%")

                    ->orWhere("consignee_email", "LIKE", "%{$searchKey}%")

                    ->orWhere("consignee_pincode", "LIKE", "%{$searchKey}%")

                    ->orWhere("consignee_city", "LIKE", "%{$searchKey}%")

                    ->orWhere("consignee_state", "LIKE", "%{$searchKey}%")

                    ->orWhere("consignee_address", "LIKE", "%{$searchKey}%")

                    ->orWhere("barcode_no", "LIKE", "%{$searchKey}%");
            });

            if (!empty($fromdate) || !empty($todate)) {
                if (!empty($fromdate) && !empty($todate)) {
                    $fromdate = Carbon::createFromFormat("d-m-Y", $fromdate)
                        ->startOfDay()
                        ->toDateString();
                    $todate = Carbon::createFromFormat("d-m-Y", $todate)
                        ->startOfDay()
                        ->toDateString();
                    $query->whereBetween("created_at", [$fromdate, $todate]);
                } elseif (!empty($fromdate)) {
                    // सिर्फ fromdate मिला
                    $fromdate = Carbon::createFromFormat(
                        "d-m-Y",
                        $fromdate
                    )->toDateString();
                    $query->whereDate("created_at", $fromdate);
                } elseif (!empty($todate)) {
                    // सिर्फ todate मिला
                    $todate = Carbon::createFromFormat(
                        "d-m-Y",
                        $todate
                    )->toDateString();
                    $query->whereDate("created_at", $todate);
                }
            }

            $datas = $query->get();
            $CodtotalAmount = (clone $query)->sum("cod_amount");
            $totalAmount = (clone $query)->sum("payment_amount");
            $totalParcels = (clone $query)->count();

            return view(
                "admin.services.india_bussines",
                compact(
                    "datas",
                    "type",
                    "totalAmount",
                    "totalParcels",
                    "CodtotalAmount",
                    "franchise"
                )
            );
        }

        if (!empty($fromdate) || !empty($todate)) {
            if (!empty($fromdate) && !empty($todate)) {
                $fromdate = Carbon::createFromFormat("d-m-Y", $fromdate)
                    ->startOfDay()
                    ->toDateString();
                $todate = Carbon::createFromFormat("d-m-Y", $todate)
                    ->startOfDay()
                    ->toDateString();
                $query->whereBetween("created_at", [$fromdate, $todate]);
            } elseif (!empty($fromdate)) {
                // सिर्फ fromdate मिला
                $fromdate = Carbon::createFromFormat(
                    "d-m-Y",
                    $fromdate
                )->toDateString();
                $query->whereDate("created_at", $fromdate);
            } elseif (!empty($todate)) {
                // सिर्फ todate मिला
                $todate = Carbon::createFromFormat(
                    "d-m-Y",
                    $todate
                )->toDateString();
                $query->whereDate("created_at", $todate);
            }
            $datas = $query->get();
            $CodtotalAmount = (clone $query)->sum("cod_amount");
            $totalAmount = (clone $query)->sum("payment_amount");
            $totalParcels = (clone $query)->count();
            return view(
                "admin.services.india_bussines",
                compact(
                    "datas",
                    "type",
                    "totalAmount",
                    "totalParcels",
                    "CodtotalAmount",
                    "franchise"
                )
            );
        } else {
            $query->whereDate("created_at", Carbon::today());
            $datas = $query->get();
            $CodtotalAmount = (clone $query)->sum("cod_amount");
            $totalAmount = (clone $query)->sum("payment_amount");
            $totalParcels = (clone $query)->count();

            return view(
                "admin.services.india_bussines",
                compact(
                    "datas",
                    "type",
                    "totalAmount",
                    "totalParcels",
                    "CodtotalAmount",
                    "franchise"
                )
            );
        }
    }

    public function bussiness_post_air(Request $request,  RateCalculator $rateCalculator)
    {
        return $request->all();
        $totalParcels = 0;
        $totalAmount = 0;
        $CodtotalAmount = 0;
        $type = $request->type;
        $id = $request->id;
        $searchKey = $request->input("searchKey");

        $fromdate = $request->input("fromdate");
        $todate = $request->input("todate");
        $franchise = Franchise::find($id);

        $query = IndiaPostBusinessParcel::where("franchise_id", $id)->where('parcel_type',1)
       
            // ->where("insert_type", $request->insert_type)
            ->where("status", 0)
            ->orderBy("created_at", "desc");

        if ($request->type == "cod") {
            $query->where("payment_method", "cod");
        } else {
            $query->where("payment_method", "prepaid");
        }

        if ($searchKey) {
            $query->where(function ($q) use ($searchKey) {
                $q->where("pickup_name", "LIKE", "%{$searchKey}%")

                    ->orWhere("pickup_mobile", "LIKE", "%{$searchKey}%")

                    ->orWhere("pickup_email", "LIKE", "%{$searchKey}%")

                    ->orWhere("pickup_pincode", "LIKE", "%{$searchKey}%")

                    ->orWhere("pickup_city", "LIKE", "%{$searchKey}%")

                    ->orWhere("pickup_state", "LIKE", "%{$searchKey}%")

                    ->orWhere("pickup_address", "LIKE", "%{$searchKey}%")

                    ->orWhere("consignee_name", "LIKE", "%{$searchKey}%")

                    ->orWhere("consignee_mobile", "LIKE", "%{$searchKey}%")

                    ->orWhere("consignee_email", "LIKE", "%{$searchKey}%")

                    ->orWhere("consignee_pincode", "LIKE", "%{$searchKey}%")

                    ->orWhere("consignee_city", "LIKE", "%{$searchKey}%")

                    ->orWhere("consignee_state", "LIKE", "%{$searchKey}%")

                    ->orWhere("consignee_address", "LIKE", "%{$searchKey}%")

                    ->orWhere("barcode_no", "LIKE", "%{$searchKey}%");
            });

            if (!empty($fromdate) || !empty($todate)) {
                if (!empty($fromdate) && !empty($todate)) {
                    $fromdate = Carbon::createFromFormat("d-m-Y", $fromdate)
                        ->startOfDay()
                        ->toDateString();
                    $todate = Carbon::createFromFormat("d-m-Y", $todate)
                        ->startOfDay()
                        ->toDateString();
                    $query->whereBetween("created_at", [$fromdate, $todate]);
                } elseif (!empty($fromdate)) {
                    // सिर्फ fromdate मिला
                    $fromdate = Carbon::createFromFormat(
                        "d-m-Y",
                        $fromdate
                    )->toDateString();
                    $query->whereDate("created_at", $fromdate);
                } elseif (!empty($todate)) {
                    // सिर्फ todate मिला
                    $todate = Carbon::createFromFormat(
                        "d-m-Y",
                        $todate
                    )->toDateString();
                    $query->whereDate("created_at", $todate);
                }
            }

            $datas = $query->get();
            $CodtotalAmount = (clone $query)->sum("cod_amount");
            $totalAmount = (clone $query)->sum("payment_amount");
            $totalParcels = (clone $query)->count();

            return view(
                "admin.services.india_bussines_air",
                compact(
                    "datas",
                    "type",
                    "totalAmount",
                    "totalParcels",
                    "CodtotalAmount",
                    "franchise"
                )
            );
        }

        if (!empty($fromdate) || !empty($todate)) {
            if (!empty($fromdate) && !empty($todate)) {
                $fromdate = Carbon::createFromFormat("d-m-Y", $fromdate)
                    ->startOfDay()
                    ->toDateString();
                $todate = Carbon::createFromFormat("d-m-Y", $todate)
                    ->startOfDay()
                    ->toDateString();
                $query->whereBetween("created_at", [$fromdate, $todate]);
            } elseif (!empty($fromdate)) {
                // सिर्फ fromdate मिला
                $fromdate = Carbon::createFromFormat(
                    "d-m-Y",
                    $fromdate
                )->toDateString();
                $query->whereDate("created_at", $fromdate);
            } elseif (!empty($todate)) {
                // सिर्फ todate मिला
                $todate = Carbon::createFromFormat(
                    "d-m-Y",
                    $todate
                )->toDateString();
                $query->whereDate("created_at", $todate);
            }
            $datas = $query->get();
            $CodtotalAmount = (clone $query)->sum("cod_amount");
            $totalAmount = (clone $query)->sum("payment_amount");
            $totalParcels = (clone $query)->count();
            return view(
                "admin.services.india_bussines_air",
                compact(
                    "datas",
                    "type",
                    "totalAmount",
                    "totalParcels",
                    "CodtotalAmount",
                    "franchise"
                )
            );
        } else {
            $query->whereDate("created_at", Carbon::today());
            $datas = $query->get();
            $CodtotalAmount = (clone $query)->sum("cod_amount");
            $totalAmount = (clone $query)->sum("payment_amount");
            $totalParcels = (clone $query)->count();

            return view(
                "admin.services.india_bussines_air",
                compact(
                    "datas",
                    "type",
                    "totalAmount",
                    "totalParcels",
                    "CodtotalAmount",
                    "franchise"
                )
            );
        }
    }

}
