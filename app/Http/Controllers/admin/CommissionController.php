<?php
namespace App\Http\Controllers\admin;
use App\Http\Controllers\Controller;

use App\Models\Commission;

use Illuminate\Http\Request;
use App\Models\Franchise;
use App\Models\FranchiseServiceCommission;




class CommissionController extends Controller

{

    public function index(Request $request)

    {

        // Commission::query()->truncate();

        $title = Commission::getMemberType($request->membertype);

        // Commission::where('membertype', $request->membertype)
        //     ->where('servicetype', 1)
        //     ->delete();

        // dd('deleted');

        $speedrates = Commission::where('membertype', $request->membertype)
            ->where('servicetype', 1)
            ->get();




        $businessrates = Commission::where('membertype', $request->membertype)
            ->where('servicetype', 3)
            ->get();


        $legalrates = Commission::where('membertype', $request->membertype)
            ->where('servicetype', 4)
            ->get();

        $e2hrates = Commission::where('membertype', $request->membertype)
            ->where('servicetype', 9)
            ->get();

        return view('admin.commission.index', [
            'title' => $title,
            'speedrates' => $speedrates,
            'businessrates' => $businessrates,
            'legalrates' => $legalrates,
            'e2hrates' => $e2hrates,
        ]);
    }

   public function franchiseCommission()
{
    $franchises = Franchise::select('id', 'franchise_no')
        ->orderBy('id', 'desc')
        ->get();

    return view('admin.commission.franchise', compact('franchises'));
}

public function saveFranchiseCommission(Request $request)
{
    $request->validate([
        'franchise_id' => 'required|exists:franchises,id',

        'commission.1' => 'required|in:5,10,15,20',
        'commission.3' => 'required|in:5,10,15,20',
        'commission.4' => 'required|in:5,10,15,20',
        'commission.5' => 'required|in:5,10,15,20',
        'commission.6' => 'required|in:5,10,15,20',
        'commission.9' => 'required|in:5,10,15,20',
    ]);

    $serviceTypes = [1, 3, 4, 5, 6, 9];

    foreach ($serviceTypes as $serviceType) {
        FranchiseServiceCommission::updateOrCreate(
            [
                'franchise_id' => $request->franchise_id,
                'service_type' => $serviceType,
            ],
            [
                'commission_rate' => $request->commission[$serviceType],
                'is_active' => true,
            ]
        );
    }

    return redirect()
        ->back()
        ->with('success', 'Franchise commission updated successfully.');
}



    public function create(Request $request)

    {



        if (count(Commission::where('type', $request->type)->get()) == 0) {

            return view('admin.commission.create');
        } else {

            return redirect()->route('admin.commission.index');
        }
    }



    public function store(Request $request)

    {



        if ($request->serviceType == 1) {

            $this->validate($request, [
                'w250gm' => 'required',
                'w250gm500gm' => 'required',
                'w500gm1000gm' => 'required',
                'w1000gm1500gm' => 'required',
                'w1500gm2000gm' => 'required',
                'w2000gm2500gm' => 'required',
                'w2500gm3000gm' => 'required',
                'w3000gm3500gm' => 'required',
                'w3500gm4000gm' => 'required',
                'w4000gm4500gm' => 'required',
                'w4500gm5000gm' => 'required',
            ]);


            try {
                // Create Commissions for each weight category
                $weights = [
                    'Up to 250 gm' => $request->w250gm,
                    '250gm to 500gm' => $request->w250gm500gm,
                    '500gm to 1kg' => $request->w500gm1000gm,
                    '1kg to 1.5kg' => $request->w1000gm1500gm,
                    '1.5kg to 2kg' => $request->w1500gm2000gm,
                    '2kg to 2.5kg' => $request->w2000gm2500gm,
                    '2.5kg to 3kg' => $request->w2500gm3000gm,
                    '3kg to 3.5kg' => $request->w3000gm3500gm,
                    '3.5kg to 4kg' => $request->w3500gm4000gm,
                    '4kg to 4.5kg' => $request->w4000gm4500gm,
                    '4.5kg to 5kg' => $request->w4500gm5000gm,
                ];

                foreach ($weights as $weight => $amount) {
                    Commission::create([
                        'weight' => $weight,
                        'amount' => $amount,
                        'membertype' => $request->query('membertype'),
                        'servicetype' => $request->query('serviceType'),
                    ]);
                }

                return redirect()->route('admin.commission.index', ['membertype' => $request->query('membertype')])->with('success', 'Commission Rates Created');
            } catch (\Exception $th) {
                return back()->with('error', $th->getMessage());
            }
        }



        if ($request->serviceType == 3) {

            $this->validate($request, [
                'w2000gm' => 'required',
                'w2000gm3000gm' => 'required',
            ]);

            try {
                $weights = [
                    'First 2 KG' => $request->w2000gm,
                    'Additional 1kg' => $request->w2000gm3000gm,
                ];

                foreach ($weights as $weight => $amount) {
                    Commission::create([
                        'weight' => $weight,
                        'amount' => $amount,
                        'membertype' => $request->query('membertype'),
                        'servicetype' => $request->query('serviceType'),
                    ]);
                }
                return redirect()->route('admin.commission.index', ['membertype' => $request->query('membertype')])->with('success', 'Commission Rates Created');
            } catch (\Exception $th) {
                return back()->with('error', $th->getMessage());
            }
        }


        if ($request->serviceType == 4) {

            $this->validate($request, [
                'w100gm' => 'required',
            ]);

            try {
                $weights = [
                    'Upto 100 gms' => $request->w100gm,

                ];

                foreach ($weights as $weight => $amount) {
                    Commission::create([
                        'weight' => $weight,
                        'amount' => $amount,
                        'membertype' => $request->query('membertype'),
                        'servicetype' => $request->query('serviceType'),
                    ]);
                }
                return redirect()->route('admin.commission.index', ['membertype' => $request->query('membertype')])->with('success', 'Commission Rates Created');
            } catch (\Exception $th) {
                return back()->with('error', $th->getMessage());
            }
        }


        if ($request->serviceType == 9) {

            // Validate the inputs for pages
            $this->validate($request, [
                'first2pages' => 'required',
                'additional_pages' => 'required',
            ]);

            try {
                // Prepare the rates data based on the number of pages
                $rates = [
                    'First 5 Pages' => $request->first2pages,
                    'Additional Pages' => $request->additional_pages,
                ];

                // Store each rate in the database
                foreach ($rates as $category => $amount) {
                    Commission::create([
                        'weight' => $category,
                        'amount' => $amount,
                        'membertype' => $request->query('membertype'),
                        'serviceType' => $request->query('serviceType'),
                    ]);
                }

                // Redirect with success message
                return redirect()->route('admin.commission.index', ['membertype' => $request->query('membertype')])
                    ->with('success', 'Postal Rates Created');
            } catch (\Exception $th) {
                // Redirect back with error message if an exception occurs
                return back()->with('error', $th->getMessage());
            }
        }
    }





    public function edit(Request $request)
    {
        $title = Commission::getMemberType($request->membertype);

        if ($request->serviceType == 1) {
            $this->validate($request, [
                'w250gm' => 'required',
                'w250gm500gm' => 'required',
                'w500gm1000gm' => 'required',
            ]);


            try {
                $membertype = $request->query('membertype');
                $serviceType = $request->query('serviceType');

                // Retrieve existing rates
                $speedrates = Commission::where('membertype', $membertype)
                    ->where('servicetype', $serviceType)
                    ->get();

                // Prepare the rate data
                $ratesData = [
                    ['weight' => 'Up to 250 gm', 'amount' => $request->input('w250gm')],
                    ['weight' => '250gm to 500gm', 'amount' => $request->input('w250gm500gm')],
                    ['weight' => '500gm to 5kg', 'amount' => $request->input('w500gm1000gm')],
                    // ['weight' => '1kg to 1.5kg', 'amount' => $request->input('w1000gm1500gm')],
                    // ['weight' => '1.5kg to 2kg', 'amount' => $request->input('w1500gm2000gm')],
                    // ['weight' => '2kg to 2.5kg', 'amount' => $request->input('w2000gm2500gm')],
                    // ['weight' => '2.5kg to 3kg', 'amount' => $request->input('w2500gm3000gm')],
                    // ['weight' => '3kg to 3.5kg', 'amount' => $request->input('w3000gm3500gm')],
                    // ['weight' => '3.5kg to 4kg', 'amount' => $request->input('w3500gm4000gm')],
                    // ['weight' => '4kg to 4.5kg', 'amount' => $request->input('w4000gm4500gm')],
                    // ['weight' => '4.5kg to 5kg', 'amount' => $request->input('w4500gm5000gm')],
                ];

                foreach ($speedrates as $index => $rate) {
                    $rate->update([
                        'weight' => $ratesData[$index]['weight'],
                        'amount' => $ratesData[$index]['amount'],
                        'membertype' => $membertype,
                        'servicetype' => $serviceType,
                    ]);
                }

                return redirect()->back()->with('success', 'Rates updated successfully.');
            } catch (\Exception $e) {
                return redirect()->back()->with('error', 'Failed to update rates: ' . $e->getMessage());
            }
        }

        if ($request->serviceType == 3) {
            if ($request->isMethod('POST')) {
                $this->validate($request, [
                    'w2000gm' => 'required',
                    'w2000gm3000gm' => 'required',
                ]);

                try {
                    $membertype = $request->query('membertype');

                    // Retrieve existing business rates for the given membertype and servicetype 3
                    $businessrates = Commission::where('membertype', $membertype)
                        ->where('servicetype', 3)
                        ->get();

                    // Prepare the new rate data
                    $businessRatesData = [
                        ['weight' => 'First 1 KG', 'amount' => $request->input('w2000gm')],
                        ['weight' => 'Additional 500gm', 'amount' => $request->input('w2000gm3000gm')],
                    ];

                    // Update the rates
                    foreach ($businessrates as $index => $rate) {
                        if (isset($businessRatesData[$index])) {
                            $rate->update([
                                'weight' => $businessRatesData[$index]['weight'],
                                'amount' => $businessRatesData[$index]['amount'],
                                'membertype' => $membertype,
                                'servicetype' => $request->query('serviceType'),
                            ]);
                        }
                    }

                    return redirect()->back()->with('success', 'Business rates updated successfully.');
                } catch (\Exception $e) {
                    return redirect()->back()->with('error', 'Failed to update business rates: ' . $e->getMessage());
                }
            }
        }

        if ($request->serviceType == 4) {
            if ($request->isMethod('POST')) {
                // Validate the request
                $this->validate($request, [
                    'w100gm' => 'required',
                ]);

                try {
                    $membertype = $request->query('membertype');
                    $servicetype = $request->query('serviceType');

                    // Retrieve existing legal rates for the given membertype and servicetype 4
                    $legalrates = Commission::where('membertype', $membertype)
                        ->where('servicetype', 4)
                        ->get();

                    // Prepare the new rate data
                    $legalRatesData = [
                        ['weight' => 'Upto 100gms', 'amount' => $request->input('w100gm')],
                    ];

                    // Update the rates
                    foreach ($legalrates as $index => $rate) {
                        if (isset($legalRatesData[$index])) {
                            $rate->update([
                                'weight' => $legalRatesData[$index]['weight'],
                                'amount' => $legalRatesData[$index]['amount'],
                                'membertype' => $membertype,
                                'servicetype' => $servicetype,
                            ]);
                        }
                    }

                    return redirect()->back()->with('success', 'Legal rates updated successfully.');
                } catch (\Exception $e) {
                    return redirect()->back()->with('error', 'Failed to update legal rates: ' . $e->getMessage());
                }
            }
        }

        if ($request->serviceType == 9) {

            // Retrieve existing rates for the given membertype and servicetype 9
            $e2hrates = Commission::where('membertype', $request->membertype)
                ->where('servicetype', $request->serviceType)
                ->get();

            // Prepare new rate data
            $e2hRatesData = [
                ['weight' => 'First 5 Pages', 'amount' => $request->input('first2pages')],
                ['weight' => 'Additional Pages', 'amount' => $request->input('additional_pages')],
            ];
            // Validate input
            $this->validate($request, [
                'first2pages' => 'required',
                'additional_pages' => 'required',
            ]);

            try {
                $membertype = $request->query('membertype');

                // Update the rates
                foreach ($e2hrates as $index => $rate) {
                    if (isset($e2hRatesData[$index])) {
                        $rate->update([
                            'weight' => $e2hRatesData[$index]['weight'],
                            'amount' => $e2hRatesData[$index]['amount'],
                            'membertype' => $membertype,
                            'servicetype' => $request->query('serviceType'),
                        ]);
                    }
                }

                return redirect()->back()->with('success', 'Commission rates updated successfully.');
            } catch (\Exception $e) {
                return redirect()->back()->with('error', 'Failed to update commission rates: ' . $e->getMessage());
            }
        }
    }
}
