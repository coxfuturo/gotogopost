<?php



namespace App\Http\Controllers\admin;



use App\Http\Controllers\Controller;

use App\Models\PostalRates;

use App\Models\Franchise;

use App\Models\CMS;
use App\Models\PPH;

use App\Models\FranchiseBarcodeSeries;

use App\Models\CMSBarcodeSeries;
use App\Models\PPHBarcodeSeries;

use Illuminate\Http\Request;

use DB;





class BarcodeUploadController extends Controller

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

        // PostalRates::query()->truncate();

        $rates = PostalRates::all();

        $franchise = Franchise::where('status', 1)->get();

        $cms = CMS::where('status', 1)->get();
        $pph = PPH::where('status', 1)->get();

        $serviceType = [

            'gotoSpeed' => \App\Models\Admin::GOTOGO_POST_SPEED,

            'gotoBusiness' => \App\Models\Admin::GOTOGO_POST_BUSINESS,

            'gotoRegistered' => \App\Models\Admin::GOTOGO_POST_REGISTERED,

            'IPSpeed' => \App\Models\Admin::INDIA_POST_SPEED,

            'IPBusiness' => \App\Models\Admin::INDIA_POST_BUSINESS,

        ];

        return view('admin.barcode-upload.index', ['rates' => $rates, 'franchise' => $franchise, 'serviceType' => $serviceType, 'cms' => $cms, 'pph' => $pph]);
    }



    public function create()

    {

        return view('admin.postal-rates.create');
    }





    public function truncatetable()

    {

        try {





            return redirect()->route('admin.postal-rates.index')->with('success', 'Table truncated successfully.');
        } catch (\Exception $e) {

            return back()->with('error', $e->getMessage());
        }
    }



    public function franchiseBarcodeStore(Request $request)

    {





        // return FranchiseBarcodeSeries::whereIn('id',['10','11','12','13'])->delete();



        // return  DB::select("DESCRIBE franchise_barcode_series");



        // return FranchiseBarcodeSeries::all();





        // $data = FranchiseBarcodeSeries::findOrFail(14);



        // $data->last_parcel_code_issued_gotoRegistered = null;

        // $data->last_parcel_code_issued_gotoBusiness = null;



        // $data->save();

        // return 'ddd';

        // Validate the request

        $this->validate($request, [

            'franchise_id' => 'required',

            'seriesAmount' => 'required',

            'service_type' => 'required',

        ]);





        $franchise_id = $request->franchise_id;

        $seriesAmount = $request->seriesAmount;

        $service_type = $request->service_type;

        $item = $request->item;



        try {

            $range_start_column = "{$item}_barcode_range_start_{$service_type}";

            $range_end_column = "{$item}_barcode_range_end_{$service_type}";

            $last_code_issued_column = "last_{$item}_code_issued_{$service_type}";



            // Get the latest end range for the specified range_type and service_type

            $latestEndRange = FranchiseBarcodeSeries::max($range_end_column);

            $newRangeStart = $latestEndRange ? $latestEndRange + 1 : 1;

            $newRangeEnd = $newRangeStart + $seriesAmount - 1;



            // Find existing franchise record

            $existingFranchise = FranchiseBarcodeSeries::where('franchise_id', $franchise_id)->first();



            if ($existingFranchise) {

                // Update the existing franchise barcode series

                $existingFranchise->{$range_start_column} = $newRangeStart;

                $existingFranchise->{$range_end_column} = $newRangeEnd;

                $existingFranchise->{$last_code_issued_column} = null; // Assuming this needs to be reset

                $existingFranchise->save();
            } else {

                // Create a new franchise barcode series

                FranchiseBarcodeSeries::create([

                    'franchise_id' => $franchise_id,

                    $range_start_column => $newRangeStart,

                    $range_end_column => $newRangeEnd,

                    $last_code_issued_column => null,

                ]);
            }



            return redirect()->back()->with('success', 'Barcode range assigned successfully.');
        } catch (\Exception $th) {

            return back()->with('error', $th->getMessage());
        }
    }





    public function cmsBarcodeStore(Request $request)

    {

        $this->validate($request, [

            'cms_id' => 'required',

            'seriesAmount' => 'required',

            'service_type' => 'required',

        ]);


        $cms_id = $request->cms_id;

        $seriesAmount = $request->seriesAmount;

        $service_type = $request->service_type;

        $item = 'bag';



        try {

            $range_start_column = "{$item}_barcode_range_start_{$service_type}";

            $range_end_column = "{$item}_barcode_range_end_{$service_type}";

            $last_code_issued_column = "last_{$item}_code_issued_{$service_type}";



            // Get the latest end range for the specified range_type and service_type

            $latestEndRange = CMSBarcodeSeries::max($range_end_column);

            $newRangeStart = $latestEndRange ? $latestEndRange + 1 : 1;

            $newRangeEnd = $newRangeStart + $seriesAmount - 1;



            // Find existing franchise record

            $existingFranchise = CMSBarcodeSeries::where('cms_id', $cms_id)->first();



            if ($existingFranchise) {

                // Update the existing franchise barcode series

                $existingFranchise->{$range_start_column} = $newRangeStart;

                $existingFranchise->{$range_end_column} = $newRangeEnd;

                $existingFranchise->{$last_code_issued_column} = null; // Assuming this needs to be reset

                $existingFranchise->save();
            } else {

                // Create a new franchise barcode series

                CMSBarcodeSeries::create([

                    'cms_id' => $cms_id,

                    $range_start_column => $newRangeStart,

                    $range_end_column => $newRangeEnd,

                    $last_code_issued_column => null,

                ]);
            }



            return redirect()->back()->with('success', 'Barcode range assigned successfully.');
        } catch (\Exception $th) {

            return back()->with('error', $th->getMessage());
        }
    }




    public function pphBarcodeStore(Request $request)

    {

        $this->validate($request, [

            'pph_id' => 'required',

            'seriesAmount' => 'required',

            'service_type' => 'required',

        ]);


        $pph_id = $request->pph_id;

        $seriesAmount = $request->seriesAmount;

        $service_type = $request->service_type;

        $item = 'bag';



        try {

            $range_start_column = "{$item}_barcode_range_start_{$service_type}";

            $range_end_column = "{$item}_barcode_range_end_{$service_type}";

            $last_code_issued_column = "last_{$item}_code_issued_{$service_type}";



            // Get the latest end range for the specified range_type and service_type

            $latestEndRange = PPHBarcodeSeries::max($range_end_column);

            $newRangeStart = $latestEndRange ? $latestEndRange + 1 : 1;

            $newRangeEnd = $newRangeStart + $seriesAmount - 1;



            // Find existing franchise record

            $existingFranchise = PPHBarcodeSeries::where('pph_id', $pph_id)->first();

            if ($existingFranchise) {

                // Update the existing franchise barcode series

                $existingFranchise->{$range_start_column} = $newRangeStart;

                $existingFranchise->{$range_end_column} = $newRangeEnd;

                $existingFranchise->{$last_code_issued_column} = null; // Assuming this needs to be reset

                $existingFranchise->save();
            } else {

                // Create a new franchise barcode series

                PPHBarcodeSeries::create([

                    'pph_id' => $pph_id,
                    $range_start_column => $newRangeStart,
                    $range_end_column => $newRangeEnd,
                    $last_code_issued_column => null,

                ]);
            }

            return redirect()->back()->with('success', 'Barcode range assigned successfully.');
        } catch (\Exception $th) {

            return back()->with('error', $th->getMessage());
        }
    }








    public function edit(Request $request)

    {



        $rates = PostalRates::all();

        if ($request->isMethod('POST')) {

            $this->validate($request, [

                // Validate 50gm category

                'p50gmlocal' => 'required|integer|min:0',

                'p50gm200km' => 'required|integer|min:0',

                'p50gm201to1000km' => 'required|integer|min:0',

                'p50gm1001to2000km' => 'required|integer|min:0',

                'p50gmAbove2000km' => 'required|integer|min:0',



                // Validate 51gm to 200gm category

                'p51gmTo200gmlocal' => 'required|integer|min:0',

                'p51gmTo200gm200km' => 'required|integer|min:0',

                'p51gmTo200gm201to1000km' => 'required|integer|min:0',

                'p51gmTo200gm1001to2000km' => 'required|integer|min:0',

                'p51gmTo200gmAbove2000km' => 'required|integer|min:0',



                // Validate 201gm to 500gm category

                'p201gmTo500gmlocal' => 'required|integer|min:0',

                'p201gmTo500gm200km' => 'required|integer|min:0',

                'p201gmTo500gm201to1000km' => 'required|integer|min:0',

                'p201gmTo500gm1001to2000km' => 'required|integer|min:0',

                'p201gmTo500gmAbove2000km' => 'required|integer|min:0',



                // Validate additional 500gm category

                'additional500gmlocal' => 'required|integer|min:0',

                'additional500gm200km' => 'required|integer|min:0',

                'additional500gm201to1000km' => 'required|integer|min:0',

                'additional500gm1001to2000km' => 'required|integer|min:0',

                'additional500gmAbove2000km' => 'required|integer|min:0',

            ]);





            try {

                // Get all existing postal rates





                // Directly update each record without a loop

                $rates[0]->update([

                    'weight' => 'Up to 50 gm',

                    'Local' => $request->p50gmlocal,

                    'upto_200_kms' => $request->p50gm200km,

                    '201_to_1000_kms' => $request->p50gm201to1000km,

                    '1001_to_2000_kms' => $request->p50gm1001to2000km,

                    'above_2000_kms' => $request->p50gmAbove2000km,

                ]);



                $rates[1]->update([

                    'weight' => '51 to 200 gm',

                    'Local' => $request->p51gmTo200gmlocal,

                    'upto_200_kms' => $request->p51gmTo200gm200km,

                    '201_to_1000_kms' => $request->p51gmTo200gm201to1000km,

                    '1001_to_2000_kms' => $request->p51gmTo200gm1001to2000km,

                    'above_2000_kms' => $request->p51gmTo200gmAbove2000km,

                ]);



                $rates[2]->update([

                    'weight' => '201 to 500 gm',

                    'Local' => $request->p201gmTo500gmlocal,

                    'upto_200_kms' => $request->p201gmTo500gm200km,

                    '201_to_1000_kms' => $request->p201gmTo500gm201to1000km,

                    '1001_to_2000_kms' => $request->p201gmTo500gm1001to2000km,

                    'above_2000_kms' => $request->p201gmTo500gmAbove2000km,

                ]);



                $rates[3]->update([

                    'weight' => 'Additional 500 gm or part thereof',

                    'Local' => $request->additional500gmlocal,

                    'upto_200_kms' => $request->additional500gm200km,

                    '201_to_1000_kms' => $request->additional500gm201to1000km,

                    '1001_to_2000_kms' => $request->additional500gm1001to2000km,

                    'above_2000_kms' => $request->additional500gmAbove2000km,

                ]);

                return redirect()->route('admin.postal-rates.index')->with('success', 'Parcel updated successfully!');
            } catch (\Exception $th) {

                return back()->with('error', $th->getMessage())->withInput();
            }
        }

        return view('admin.postal-rates.edit', compact('rates'));
    }
}
