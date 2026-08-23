<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\GotogoPostalRates;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class GotogoPostalRatesController extends Controller
{
    public function index(Request $request)
    {
        //  GotogoPostalRates::query()->truncate();

        $title = GotogoPostalRates::getServiceType($request->type);

        $rates = GotogoPostalRates::where('type', $request->type)->get();

        // return $rates;
        return view('admin.gotogo-postal-rates.index', ['rates' => $rates, 'title' => $title]);
    }

    public function create(Request $request)
    {

        if (count(GotogoPostalRates::where('type', $request->type)->get()) == 0) {
            return view('admin.gotogo-postal-rates.create');
        } else {
            return redirect()->route('admin.gotogo-postal-rates.index');
        }
    }

    public function store(Request $request)
    {
        if ($request->type == 1) {
            // Define weight ranges and correct prefixes based on the dd() output
            $weightRanges = [
                'Upto 250gm' => 'w0to250gm',
                'Additional 250gm to 500gm' => 'w250gmto500gm',
                'Additional 500gm to 1kg' => 'w500gmto1',
                'Additional 1kg to 1.5kg' => 'w1to1_5',
                'Additional 1.5kg to 2kg' => 'w1_5to2',
                'Additional 2kg to 2.5kg' => 'w2to2_5',
                'Additional 2.5kg to 3kg' => 'w2_5to3',
                'Additional 3kg to 3.5kg' => 'w3to3_5',
                'Additional 3.5kg to 4kg' => 'w3_5to4',
                'Additional 4kg to 4.5kg' => 'w4to4_5',
                'Additional 4.5kg to 5kg' => 'w4_5to5',
            ];

            // Define validation rules dynamically
            $rules = [];
            foreach ($weightRanges as $weightLabel => $prefix) {
                $rules["{$prefix}local"] = 'required|numeric';
                $rules["{$prefix}200km"] = 'required|numeric';
                $rules["{$prefix}201to1000km"] = 'required|numeric';
                $rules["{$prefix}1001to2000km"] = 'required|numeric';
                $rules["{$prefix}Above2000km"] = 'required|numeric';
            }

            // Validate the request input
            $validator = Validator::make($request->all(), $rules);

            // If validation fails
            if ($validator->fails()) {
                dd($validator->errors()); // Dump the validation errors to debug
                return back()->withErrors($validator)->withInput();
            }

            try {
                // Insert data dynamically into the GotogoPostalRates table
                foreach ($weightRanges as $weightLabel => $prefix) {
                    GotogoPostalRates::create([
                        'type' => $request->type,
                        'weight' => $weightLabel,
                        'Local' => $request->input("{$prefix}local"),
                        'upto_200_kms' => $request->input("{$prefix}200km"),
                        '201_to_1000_kms' => $request->input("{$prefix}201to1000km"),
                        '1001_to_2000_kms' => $request->input("{$prefix}1001to2000km"),
                        'above_2000_kms' => $request->input("{$prefix}Above2000km"),
                    ]);
                }
                return redirect()->route('admin.gotogo-postal-rates.index', ['type' => $request->type])
                    ->with('success', 'Postal Rates Created');
            } catch (\Exception $e) {
                return back()->with('error', 'Error adding postal rates: ' . $e->getMessage());
            }
        }



        if ($request->type == 3) {

            $rules = [
                'w2000gmLocal' => 'required',
                'w2000gm200km' => 'required',
                'w2000gm201to1000km' => 'required',
                'w2000gm1001to2000km' => 'required',
                'w2000gmAbove2000km' => 'required',

                'w3000gmLocal' => 'required',
                'w3000gm200km' => 'required',
                'w3000gm201to1000km' => 'required',
                'w3000gm1001to2000km' => 'required',
                'w3000gmAbove2000km' => 'required',
            ];

            $this->validate($request, $rules);

            try {
                // Create Postal Rates for First 2 kg
                GotogoPostalRates::create([
                    'weight' => 'First 2 kg',
                    'type' => $request->type,
                    'Local' => $request->w2000gmLocal,
                    'upto_200_kms' => $request->w2000gm200km,
                    '201_to_1000_kms' => $request->w2000gm201to1000km,
                    '1001_to_2000_kms' => $request->w2000gm1001to2000km,
                    'above_2000_kms' => $request->w2000gmAbove2000km,
                ]);

                // Create Postal Rates for Additional 1kg
                GotogoPostalRates::create([
                    'weight' => 'Additional 1kg',
                    'type' => $request->type,
                    'Local' => $request->w3000gmLocal,
                    'upto_200_kms' => $request->w3000gm200km,
                    '201_to_1000_kms' => $request->w3000gm201to1000km,
                    '1001_to_2000_kms' => $request->w3000gm1001to2000km,
                    'above_2000_kms' => $request->w3000gmAbove2000km,
                ]);

                return redirect()->route('admin.gotogo-postal-rates.index', ['type' => $request->type])
                    ->with('success', 'Postal Rates Created');
            } catch (\Exception $th) {
                return back()->with('error', $th->getMessage());
            }
        }


        if ($request->type == 4) {

            $rules = [
                'w100gmLocal' => 'required',
                'w100gm200km' => 'required',
                'w100gm201to1000km' => 'required',
                'w100gm1001to2000km' => 'required',
                'w100gmAbove2000km' => 'required',
            ];

            $this->validate($request, $rules);

            try {
                // Create Postal Rates for Upto 100gms
                GotogoPostalRates::create([
                    'weight' => 'Upto 100gms',
                    'type' => $request->type,
                    'Local' => $request->w100gmLocal,
                    'upto_200_kms' => $request->w100gm200km,
                    '201_to_1000_kms' => $request->w100gm201to1000km,
                    '1001_to_2000_kms' => $request->w100gm1001to2000km,
                    'above_2000_kms' => $request->w100gmAbove2000km,
                ]);

                return redirect()->route('admin.gotogo-postal-rates.index', ['type' => $request->type])
                    ->with('success', 'Postal Rates Created');
            } catch (\Exception $th) {
                return back()->with('error', $th->getMessage());
            }
        }

        if ($request->type == 9) {

            $rules = [
                // Validate First 2 Pages category
                'first2pages_local' => 'required',
                'first2pages_200km' => 'required',
                'first2pages_201to1000km' => 'required',
                'first2pages_1001to2000km' => 'required',
                'first2pages_above2000km' => 'required',

                // Validate Each Additional Page for 3 & Above category
                'additional_page_local' => 'required',
                'additional_page_200km' => 'required',
                'additional_page_201to1000km' => 'required',
                'additional_page_1001to2000km' => 'required',
                'additional_page_above2000km' => 'required',
            ];

            $this->validate($request, $rules);

            try {
                // Create Postal Rates for First 2 Pages
                GotogoPostalRates::create([
                    'weight' => 'First 5 Pages',
                    'type' => $request->type,
                    'Local' => $request->first2pages_local,
                    'upto_200_kms' => $request->first2pages_200km,
                    '201_to_1000_kms' => $request->first2pages_201to1000km,
                    '1001_to_2000_kms' => $request->first2pages_1001to2000km,
                    'above_2000_kms' => $request->first2pages_above2000km,
                ]);

                // Create Postal Rates for Each Additional Page for 3 & Above
                GotogoPostalRates::create([
                    'weight' => 'Each Additional Page for 6 & Above',
                    'type' => $request->type,
                    'Local' => $request->additional_page_local,
                    'upto_200_kms' => $request->additional_page_200km,
                    '201_to_1000_kms' => $request->additional_page_201to1000km,
                    '1001_to_2000_kms' => $request->additional_page_1001to2000km,
                    'above_2000_kms' => $request->additional_page_above2000km,
                ]);

                return redirect()->route('admin.gotogo-postal-rates.index', ['type' => $request->type])
                    ->with('success', 'Postal Rates Created');
            } catch (\Exception $th) {
                return back()->with('error', $th->getMessage());
            }
        }
    }


    public function edit(Request $request)
    {
        // Fetch postal rates based on the 'type' passed in the request
        $rates = GotogoPostalRates::where('type', $request->type)->get();

        // Define weight ranges and form field prefixes based on 'type'
        $weightRanges = [];
        $rules = [];

        // Handle type 1
        if ($request->type == 1) {
            $weightRanges = [
                'Upto 250gm' => 'wUpto250gm',
                'Additional 250gm to 500gm' => 'wAdditional250gmto500gm',
                'Additional 500gm to 1kg' => 'wAdditional500gmto1',
                'Additional 1kg to 1.5kg' => 'wAdditional1to1_5',
                'Additional 1.5kg to 2kg' => 'wAdditional1_5to2',
                'Additional 2kg to 2.5kg' => 'wAdditional2to2_5',
                'Additional 2.5kg to 3kg' => 'wAdditional2_5to3',
                'Additional 3kg to 3.5kg' => 'wAdditional3to3_5',
                'Additional 3.5kg to 4kg' => 'wAdditional3_5to4',
                'Additional 4kg to 4.5kg' => 'wAdditional4to4_5',
                'Additional 4.5kg to 5kg' => 'wAdditional4_5to5',
            ];

            // Define validation rules dynamically
            $rules = [];
            foreach ($weightRanges as $weightLabel => $prefix) {
                $rules["{$prefix}local"] = 'required|numeric';
                $rules["{$prefix}200km"] = 'required|numeric';
                $rules["{$prefix}201to1000km"] = 'required|numeric';
                $rules["{$prefix}1001to2000km"] = 'required|numeric';
                $rules["{$prefix}Above2000km"] = 'required|numeric';
            }

            // Handle POST request for type 1
            if ($request->isMethod('POST')) {
                // Create a validator instance for type 1
                $validator = Validator::make($request->all(), $rules);

                // If validation fails
                if ($validator->fails()) {
                    return back()->withErrors($validator)->withInput();
                }

                // Fetch rates from the database for type 1
                $rates = GotogoPostalRates::where('type', 1)->get()->keyBy('weight');

                try {
                    foreach ($weightRanges as $weightLabel => $prefix) {
                        if (!isset($rates[$weightLabel])) {
                            continue; // Skip if weight category is missing
                        }

                        $rate = $rates[$weightLabel];

                        // Update the rate for each weight range
                        $rate->update([
                            'Local' => $request->input("{$prefix}local"),
                            'upto_200_kms' => $request->input("{$prefix}200km"),
                            '201_to_1000_kms' => $request->input("{$prefix}201to1000km"),
                            '1001_to_2000_kms' => $request->input("{$prefix}1001to2000km"),
                            'above_2000_kms' => $request->input("{$prefix}Above2000km"),
                        ]);
                    }

                    return redirect()->route('admin.gotogo-postal-rates.index', ['type' => $request->type])
                        ->with('success', 'Postal rates updated successfully!');
                } catch (\Exception $e) {
                    return back()->with('error', $e->getMessage())->withInput();
                }
            }
        } elseif ($request->type == 3) {
            
            $weightRanges = [
                'First 1 kg' => 'w2000gm',
                'Additional 500gm' => 'w3000gm',
            ];

            $rules = [
                'w2000gm_local' => 'required|numeric',
                'w2000gm_200km' => 'required|numeric',
                'w2000gm_201to1000km' => 'required|numeric',
                'w2000gm_1001to2000km' => 'required|numeric',
                'w2000gm_above2000km' => 'required|numeric',
                'w3000gm_local' => 'required|numeric',
                'w3000gm_200km' => 'required|numeric',
                'w3000gm_201to1000km' => 'required|numeric',
                'w3000gm_1001to2000km' => 'required|numeric',
                'w3000gm_above2000km' => 'required|numeric',
            ];
          
            // Handle POST request for type 3
            if ($request->isMethod('POST')) {
                // Create a validator instance for type 3
                $validator = Validator::make($request->all(), $rules);

                // If validation fails
                if ($validator->fails()) {
                    dd($validator->errors()->all());
                    return back()->withErrors($validator)->withInput();
                }

                // Update rates for type 3
                try {
                    foreach ($rates as $index => $rate) {
                        // Dynamically get the weight label based on the index
                        
                        $weightLabel = array_keys($weightRanges)[$index];
                        $prefix = $weightRanges[$weightLabel];
                        // Correctly update the rate fields with the dynamic form values
                        $rate->update([
                            'weight' => $weightLabel,
                            'Local' => $request->input("{$prefix}_local"),
                            'upto_200_kms' => $request->input("{$prefix}_200km"),
                            '201_to_1000_kms' => $request->input("{$prefix}_201to1000km"),
                            '1001_to_2000_kms' => $request->input("{$prefix}_1001to2000km"),
                            'above_2000_kms' => $request->input("{$prefix}_above2000km"),
                        ]);
                    }

                    return redirect()->route('admin.gotogo-postal-rates.index', ['type' => $request->type])
                        ->with('success', 'Postal rates updated successfully!');
                } catch (\Exception $e) {
                    return back()->with('error', $e->getMessage())->withInput();
                }
            }
            // Handle type 4
        } // Handling Type 4 - Upto 100gms
        if ($request->type == 4) {
            // Validation rules
            $rules = [
                'w100gm_local' => 'required|numeric',
                'w100gm_200km' => 'required|numeric',
                'w100gm_1000km' => 'required|numeric',
                'w100gm_2000km' => 'required|numeric',
                'w100gm_above2000' => 'required|numeric',
            ];

            // Handle POST request for type 4
            if ($request->isMethod('POST')) {
                // Create a validator instance for type 4
                $validator = Validator::make($request->all(), $rules);

                // If validation fails, dd to inspect the errors
                if ($validator->fails()) {
                    dd($validator->errors()->all(), $request->all());
                    return back()->withErrors($validator)->withInput();
                }

                // Update rates for type 4
                try {
                    // Assuming $rates is fetched properly
                    $rate = $rates[0];  // Get the rate data

                    // Update the rate with new values
                    $rate->update([
                        'weight' => 'Upto 100gms',
                        'Local' => $request->input('w100gm_local'),
                        'upto_200_kms' => $request->input('w100gm_200km'),
                        '201_to_1000_kms' => $request->input('w100gm_1000km'),
                        '1001_to_2000_kms' => $request->input('w100gm_2000km'),
                        'above_2000_kms' => $request->input('w100gm_above2000'),
                    ]);

                    return redirect()->route('admin.gotogo-postal-rates.index', ['type' => $request->type])
                        ->with('success', 'Postal rates updated successfully!');
                } catch (\Exception $e) {
                    return back()->with('error', $e->getMessage())->withInput();
                }
            }
        }

        // Handling Type 9 - First 2 Pages and Additional Pages
        elseif ($request->type == 9) {
            // Validation rules for type 9
            $rules = [
                'first2pages_local' => 'required|numeric',
                'first2pages_200km' => 'required|numeric',
                'first2pages_1000km' => 'required|numeric',
                'first2pages_2000km' => 'required|numeric',
                'first2pages_above2000' => 'required|numeric',
                'additional_page_local' => 'required|numeric',
                'additional_page_200km' => 'required|numeric',
                'additional_page_1000km' => 'required|numeric',
                'additional_page_2000km' => 'required|numeric',
                'additional_page_above2000' => 'required|numeric',
            ];

            // Handle POST request for type 9
            if ($request->isMethod('POST')) {
                // Create a validator instance for type 9
                $validator = Validator::make($request->all(), $rules);

                // If validation fails, dd to inspect the errors
                if ($validator->fails()) {
                    dd($validator->errors()->all(), $request->all());
                    return back()->withErrors($validator)->withInput();
                }

                // Update rates for type 9
                try {
                    // Assuming $rates is fetched properly
                    $first2pages = $rates[0];
                    $additionalPages = $rates[1];

                    // Update the rates for first 2 pages
                    $first2pages->update([
                        'weight' => 'First 5 Pages',
                        'Local' => $request->input('first2pages_local'),
                        'upto_200_kms' => $request->input('first2pages_200km'),
                        '201_to_1000_kms' => $request->input('first2pages_1000km'),
                        '1001_to_2000_kms' => $request->input('first2pages_2000km'),
                        'above_2000_kms' => $request->input('first2pages_above2000'),
                    ]);

                    // Update the rates for additional pages
                    $additionalPages->update([
                        'weight' => 'Each Additional Page for 6 & Above',
                        'Local' => $request->input('additional_page_local'),
                        'upto_200_kms' => $request->input('additional_page_200km'),
                        '201_to_1000_kms' => $request->input('additional_page_1000km'),
                        '1001_to_2000_kms' => $request->input('additional_page_2000km'),
                        'above_2000_kms' => $request->input('additional_page_above2000'),
                    ]);

                    return redirect()->route('admin.gotogo-postal-rates.index', ['type' => $request->type])
                        ->with('success', 'Postal rates updated successfully!');
                } catch (\Exception $e) {
                    return back()->with('error', $e->getMessage())->withInput();
                }
            }
        }


        //  return $rates;
        // Return the view with the rates
        return view('admin.gotogo-postal-rates.edit', compact('rates'));
    }
}
