<?php



namespace App\Http\Controllers\admin;



use App\Http\Controllers\Controller;

use App\Models\IndiaPostBRRate;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;





class IndiaPostBRRatesController extends Controller

{

    public function index(Request $request)
    {
        $rates = IndiaPostBRRate::get();
        return view('admin.india-post-br-rate.index', ['rates' => $rates]);
    }

    public function create(Request $request)

    {
        if (count(IndiaPostBRRate::get()) == 0) {

            return view('admin.india-post-br-rate.create');
        } else {

            return redirect()->route('admin.india-post-br-rate.index');
        }
    }


    public function store(Request $request)
    {
        $this->validate($request, [
            // Validate the 'Local' distance category
            'local_upto_2kg' => 'required|integer|min:0',
            'local_addl_upto_5kg' => 'required|integer|min:0',
            'local_above_upto_5kg' => 'required|integer|min:0',

            // Validate the 'Within State' distance category
            'within_upto_2kg' => 'required|integer|min:0',
            'within_addl_upto_5kg' => 'required|integer|min:0',
            'within_above_upto_5kg' => 'required|integer|min:0',

            // Validate the 'Neighbouring State' distance category
            'neighbouring_upto_2kg' => 'required|integer|min:0',
            'neighbouring_addl_upto_5kg' => 'required|integer|min:0',
            'neighbouring_above_upto_5kg' => 'required|integer|min:0',

            // Validate the 'Other State' distance category
            'other_upto_2kg' => 'required|integer|min:0',
            'other_addl_upto_5kg' => 'required|integer|min:0',
            'other_above_upto_5kg' => 'required|integer|min:0',

            // Validate the 'Between Metro and State Capitals' distance category
            'between_capital_upto_2kg' => 'required|integer|min:0',
            'between_capital_addl_upto_5kg' => 'required|integer|min:0',
            'between_capital_above_upto_5kg' => 'required|integer|min:0',

            // Validate the 'NCR-Delhi/ Ghaziabad/ Noida/ Greater Noida/ Faridabad' distance category
            'ncr_delhi_upto_2kg' => 'required|integer|min:0',
            'ncr_delhi_addl_upto_5kg' => 'required|integer|min:0',
            'ncr_delhi_above_upto_5kg' => 'required|integer|min:0',
        ]);
        try {
            IndiaPostBRRate::create([
                'distance' => 'Local',
                'upto_2kg' => $request->local_upto_2kg,
                'addl_upto_5kg' => $request->local_addl_upto_5kg,
                'above_upto_5kg' => $request->local_above_upto_5kg,
            ]);

            IndiaPostBRRate::create([
                'distance' => 'Within State',
                'upto_2kg' => $request->within_upto_2kg,
                'addl_upto_5kg' => $request->within_addl_upto_5kg,
                'above_upto_5kg' => $request->within_above_upto_5kg,
            ]);

            IndiaPostBRRate::create([
                'distance' => 'Neighbouring State',
                'upto_2kg' => $request->neighbouring_upto_2kg,
                'addl_upto_5kg' => $request->neighbouring_addl_upto_5kg,
                'above_upto_5kg' => $request->neighbouring_above_upto_5kg,
            ]);

            IndiaPostBRRate::create([
                'distance' => 'Other State',
                'upto_2kg' => $request->other_upto_2kg,
                'addl_upto_5kg' => $request->other_addl_upto_5kg,
                'above_upto_5kg' => $request->other_above_upto_5kg,
            ]);

            IndiaPostBRRate::create([
                'distance' => 'Between Metro and State Capitals',
                'upto_2kg' => $request->between_capital_upto_2kg,
                'addl_upto_5kg' => $request->between_capital_addl_upto_5kg,
                'above_upto_5kg' => $request->between_capital_above_upto_5kg,
            ]);

            IndiaPostBRRate::create([
                'distance' => 'NCR-Delhi/ Ghaziabad/ Noida/ Greater Noida/ Faridabad',
                'upto_2kg' => $request->ncr_delhi_upto_2kg,
                'addl_upto_5kg' => $request->ncr_delhi_addl_upto_5kg,
                'above_upto_5kg' => $request->ncr_delhi_above_upto_5kg,
            ]);

            return redirect()->route('admin.india-post-br-rate.index')->with('success', 'India Post Rates Created');
        } catch (\Exception $th) {
            return back()->with('error', 'Error occurred: ' . $th->getMessage());
        }
    }

    //  public function edit(Request $request)

    // {
    //     $rates =  IndiaPostBRRate::get();
    //         if ($request->isMethod('POST')) {
    //                 $this->validate($request, [
    //                     // Validate the 'Local' distance category
    //                     'local_upto_2kg' => 'required|integer|min:0',
    //                     'local_addl_upto_5kg' => 'required|integer|min:0',
    //                     'local_above_upto_5kg' => 'required|integer|min:0',
            
    //                     // Validate the 'Within State' distance category
    //                     'within_upto_2kg' => 'required|integer|min:0',
    //                     'within_addl_upto_5kg' => 'required|integer|min:0',
    //                     'within_above_upto_5kg' => 'required|integer|min:0',
            
    //                     // Validate the 'Neighbouring State' distance category
    //                     'neighbouring_upto_2kg' => 'required|integer|min:0',
    //                     'neighbouring_addl_upto_5kg' => 'required|integer|min:0',
    //                     'neighbouring_above_upto_5kg' => 'required|integer|min:0',
            
    //                     // Validate the 'Other State' distance category
    //                     'other_upto_2kg' => 'required|integer|min:0',
    //                     'other_addl_upto_5kg' => 'required|integer|min:0',
    //                     'other_above_upto_5kg' => 'required|integer|min:0',
            
    //                     // Validate the 'Between Metro and State Capitals' distance category
    //                     'between_capital_upto_2kg' => 'required|integer|min:0',
    //                     'between_capital_addl_upto_5kg' => 'required|integer|min:0',
    //                     'between_capital_above_upto_5kg' => 'required|integer|min:0',
            
    //                     // Validate the 'NCR-Delhi/ Ghaziabad/ Noida/ Greater Noida/ Faridabad' distance category
    //                     'ncr_delhi_upto_2kg' => 'required|integer|min:0',
    //                     'ncr_delhi_addl_upto_5kg' => 'required|integer|min:0',
    //                     'ncr_delhi_above_upto_5kg' => 'required|integer|min:0',
    //                 ]);
            

    //             try {
    //                 $rates[0]->update([

    //                     'distance' => 'Local',
    //                     'upto_2kg' => $request->local_upto_2kg,
    //                     'addl_upto_5kg' => $request->local_addl_upto_5kg,
    //                     'above_upto_5kg' => $request->local_above_upto_5kg,

    //                 ]);
    //                 $rates[1]->update([

    //                     'distance' => 'Within State',
    //                     'upto_2kg' => $request->within_upto_2kg,
    //                     'addl_upto_5kg' => $request->within_addl_upto_5kg,
    //                     'above_upto_5kg' => $request->within_above_upto_5kg,

    //                 ]);
    //                 $rates[2]->update([

    //                         'distance' => 'Neighbouring State',
    //                         'upto_2kg' => $request->neighbouring_upto_2kg,
    //                         'addl_upto_5kg' => $request->neighbouring_addl_upto_5kg,
    //                         'above_upto_5kg' => $request->neighbouring_above_upto_5kg,
    //                 ]);
    //                 $rates[3]->update([


    //                         'distance' => 'Other State',
    //                         'upto_2kg' => $request->other_upto_2kg,
    //                         'addl_upto_5kg' => $request->other_addl_upto_5kg,
    //                         'above_upto_5kg' => $request->other_above_upto_5kg,

    //                 ]);
                                       
    //                 $rates[4]->update([
    //                             'distance' => 'Between Metro and State Capitals',
    //                             'upto_2kg' => $request->between_capital_upto_2kg,
    //                             'addl_upto_5kg' => $request->between_capital_addl_upto_5kg,
    //                             'above_upto_5kg' => $request->between_capital_above_upto_5kg,
                        
    //                 ]);
    //                 $rates[5]->update([
    //                          'distance' => 'NCR-Delhi/ Ghaziabad/ Noida/ Greater Noida/ Faridabad',
    //                         'upto_2kg' => $request->ncr_delhi_upto_2kg,
    //                         'addl_upto_5kg' => $request->ncr_delhi_addl_upto_5kg,
    //                         'above_upto_5kg' => $request->ncr_delhi_above_upto_5kg,
                                                
    //                 ]);
                   

    //                 return redirect()->route('admin.india-post-br-rate.index')->with('success', 'India Post updated successfully!');
    //             } catch (\Exception $th) {

    //                 return back()->with('error', $th->getMessage())->withInput();
    //             }
    //         }

    //     return view('admin.india-post-br-rate.edit', compact('rates'));

    // }

   public function edit(Request $request)
{
    $rates = IndiaPostBRRate::all();

    if ($request->isMethod('POST')) {
        try {
            $weights = collect($request->all())->filter(function ($value, $key) {
                return Str::startsWith($key, 'weight_');
            });

            foreach ($weights as $key => $weight) {
                $index = Str::after($key, 'weight_');

                $validated = $request->validate([
                    "id_$index" => 'required|integer|exists:india_post_b_r_rates,id',
                    "weight_$index" => 'required|string',
                    "local_$index" => 'required|numeric',
                    "upto_200_km_$index" => 'required|numeric',
                    "201_to_1000_km_$index" => 'required|numeric',
                    "1001_to_2000_km_$index" => 'required|numeric',
                    "above_2000_km_$index" => 'required|numeric',
                ]);

                // Get record by ID
                $model = IndiaPostBRRate::findOrFail($request->input("id_$index"));

                // Update fields
                $model->update([
                    'weight' => $request->input("weight_$index"),
                    'local' => $request->input("local_$index"),
                    'upto_200_km' => $request->input("upto_200_km_$index"),
                    '201_to_1000_km' => $request->input("201_to_1000_km_$index"),
                    '1001_to_2000_km' => $request->input("1001_to_2000_km_$index"),
                    'above_2000_km' => $request->input("above_2000_km_$index"),
                ]);
            }

            return redirect()->route('admin.india-post-br-rate.index')
                ->with('success', 'India Post BR Rates updated successfully!');
        } catch (\Exception $e) {
            \Log::error("Error in updating rates", [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return back()->with('error', 'Error: ' . $e->getMessage())->withInput();
        }
    }

    return view('admin.india-post-br-rate.edits', compact('rates'));
}


}
