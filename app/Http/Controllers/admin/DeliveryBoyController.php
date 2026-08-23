<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\DeliveryBoy;
use App\Models\Franchise;
use App\Models\FranchiseBag;
use App\Models\FranchiseKyc;
use App\Models\GotogoSpeedPostParcel;
use App\Models\GotogoSuperFastParcel;
use App\Models\GotogoBusinessParcel;
use App\Models\GotogoRegisteredParcel;
use App\Models\IndiaPostSpeedPostParcel;
use App\Models\IndiaPostBusinessParcel;
use App\Models\IndiaPostRegisteredParcel;
use App\Models\DeliveryBoyCommissionDetail;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\admin\DeliveryBoyDailyBookingReportController;
use Illuminate\Support\Facades\View;
use App\Http\Controllers\franchise\RateCalculator;


class DeliveryBoyController extends Controller
{



    public function index(Request $request)

    {
        $datas = DeliveryBoy::get();
        return view('admin.DeliveryBoy.index', compact('datas'));
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

                $post->wallet_balance = $request->wallet_amount;

                $post->remaining_balance = $request->wallet_amount;

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

                return redirect()->route('admin.DeliveryBoy.index')->with('success', 'CMS created successfully! ID:' . $post->generated_id . ' Password:' . $password);
            } catch (\Exception $th) {

                return back()->with('error', $th->getMessage())->withInput();
            }
        }

        return view('admin.DeliveryBoy.create');
    }

    public function edit(Request $request, $id)
    {
        $post = DeliveryBoy::findorfail($id);
        $franchise = Franchise::where("status", 1)->get();


        if ($request->method() == 'POST') {

            $this->validate(
                $request,
                [
                    'name' => 'required',
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

                // $date = Carbon::now();

                $post->name = $request->name;
                $post->mobile = $request->mobile;
                $post->email = $request->email;
                $post->pincode = $request->pincode;
                $post->city = $request->city;
                $post->district = $request->district;
                $post->state = $request->state;
                $post->address = $request->address;
                $post->franchise_id = $request->franchise_id;
                $post->save();
                return redirect()->route('admin.deliveryBoy.index')->with('success', 'Delivery boy updated successfully!');
            } catch (\Exception $th) {
                return back()->with('error', $th->getMessage())->withInput();
            }
        }
        $data = $post;
        return view('admin.DeliveryBoy.edit', ['data' => $data, 'franchise' => $franchise]);
    }

    public function view(Request $request, $id)
    {


        $frachinse =  DeliveryBoy::findorfail($id);


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
        $formattedDate = Carbon::today()->format('Y-m-d');

        foreach ($models as $serviceType => $model) {
            $serviceNumber = $serviceNumbers[$serviceType] ?? null;
            $bagfromfranchise = FranchiseBag::where('service_type', $serviceNumber)
                ->where('delivery_boy_id',  $id)
                ->pluck('id')
                ->toArray();

            $parcels = $model::where(function ($query) use ($formattedDate, $bagfromfranchise) {
                $query->whereDate('delivered_date', '=', $formattedDate)
                    ->whereIn('source_franchise_bag_id', $bagfromfranchise)
                    ->orWhereIn('destination_franchise_bag_id', $bagfromfranchise)
                    ->orWhere(function ($subQuery) use ($formattedDate) {
                        $subQuery->where('otp', '0')
                            ->whereHas('cancelDelivery', function ($query) use ($formattedDate) {
                                $query->whereDate('created_at', '=', $formattedDate);
                            });
                    });
            })
                ->with('cancelDelivery')
                ->get();

            $parcels->each(function ($parcel) use ($serviceType, $serviceNumber) {
                $parcel->service_type = $serviceType;
                $parcel->service_number = $serviceNumber;
            });

            $allParcels = $allParcels->merge($parcels);
        }

        // return $parcel;
        $dailybookingdata = DeliveryBoyDailyBookingReportController::eachDeliveyBoy($id);


        if ($request->date && $request->type === "parcel") {

            $formattedDate =  Carbon::parse($request->date)->format('Y-m-d');
            $allParcels = collect();

            foreach ($models as $serviceType => $model) {
                $serviceNumber = $serviceNumbers[$serviceType] ?? null;
                $bagfromfranchise = FranchiseBag::where('service_type', $serviceNumber)
                    ->where('delivery_boy_id',  $id)
                    ->pluck('id')
                    ->toArray();

                $parcels = $model::where(function ($query) use ($formattedDate, $bagfromfranchise) {
                    $query->whereDate('delivered_date', '=', $formattedDate)
                        ->whereIn('source_franchise_bag_id', $bagfromfranchise)
                        ->orWhereIn('destination_franchise_bag_id', $bagfromfranchise)
                        ->orWhere(function ($subQuery) use ($formattedDate) {
                            $subQuery->where('otp', '0')
                                ->whereHas('cancelDelivery', function ($query) use ($formattedDate) {
                                    $query->whereDate('created_at', '=', $formattedDate);
                                });
                        });
                })
                    ->with('cancelDelivery')
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

            $dailybookingdata = DeliveryBoyDailyBookingReportController::eachDeliveyBoyfilterbyDate($id, $request->date);
            return response()->json(['status' => 200, 'message' => 'filter data succesfull', 'data' => $dailybookingdata]);
        }

        return view(
            'admin.DeliveryBoy.view',
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
            DeliveryBoy::findorfail($id)->delete();
            return redirect()->route('admin.deliveryBoy.index')->with('success', 'Delivery Boy Deleted Successfully');
        } catch (\Exception $th) {
            return back()->with('error', $th->getMessage())->withInput();
        }
    }



    public function commissions(Request $request)
    {
        $data = DeliveryBoy::all();

        return view('admin.DeliveryBoy.commissions', compact('data'));
    }



    public function commissionDetail(Request $request, $id, RateCalculator $rateCalculator)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $data = collect();
        if ($startDate && $endDate) {
            $startDate = \Carbon\Carbon::parse($startDate)->startOfDay();
            $endDate = \Carbon\Carbon::parse($endDate)->endOfDay();
            $data = DeliveryBoyCommissionDetail::where('delivery_boy_id', $id)
                ->selectRaw('
                delivery_boy_id, 
                service_type, 
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
            $data = DeliveryBoyCommissionDetail::where('delivery_boy_id', $id)
                ->selectRaw('
                delivery_boy_id, 
                service_type, 
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

        // Ensure all service types are present
        $allServiceTypes = [1, 3, 4, 5, 6, 9];
        $dataMap = $data->keyBy('service_type');

        foreach ($allServiceTypes as $serviceType) {
            if (!isset($dataMap[$serviceType])) {
                $data->push((object)[
                    'delivery_boy_id' => $id,
                    'service_type' => $serviceType,
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

        // Assign service names
        foreach ($data as $entry) {
            $entry->service_name = GotogoSpeedPostParcel::getServiceType($entry->service_type);
        }

        // Split data into Gotogo and India Post
        $gotogoCommission = [];
        $indiaPostCommission = [];
        $totalGotogoCommission = 0;
        $totalIndiaPostCommission = 0;

        foreach ($data as $entry) {
            if ($entry->service_type == 5 || $entry->service_type == 6) {
                $indiaPostCommission[] = $entry;
                $totalIndiaPostCommission += (float) $entry->total_commission;
            } else {
                $gotogoCommission[] = $entry;
                $totalGotogoCommission += (float) $entry->total_commission;
            }
        }

        $franchiseDetails = DeliveryBoy::findOrFail($id);

        return view('admin.DeliveryBoy.commissionDetail', compact(
            'gotogoCommission',
            'indiaPostCommission',
            'totalGotogoCommission',
            'totalIndiaPostCommission',
            'franchiseDetails'
        ));
    }

    public function printCommissionDetail(Request $request, $id, RateCalculator $rateCalculator)
    {
        $data = collect();
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        if ($startDate && $endDate) {
            $startDate = \Carbon\Carbon::parse($startDate)->startOfDay();
            $endDate = \Carbon\Carbon::parse($endDate)->endOfDay();
            $data = DeliveryBoyCommissionDetail::where('delivery_boy_id', $id)
                ->selectRaw('
                    delivery_boy_id, 
                    service_type, 
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
            $data = DeliveryBoyCommissionDetail::where('delivery_boy_id', $id)
                ->selectRaw('
                    delivery_boy_id, 
                    service_type, 
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

        $allServiceTypes = [1, 3, 4, 5, 6, 9];
        $dataMap = $data->keyBy('service_type');

        foreach ($allServiceTypes as $serviceType) {
            if (!isset($dataMap[$serviceType])) {
                $data->push((object)[
                    'delivery_boy_id' => $id,
                    'service_type' => $serviceType,
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

        foreach ($data as $entry) {
            $entry->service_name = GotogoSpeedPostParcel::getServiceType($entry->service_type);
        }

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

        $gstRate = 18;
        $tdsRate = 5;

        $gstGotogo = ($totalGotogoCommission * $gstRate) / 100;
        $tdsGotogo = ($totalGotogoCommission * $tdsRate) / 100;
        $totalGotogo = $totalGotogoCommission + $gstGotogo - $tdsGotogo;

        $gstIndiaPost = ($totalIndiaPostCommission * $gstRate) / 100;
        $tdsIndiaPost = ($totalIndiaPostCommission * $tdsRate) / 100;
        $totalIndiaPost = $totalIndiaPostCommission + $gstIndiaPost - $tdsIndiaPost;

        $franchiseDetails = DeliveryBoy::findOrFail($id);


        // Same data pass for printing
        $otherPageContent = View('admin.DeliveryBoy.printCommissionDetail', compact(
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


    public function status(Request $request)
    {

        try {
            $franchise = DeliveryBoy::findorfail($request->id);
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

            $franchise = DeliveryBoy::findorfail($request->delivery_boy_id);

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
}
