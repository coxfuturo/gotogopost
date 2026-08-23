<?php



namespace App\Http\Controllers\admin;



use App\Http\Controllers\Controller;

use App\Models\IndiaPostCommission;

use Illuminate\Http\Request;





class IndiaPostCommissionController extends Controller

{

    public function index(Request $request)

    {
        $india_commission = IndiaPostCommission::get();
        return view('admin.india-post-commission.index', [
            'india_commission' => $india_commission,
        ]);
    }



    public function create(Request $request)

    {
        if (count(Commission::get()) == 0) {

            return view('admin.india-post-commission.create');
        } else {

            return redirect()->route('admin.india-post-commission.index');
        }
    }



    // public function store(Request $request)

    // {

    //     if ($request->serviceType == 1) {

    //         $this->validate($request, [
    //             'w250gm' => 'required|integer|min:0',
    //             'w250gm500gm' => 'required|integer|min:0',
    //             'w500gm1000gm' => 'required|integer|min:0',
    //             'w1000gm1500gm' => 'required|integer|min:0',
    //             'w1500gm2000gm' => 'required|integer|min:0',
    //             'w2000gm2500gm' => 'required|integer|min:0',
    //             'w2500gm3000gm' => 'required|integer|min:0',
    //             'w3000gm3500gm' => 'required|integer|min:0',
    //             'w3500gm4000gm' => 'required|integer|min:0',
    //             'w4000gm4500gm' => 'required|integer|min:0',
    //             'w4500gm5000gm' => 'required|integer|min:0',
    //         ]);


    //         try {
    //             // Create Commissions for each weight category
    //             $weights = [
    //                 'Up to 250 gm' => $request->w250gm,
    //                 '250gm to 500gm' => $request->w250gm500gm,
    //                 '500gm to 1kg' => $request->w500gm1000gm,
    //                 '1kg to 1.5kg' => $request->w1000gm1500gm,
    //                 '1.5kg to 2kg' => $request->w1500gm2000gm,
    //                 '2kg to 2.5kg' => $request->w2000gm2500gm,
    //                 '2.5kg to 3kg' => $request->w2500gm3000gm,
    //                 '3kg to 3.5kg' => $request->w3000gm3500gm,
    //                 '3.5kg to 4kg' => $request->w3500gm4000gm,
    //                 '4kg to 4.5kg' => $request->w4000gm4500gm,
    //                 '4.5kg to 5kg' => $request->w4500gm5000gm,
    //             ];

    //             foreach ($weights as $weight => $amount) {
    //                 Commission::create([
    //                     'weight' => $weight,
    //                     'amount' => $amount,
    //                     'membertype' => $request->query('membertype'),
    //                     'servicetype' => $request->query('serviceType'),
    //                 ]);
    //             }

    //             return redirect()->route('admin.commission.index', ['membertype' => $request->query('membertype')])->with('success', 'Commission Rates Created');
    //         } catch (\Exception $th) {
    //             return back()->with('error', $th->getMessage());
    //         }
    //     }



    //     if ($request->serviceType == 3) {

    //         $this->validate($request, [
    //             'w2000gm' => 'required|integer|min:0',
    //             'w2000gm3000gm' => 'required|integer|min:0',
    //         ]);

    //         try {
    //             $weights = [
    //                 'First 2 KG' => $request->w2000gm,
    //                 'Additional 1kg' => $request->w2000gm3000gm,
    //             ];

    //             foreach ($weights as $weight => $amount) {
    //                 Commission::create([
    //                     'weight' => $weight,
    //                     'amount' => $amount,
    //                     'membertype' => $request->query('membertype'),
    //                     'servicetype' => $request->query('serviceType'),
    //                 ]);
    //             }
    //             return redirect()->route('admin.commission.index', ['membertype' => $request->query('membertype')])->with('success', 'Commission Rates Created');
    //         } catch (\Exception $th) {
    //             return back()->with('error', $th->getMessage());
    //         }
    //     }


    //     if ($request->serviceType == 4) {

    //         $this->validate($request, [
    //             'w100gm' => 'required|integer|min:0',
    //         ]);

    //         try {
    //             $weights = [
    //                 'Upto 100 gms' => $request->w100gm,

    //             ];

    //             foreach ($weights as $weight => $amount) {
    //                 Commission::create([
    //                     'weight' => $weight,
    //                     'amount' => $amount,
    //                     'membertype' => $request->query('membertype'),
    //                     'servicetype' => $request->query('serviceType'),
    //                 ]);
    //             }
    //             return redirect()->route('admin.commission.index', ['membertype' => $request->query('membertype')])->with('success', 'Commission Rates Created');
    //         } catch (\Exception $th) {
    //             return back()->with('error', $th->getMessage());
    //         }
    //     }


    //     if ($request->serviceType == 9) {

    //         // Validate the inputs for pages
    //         $this->validate($request, [
    //             'first2pages' => 'required|integer|min:0',
    //             'additional_pages' => 'required|integer|min:0',
    //         ]);

    //         try {
    //             // Prepare the rates data based on the number of pages
    //             $rates = [
    //                 'First 2 Pages' => $request->first2pages,
    //                 'Additional Pages' => $request->additional_pages,
    //             ];

    //             // Store each rate in the database
    //             foreach ($rates as $category => $amount) {
    //                 Commission::create([
    //                     'weight' => $category,
    //                     'amount' => $amount,
    //                     'membertype' => $request->query('membertype'),
    //                     'serviceType' => $request->query('serviceType'),
    //                 ]);
    //             }

    //             // Redirect with success message
    //             return redirect()->route('admin.commission.index', ['membertype' => $request->query('membertype')])
    //                 ->with('success', 'Postal Rates Created');
    //         } catch (\Exception $th) {
    //             // Redirect back with error message if an exception occurs
    //             return back()->with('error', $th->getMessage());
    //         }
    //     }
    // }


    public function store(Request $request)
    {

        // dd($request->all());
        $this->validate($request, [
            'upto_2_lakh' => 'required',
            'between_2_to_5_lakh' => 'required',
            'between_5_to_10_lakh' => 'required',
            'between_10_to_25_lakh_and_above' => 'required',
        
        ]);
        try {
            IndiaPostCommission::create([
                'india_post_monthly_revenue' => 'Up To RS. 2,00,000/-',
                'india_post_commission' => $request->upto_2_lakh,
                'start' => 0,
                'end' => 200000,
                
            ]);

            IndiaPostCommission::create([
                'india_post_monthly_revenue' => 'RS. 2,00,001 to 5,00,000/-',
                'india_post_commission' => $request->between_2_to_5_lakh,
                'start' => 2,00,001,
                'end' => 5,00,000,
                
            ]);

            IndiaPostCommission::create([
                'india_post_monthly_revenue' => 'RS. 5,00,001 to 10,00,000/-',
                'india_post_commission' => $request->between_5_to_10_lakh,
                'start' =>  5,00,001,
                'end' => 0,00,000,
                
            ]);

            IndiaPostCommission::create([
                'india_post_monthly_revenue' => 'RS. 10,00,001 to and above',
                'india_post_commission' => $request->between_10_to_25_lakh_and_above,
                'start' => 1000001

                
            ]);
            return redirect()->route('admin.india-post-commission.index')->with('success', 'India Post Commission Created');
        } catch (\Exception $th) {
            return back()->with('error', 'Error occurred: ' . $th->getMessage());
        }
    }



    public function edit(Request $request)

    {
        $rates =  IndiaPostCommission::get();
            if ($request->isMethod('POST')) {
                $this->validate($request, [
                    'upto_2_lakh' => 'required',
                    'between_2_to_5_lakh' => 'required',
                    'between_5_to_10_lakh' => 'required',
                    'between_10_to_25_lakh_and_above' => 'required',
                
                ]);
    
                try {
                    $rates[0]->update([

                                'india_post_monthly_revenue' => 'Up To RS. 2,00,000/-',
                                'india_post_commission' => $request->upto_2_lakh,

                    ]);
                    $rates[1]->update([

                                'india_post_monthly_revenue' => 'RS. 2,00,001 to 5,00,000/-',
                                'india_post_commission' => $request->between_2_to_5_lakh,
                    ]);
                    $rates[2]->update([

                                'india_post_monthly_revenue' => 'RS. 5,00,001 to 10,00,000/-',
                                'india_post_commission' => $request->between_5_to_10_lakh,
                    ]);
                    $rates[3]->update([


                                'india_post_monthly_revenue' => 'RS. 10,00,001 to and above',
                                'india_post_commission' => $request->between_10_to_25_lakh_and_above,

                    ]);
                                    
                
                    return redirect()->back()->with('success', 'India Post Commission rates updated successfully.');
                } catch (\Exception $e) {
                    return redirect()->back()->with('error', 'Failed to update commission rates: ' . $e->getMessage());
                }
            }
    }
    // public function edit(Request $request)
    // {
    //     $title = Commission::getMemberType($request->membertype);

    //     if ($request->serviceType == 1) {
    //         $this->validate($request, [
    //             'w250gm' => 'required|integer|min:0',
    //             'w250gm500gm' => 'required|integer|min:0',
    //             'w500gm1000gm' => 'required|integer|min:0',
    //             'w1000gm1500gm' => 'required|integer|min:0',
    //             'w1500gm2000gm' => 'required|integer|min:0',
    //             'w2000gm2500gm' => 'required|integer|min:0',
    //             'w2500gm3000gm' => 'required|integer|min:0',
    //             'w3000gm3500gm' => 'required|integer|min:0',
    //             'w3500gm4000gm' => 'required|integer|min:0',
    //             'w4000gm4500gm' => 'required|integer|min:0',
    //             'w4500gm5000gm' => 'required|integer|min:0',
    //         ]);


    //         try {
    //             $membertype = $request->query('membertype');
    //             $serviceType = $request->query('serviceType');

    //             // Retrieve existing rates
    //             $speedrates = Commission::where('membertype', $membertype)
    //                 ->where('servicetype', $serviceType)
    //                 ->get();

    //             // Prepare the rate data
    //             $ratesData = [
    //                 ['weight' => 'Up to 250 gm', 'amount' => $request->input('w250gm')],
    //                 ['weight' => '250gm to 500gm', 'amount' => $request->input('w250gm500gm')],
    //                 ['weight' => '500gm to 1kg', 'amount' => $request->input('w500gm1000gm')],
    //                 ['weight' => '1kg to 1.5kg', 'amount' => $request->input('w1000gm1500gm')],
    //                 ['weight' => '1.5kg to 2kg', 'amount' => $request->input('w1500gm2000gm')],
    //                 ['weight' => '2kg to 2.5kg', 'amount' => $request->input('w2000gm2500gm')],
    //                 ['weight' => '2.5kg to 3kg', 'amount' => $request->input('w2500gm3000gm')],
    //                 ['weight' => '3kg to 3.5kg', 'amount' => $request->input('w3000gm3500gm')],
    //                 ['weight' => '3.5kg to 4kg', 'amount' => $request->input('w3500gm4000gm')],
    //                 ['weight' => '4kg to 4.5kg', 'amount' => $request->input('w4000gm4500gm')],
    //                 ['weight' => '4.5kg to 5kg', 'amount' => $request->input('w4500gm5000gm')],
    //             ];

    //             foreach ($speedrates as $index => $rate) {
    //                 $rate->update([
    //                     'weight' => $ratesData[$index]['weight'],
    //                     'amount' => $ratesData[$index]['amount'],
    //                     'membertype' => $membertype,
    //                     'servicetype' => $serviceType,
    //                 ]);
    //             }

    //             return redirect()->back()->with('success', 'Rates updated successfully.');
    //         } catch (\Exception $e) {
    //             return redirect()->back()->with('error', 'Failed to update rates: ' . $e->getMessage());
    //         }
    //     }

    //     if ($request->serviceType == 3) {
    //         if ($request->isMethod('POST')) {
    //             $this->validate($request, [
    //                 'w2000gm' => 'required|integer|min:0',
    //                 'w2000gm3000gm' => 'required|integer|min:0',
    //             ]);

    //             try {
    //                 $membertype = $request->query('membertype');

    //                 // Retrieve existing business rates for the given membertype and servicetype 3
    //                 $businessrates = Commission::where('membertype', $membertype)
    //                     ->where('servicetype', 3)
    //                     ->get();

    //                 // Prepare the new rate data
    //                 $businessRatesData = [
    //                     ['weight' => 'First 2 KG', 'amount' => $request->input('w2000gm')],
    //                     ['weight' => 'Additional 1kg', 'amount' => $request->input('w2000gm3000gm')],
    //                 ];

    //                 // Update the rates
    //                 foreach ($businessrates as $index => $rate) {
    //                     if (isset($businessRatesData[$index])) {
    //                         $rate->update([
    //                             'weight' => $businessRatesData[$index]['weight'],
    //                             'amount' => $businessRatesData[$index]['amount'],
    //                             'membertype' => $membertype,
    //                             'servicetype' => $request->query('serviceType'),
    //                         ]);
    //                     }
    //                 }

    //                 return redirect()->back()->with('success', 'Business rates updated successfully.');
    //             } catch (\Exception $e) {
    //                 return redirect()->back()->with('error', 'Failed to update business rates: ' . $e->getMessage());
    //             }
    //         }
    //     }

    //     if ($request->serviceType == 4) {
    //         if ($request->isMethod('POST')) {
    //             // Validate the request
    //             $this->validate($request, [
    //                 'w100gm' => 'required|integer|min:0',
    //             ]);

    //             try {
    //                 $membertype = $request->query('membertype');
    //                 $servicetype = $request->query('serviceType');

    //                 // Retrieve existing legal rates for the given membertype and servicetype 4
    //                 $legalrates = Commission::where('membertype', $membertype)
    //                     ->where('servicetype', 4)
    //                     ->get();

    //                 // Prepare the new rate data
    //                 $legalRatesData = [
    //                     ['weight' => 'Upto 100gms', 'amount' => $request->input('w100gm')],
    //                 ];

    //                 // Update the rates
    //                 foreach ($legalrates as $index => $rate) {
    //                     if (isset($legalRatesData[$index])) {
    //                         $rate->update([
    //                             'weight' => $legalRatesData[$index]['weight'],
    //                             'amount' => $legalRatesData[$index]['amount'],
    //                             'membertype' => $membertype,
    //                             'servicetype' => $servicetype,
    //                         ]);
    //                     }
    //                 }

    //                 return redirect()->back()->with('success', 'Legal rates updated successfully.');
    //             } catch (\Exception $e) {
    //                 return redirect()->back()->with('error', 'Failed to update legal rates: ' . $e->getMessage());
    //             }
    //         }
    //     }

    //     if ($request->serviceType == 9) {

    //         // Retrieve existing rates for the given membertype and servicetype 9
    //         $e2hrates = Commission::where('membertype', $request->membertype)
    //             ->where('servicetype', $request->serviceType)
    //             ->get();

    //         // Prepare new rate data
    //         $e2hRatesData = [
    //             ['weight' => 'First 2 Pages', 'amount' => $request->input('first2pages')],
    //             ['weight' => 'Additional Pages', 'amount' => $request->input('additional_pages')],
    //         ];
    //         // Validate input
    //         $this->validate($request, [
    //             'first2pages' => 'required|integer|min:0',
    //             'additional_pages' => 'required|integer|min:0',
    //         ]);

    //         try {
    //             $membertype = $request->query('membertype');

    //             // Update the rates
    //             foreach ($e2hrates as $index => $rate) {
    //                 if (isset($e2hRatesData[$index])) {
    //                     $rate->update([
    //                         'weight' => $e2hRatesData[$index]['weight'],
    //                         'amount' => $e2hRatesData[$index]['amount'],
    //                         'membertype' => $membertype,
    //                         'servicetype' => $request->query('serviceType'),
    //                     ]);
    //                 }
    //             }

    //             return redirect()->back()->with('success', 'Commission rates updated successfully.');
    //         } catch (\Exception $e) {
    //             return redirect()->back()->with('error', 'Failed to update commission rates: ' . $e->getMessage());
    //         }
    //     }
    // }
}
