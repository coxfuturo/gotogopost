<?php

namespace App\Http\Controllers\franchise;

use App\Http\Controllers\Controller;
use App\Http\Controllers\franchise\RateCalculator;
use App\Models\DeliveryBoy;
use App\Models\FranchiseBag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\Franchise;
use App\Models\GotogoSpeedPostParcel;
use App\Models\GotogoSuperFastParcel;
use App\Models\GotogoBusinessParcel;
use App\Models\GotogoRegisteredParcel;
use App\Models\IndiaPostSpeedPostParcel;
use App\Models\IndiaPostBusinessParcel;
use App\Models\IndiaPostRegisteredParcel;
use App\Models\DeliveryBoyCommissionDetail;
use App\Http\Controllers\admin\DeliveryBoyDailyBookingReportController;
use App\Models\DeliveryBoyKyc;
// use App\Models\RateCalculator;
use Carbon\Carbon;
use DB;


class DeliveryBoyController extends Controller
{
    public function index()
    {

        // return FranchiseBag::all();
        // return DeliveryBoy::all();
        $datas = DeliveryBoy::where('franchise_id', Franchise::getFranchiseId())->get();

        // return  $datas;

        return view('franchise.delboy.index', compact('datas'));
    }

    public function create(Request $request)
    {



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
                    // 'wallet_status' => 'required',
                    // 'cod_charges' => 'required',
                    // 'delivery_charges' => 'required',
                    // 'commission' => 'required',
                    // 'pan_img' => 'file|size:1024',
                    // 'adhar_front_img' => 'file|max:1024',
                    // 'adhar_back_img' => 'file|max:1024',
                    // 'cheque_img' => 'file|max:1024',
                    // 'photo' => 'file|max:1024',
                ],
                // [
                //     'pan_img.max' => 'The PAN card image size must not exceed 1 MB.',
                //     'adhar_front_img.max' => 'The adhar card front image size must not exceed 1 MB.',
                //     'adhar_back_img.max' => 'The adhar card back image size must not exceed 1 MB.',
                //     'cheque_img.max' => 'The Cancel cheque image size must not exceed 1 MB.',
                //     'photo.max' => 'The photo size must not exceed 1 MB.',
                // ]
            );

            try {



                $post = new DeliveryBoy();
                $post->name = $request->name;
                $post->father_name = $request->father_name;
                $post->mobile = $request->mobile;
                $post->email = $request->email;
                $post->pincode = $request->pincode;
                $post->city = $request->city;
                $post->district = $request->district;
                $post->state = $request->state;
                $post->address = $request->address;
                $post->wallet_balance = 0;
                $post->generated_id = $request->generated_id;
                $post->delivery_boy_no = str_pad(rand(1, 999999), 6, '0', STR_PAD_LEFT);;
                $numbers = str_shuffle('0123456789');
                $password = substr($numbers, 0, 10);
                $post->franchise_id = Franchise::getFranchiseId();
                $post->password = Hash::make($password);
                $post->save();
                if ($post) {
                    $kyc = new DeliveryBoyKyc;
                    $kyc->delivery_boy_id = $post->id;
                    $kyc->adhar_card = $request->adhar_card;
                    if ($request->hasFile('adhar_front_img')) {
                        $file1 = $request->file('adhar_front_img');
                        $extension1 = $file1->getClientOriginalName();
                        $img1 = time() . '_' . $extension1;
                        $file1->move('tenancy/assets/delboy/' . $post->generated_id . '/', $img1);
                        $kyc->adhar_front_img = $img1;
                    }
                    if ($request->hasFile('adhar_back_img')) {
                        $file2 = $request->file('adhar_back_img');
                        $extension2 = $file2->getClientOriginalName();
                        $img2 = time() . '_' . $extension2;
                        $file2->move('tenancy/assets/delboy/' . $post->generated_id . '/', $img2);
                        $kyc->adhar_back_img = $img2;
                    }

                    if ($request->hasFile('photo')) {
                        $file5 = $request->file('photo');
                        $extension5 = $file5->getClientOriginalName();
                        $img5 = time() . '_' . $extension5;
                        $file5->move('tenancy/assets/delboy/' . $post->generated_id . '/', $img5);
                        $kyc->photo = $img5;
                    }
                    $kyc->save();
                    if (!$kyc) {
                        $post->delete();
                    }
                }

                return redirect()->route('franchise.delboy.index')->with('success', 'Franchise created successfully! ID:' . $post->generated_id . ' Password:' . $password);
            } catch (\Exception $th) {
                return back()->with('error', $th->getMessage())->withInput();
            }
        }
        return view('franchise.delboy.create');
    }




    public function edit(Request $request, $id)
    {
        $post = DeliveryBoy::findorfail($id);
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
                $post->wallet_balance = $request->wallet_amount;
                $post->save();
                return redirect()->route('franchise.delboy.index')->with('success', 'Delivery boy updated successfully!');
            } catch (\Exception $th) {
                return back()->with('error', $th->getMessage())->withInput();
            }
        }
        $data = $post;
        return view('franchise.delboy.edit', compact('data'));
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
            'franchise.delboy.view',
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
            return redirect()->route('franchise.delboy.index')->with('success', 'Delivery Boy Deleted Successfully');
        } catch (\Exception $th) {
            return back()->with('error', $th->getMessage())->withInput();
        }
    }

    public function commissionDetail(Request $request, RateCalculator $rateCalculator, $id)
    {

        return 'hhjjdsf';
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        if ($startDate && $endDate) {
            $startDate = \Carbon\Carbon::parse($startDate)->startOfDay();
            $endDate = \Carbon\Carbon::parse($endDate)->endOfDay();
            $data = DeliveryBoyCommissionDetail::where('franchise_id', $id)
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
            $data = DeliveryBoyCommissionDetail::where('franchise_id', $id)
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

        return view('deliveryBoy.commission.index', compact(
            'gotogoCommission',
            'indiaPostCommission',
            'totalGotogoCommission',
            'totalIndiaPostCommission',
            'franchiseDetails'
        ));
    }

    public function printCommissionDetail(Request $request, RateCalculator $rateCalculator)
    {
        $id = Franchise::getFranchiseId();

        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        if ($startDate && $endDate) {
            $startDate = \Carbon\Carbon::parse($startDate)->startOfDay();
            $endDate = \Carbon\Carbon::parse($endDate)->endOfDay();
            $data = DeliveryBoyCommissionDetail::where('franchise_id', $id)
                ->selectRaw('id, franchise_id, service_type, created_at, SUM(amount) as total_amount, SUM(commission) as total_commission')
                ->whereBetween('created_at', [$startDate, $endDate])
                ->groupBy('service_type')
                ->get();
        } else {
            $today = Carbon::today();
            $data = DeliveryBoyCommissionDetail::where('franchise_id', $id)
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
        $otherPageContent = View('deliveryBoy.commission.printCommissionDetail', compact(
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

    public function viewDetails($id, $service){
        
        $Model = FranchiseBag::getServiceModel($service);
        $data =  $Model::where('barcode_no', $id)->first();

        $fuel_charge = $data->fuel_charge;
        $pickup_charge = $data->pickup_charge;
        $other_service_charge = $data->other_service_charge;
        $total_payment_amount = $data->payment_amount;
        $net_price = $total_payment_amount / 1.18;
        $amount = $net_price - ($fuel_charge + $pickup_charge + $other_service_charge);
        $gst = number_format($net_price * 0.18, 2, '.', '');
        $net_price_formatted = number_format($net_price, 2, '.', '');


        $rateDetails = [
            'fuel_charge' => $fuel_charge,
            'pickup_charge' => $pickup_charge,
            'other_service_charge' => $other_service_charge,
            'total_payment_amount' => number_format($total_payment_amount, 2, '.', ''),
            'net_price' => $net_price_formatted,
            'amount' => number_format($amount, 2, '.', ''),
            'gst' => $gst,
        ];
       
        return view('franchise.delboy.details', compact('data', 'rateDetails'));
    }
}
