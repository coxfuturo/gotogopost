<?php



namespace App\Http\Controllers\admin;



use App\Http\Controllers\Controller;

use App\Models\PostalRates;

use Illuminate\Http\Request;





class PostalRatesController extends Controller

{

    public function index(Request $request)

    {

        // PostalRates::query()->truncate();



        $title = PostalRates::getServiceType($request->type);



        $rates = PostalRates::where('type', $request->type)->get();

        return view('admin.postal-rates.index', ['rates' => $rates, 'title' => $title]);
    }



    public function create(Request $request)

    {



        if (count(PostalRates::where('type', $request->type)->get()) == 0) {

            return view('admin.postal-rates.create');
        } else {

            return redirect()->route('admin.postal-rates.index');
        }
    }



    public function store(Request $request)

    {



        if ($request->type == 1 || $request->type == 2) {

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

                // Create Parcel for "Up to 50 gm"

                PostalRates::create([

                    'weight' => 'Up to 50 gm',

                    'Local' => $request->p50gmlocal,

                    'upto_200_kms' => $request->p50gm200km,

                    '201_to_1000_kms' => $request->p50gm201to1000km,

                    '1001_to_2000_kms' => $request->p50gm1001to2000km,

                    'above_2000_kms' => $request->p50gmAbove2000km,

                    'type' => $request->type,

                ]);



                // Create Parcel for "51 to 200 gm"

                PostalRates::create([

                    'weight' => '51 to 200 gm',

                    'Local' => $request->p51gmTo200gmlocal,

                    'upto_200_kms' => $request->p51gmTo200gm200km,

                    '201_to_1000_kms' => $request->p51gmTo200gm201to1000km,

                    '1001_to_2000_kms' => $request->p51gmTo200gm1001to2000km,

                    'above_2000_kms' => $request->p51gmTo200gmAbove2000km,

                    'type' => $request->type,

                ]);



                // Create Parcel for "201 to 500 gm"

                PostalRates::create([

                    'weight' => '201 to 500 gm',

                    'Local' => $request->p201gmTo500gmlocal,

                    'upto_200_kms' => $request->p201gmTo500gm200km,

                    '201_to_1000_kms' => $request->p201gmTo500gm201to1000km,

                    '1001_to_2000_kms' => $request->p201gmTo500gm1001to2000km,

                    'above_2000_kms' => $request->p201gmTo500gmAbove2000km,

                    'type' => $request->type,

                ]);



                // Create Parcel for "Additional 500 gm or part thereof"

                PostalRates::create([

                    'weight' => 'additional 500 gm or part thereof',

                    'Local' => $request->additional500gmlocal,

                    'upto_200_kms' => $request->additional500gm200km,

                    '201_to_1000_kms' => $request->additional500gm201to1000km,

                    '1001_to_2000_kms' => $request->additional500gm1001to2000km,

                    'above_2000_kms' => $request->additional500gmAbove2000km,

                    'type' => $request->type,

                ]);





                return redirect()->route('admin.postal-rates.index', ['type' => $request->type])->with('success', 'Postal Rates Created');
            } catch (\Exception $th) {

                return back()->with('error', $th->getMessage());
            }
        }



        if ($request->type == 3) {



            // Validation rules

            $this->validate($request, [

                // Validate Up to 20 gm category

                'p20gm' => 'required|integer|min:0',

                // Validate 21 to 40 gm category

                'p40gm' => 'required|integer|min:0',

                // Validate 41 to 60 gm category

                'p60gm' => 'required|integer|min:0',

                // Validate 61 to 80 gm category

                'p80gm' => 'required|integer|min:0',

                // Validate 81 to 100 gm category

                'p100gm' => 'required|integer|min:0',

                // Validate 101 to 120 gm category

                'p120gm' => 'required|integer|min:0',

                // Validate 121 to 140 gm category

                'p140gm' => 'required|integer|min:0',

                // Validate 141 to 160 gm category

                'p160gm' => 'required|integer|min:0',

                // Validate 161 to 180 gm category

                'p180gm' => 'required|integer|min:0',

                // Validate 181 to 200 gm category

                'p200gm' => 'required|integer|min:0',

            ]);



            try {

                // Create Parcel for Up to 20gm

                PostalRates::create([

                    'weight' => 'Up to 20 gm',

                    'Local' => $request->p20gm,

                    'upto_200_kms' => $request->p20gm,

                    '201_to_1000_kms' => $request->p20gm,

                    '1001_to_2000_kms' => $request->p20gm,

                    'above_2000_kms' => $request->p20gm,

                    'type' => $request->type,

                ]);



                // Create Parcel for 21 to 40 gm

                PostalRates::create([

                    'weight' => '21 to 40 gm',

                    'Local' => $request->p40gm,

                    'upto_200_kms' => $request->p40gm,

                    '201_to_1000_kms' => $request->p40gm,

                    '1001_to_2000_kms' => $request->p40gm,

                    'above_2000_kms' => $request->p40gm,

                    'type' => $request->type,

                ]);



                // Create Parcel for 41 to 60 gm

                PostalRates::create([

                    'weight' => '41 to 60 gm',

                    'Local' => $request->p60gm,

                    'upto_200_kms' => $request->p60gm,

                    '201_to_1000_kms' => $request->p60gm,

                    '1001_to_2000_kms' => $request->p60gm,

                    'above_2000_kms' => $request->p60gm,

                    'type' => $request->type,

                ]);



                // Create Parcel for 61 to 80 gm

                PostalRates::create([

                    'weight' => '61 to 80 gm',

                    'Local' => $request->p80gm,

                    'upto_200_kms' => $request->p80gm,

                    '201_to_1000_kms' => $request->p80gm,

                    '1001_to_2000_kms' => $request->p80gm,

                    'above_2000_kms' => $request->p80gm,

                    'type' => $request->type,

                ]);



                // Create Parcel for 81 to 100 gm

                PostalRates::create([

                    'weight' => '81 to 100 gm',

                    'Local' => $request->p100gm,

                    'upto_200_kms' => $request->p100gm,

                    '201_to_1000_kms' => $request->p100gm,

                    '1001_to_2000_kms' => $request->p100gm,

                    'above_2000_kms' => $request->p100gm,

                    'type' => $request->type,

                ]);



                // Create Parcel for 101 to 120 gm

                PostalRates::create([

                    'weight' => '101 to 120 gm',

                    'Local' => $request->p120gm,

                    'upto_200_kms' => $request->p120gm,

                    '201_to_1000_kms' => $request->p120gm,

                    '1001_to_2000_kms' => $request->p120gm,

                    'above_2000_kms' => $request->p120gm,

                    'type' => $request->type,

                ]);



                // Create Parcel for 121 to 140 gm

                PostalRates::create([

                    'weight' => '121 to 140 gm',

                    'Local' => $request->p140gm,

                    'upto_200_kms' => $request->p140gm,

                    '201_to_1000_kms' => $request->p140gm,

                    '1001_to_2000_kms' => $request->p140gm,

                    'above_2000_kms' => $request->p140gm,

                    'type' => $request->type,

                ]);



                // Create Parcel for 141 to 160 gm

                PostalRates::create([

                    'weight' => '141 to 160 gm',

                    'Local' => $request->p160gm,

                    'upto_200_kms' => $request->p160gm,

                    '201_to_1000_kms' => $request->p160gm,

                    '1001_to_2000_kms' => $request->p160gm,

                    'above_2000_kms' => $request->p160gm,

                    'type' => $request->type,

                ]);





                // Create Parcel for 161 to 180 gm

                PostalRates::create([

                    'weight' => '161 to 180 gm',

                    'Local' => $request->p180gm,

                    'upto_200_kms' => $request->p180gm,

                    '201_to_1000_kms' => $request->p180gm,

                    '1001_to_2000_kms' => $request->p180gm,

                    'above_2000_kms' => $request->p180gm,

                    'type' => $request->type,

                ]);



                // Create Parcel for 181 to 200 gm

                PostalRates::create([

                    'weight' => '181 to 200 gm',

                    'Local' => $request->p200gm,

                    'upto_200_kms' => $request->p200gm,

                    '201_to_1000_kms' => $request->p200gm,

                    '1001_to_2000_kms' => $request->p200gm,

                    'above_2000_kms' => $request->p200gm,

                    'type' => $request->type,

                ]);



                return redirect()->route('admin.postal-rates.index', ['type' => $request->type])->with('success', 'Postal Rates Created');
            } catch (\Exception $th) {

                return back()->with('error', $th->getMessage());
            }
        }





        if ($request->type == 4) {



            // Validation rules

            $this->validate($request, [

                // Validate Up to 50 gm category

                'p50gmlocal' => 'required|integer|min:0',

                'p50gm200km' => 'required|integer|min:0',

                'p50gm201to1000km' => 'required|integer|min:0',

                'p50gm1001to2000km' => 'required|integer|min:0',

                'p50gmAbove2000km' => 'required|integer|min:0',



                // Validate 51 to 100 gm category

                'p51gmTo100gmlocal' => 'required|integer|min:0',

                'p51gmTo100gm200km' => 'required|integer|min:0',

                'p51gmTo100gm201to1000km' => 'required|integer|min:0',

                'p51gmTo100gm1001to2000km' => 'required|integer|min:0',

                'p51gmTo100gmAbove2000km' => 'required|integer|min:0',



                // Validate 101 to 150 gm category

                'p101gmTo150gmlocal' => 'required|integer|min:0',

                'p101gmTo150gm200km' => 'required|integer|min:0',

                'p101gmTo150gm201to1000km' => 'required|integer|min:0',

                'p101gmTo150gm1001to2000km' => 'required|integer|min:0',

                'p101gmTo150gmAbove2000km' => 'required|integer|min:0',



                // Validate 151 to 200 gm category

                'p151gmTo200gmlocal' => 'required|integer|min:0',

                'p151gmTo200gm200km' => 'required|integer|min:0',

                'p151gmTo200gm201to1000km' => 'required|integer|min:0',

                'p151gmTo200gm1001to2000km' => 'required|integer|min:0',

                'p151gmTo200gmAbove2000km' => 'required|integer|min:0',



                // Validate 201 to 250 gm category

                'p201gmTo250gmlocal' => 'required|integer|min:0',

                'p201gmTo250gm200km' => 'required|integer|min:0',

                'p201gmTo250gm201to1000km' => 'required|integer|min:0',

                'p201gmTo250gm1001to2000km' => 'required|integer|min:0',

                'p201gmTo250gmAbove2000km' => 'required|integer|min:0',

            ]);



            try {

                // Create Parcel for "Up to 50 gm"

                PostalRates::create([

                    'weight' => 'Up to 50 gm',

                    'Local' => $request->p50gmlocal,

                    'upto_200_kms' => $request->p50gm200km,

                    '201_to_1000_kms' => $request->p50gm201to1000km,

                    '1001_to_2000_kms' => $request->p50gm1001to2000km,

                    'above_2000_kms' => $request->p50gmAbove2000km,

                    'type' => $request->type,

                ]);



                // Create Parcel for "51 to 100 gm"

                PostalRates::create([

                    'weight' => '51 to 100 gm',

                    'Local' => $request->p51gmTo100gmlocal,

                    'upto_200_kms' => $request->p51gmTo100gm200km,

                    '201_to_1000_kms' => $request->p51gmTo100gm201to1000km,

                    '1001_to_2000_kms' => $request->p51gmTo100gm1001to2000km,

                    'above_2000_kms' => $request->p51gmTo100gmAbove2000km,

                    'type' => $request->type,

                ]);



                // Create Parcel for "101 to 150 gm"

                PostalRates::create([

                    'weight' => '101 to 150 gm',

                    'Local' => $request->p101gmTo150gmlocal,

                    'upto_200_kms' => $request->p101gmTo150gm200km,

                    '201_to_1000_kms' => $request->p101gmTo150gm201to1000km,

                    '1001_to_2000_kms' => $request->p101gmTo150gm1001to2000km,

                    'above_2000_kms' => $request->p101gmTo150gmAbove2000km,

                    'type' => $request->type,

                ]);



                // Create Parcel for "151 to 200 gm"

                PostalRates::create([

                    'weight' => '151 to 200 gm',

                    'Local' => $request->p151gmTo200gmlocal,

                    'upto_200_kms' => $request->p151gmTo200gm200km,

                    '201_to_1000_kms' => $request->p151gmTo200gm201to1000km,

                    '1001_to_2000_kms' => $request->p151gmTo200gm1001to2000km,

                    'above_2000_kms' => $request->p151gmTo200gmAbove2000km,

                    'type' => $request->type,

                ]);



                // Create Parcel for "201 to 250 gm"

                PostalRates::create([

                    'weight' => '201 to 250 gm',

                    'Local' => $request->p201gmTo250gmlocal,

                    'upto_200_kms' => $request->p201gmTo250gm200km,

                    '201_to_1000_kms' => $request->p201gmTo250gm201to1000km,

                    '1001_to_2000_kms' => $request->p201gmTo250gm1001to2000km,

                    'above_2000_kms' => $request->p201gmTo250gmAbove2000km,

                    'type' => $request->type,

                ]);



                return redirect()->route('admin.postal-rates.index', ['type' => $request->type])->with('success', 'Postal Rates Created');
            } catch (\Exception $th) {

                return back()->with('error', $th->getMessage());
            }
        }
    }





    public function edit(Request $request)

    {



        $rates =  PostalRates::where('type', $request->type)->get();


        if ($request->type == 1 || $request->type == 2) {

            if ($request->isMethod('POST')) {

                $this->validate($request, [

                    // Validate 50gm category

                    'p50gmlocal' => 'required|integer|min:0',

                    'p50gm200km' => 'required|integer|min:0',

                    'p50gm201to500km' => 'required|integer|min:0',

                    'p50gm501to1000km' => 'required|integer|min:0',

                    'p50gm1001to2000km' => 'required|integer|min:0',

                    'p50gmAbove2000km' => 'required|integer|min:0',



                    // Validate 51gm to 200gm category

                    'p51gmTo200gmlocal' => 'required|integer|min:0',

                    'p51gmTo200gm200km' => 'required|integer|min:0',

                    'p51gmTo200gm201to500km' => 'required|integer|min:0',

                    'p51gmTo200gm501to1000km' => 'required|integer|min:0',

                    'p51gmTo200gm1001to2000km' => 'required|integer|min:0',

                    'p51gmTo200gmAbove2000km' => 'required|integer|min:0',



                    // Validate 201gm to 500gm category

                    'p201gmTo500gmlocal' => 'required|integer|min:0',

                    'p201gmTo500gm200km' => 'required|integer|min:0',

                    'p201gmTo500gm201to500km' => 'required|integer|min:0',

                    'p201gmTo500gm501to1000km' => 'required|integer|min:0',

                    'p201gmTo500gmAbove2000km' => 'required|integer|min:0',

                    'p201gmTo500gmAbove2000km' => 'required|integer|min:0',



                    // Validate additional 500gm category

                    'additional500gmlocal' => 'required|integer|min:0',

                    'additional500gm200km' => 'required|integer|min:0',

                    'additional201gm201to500km' => 'required|integer|min:0',

                    'additional500gm501to1000km' => 'required|integer|min:0',

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

                        'rate_201_to_500_kms' => $request->p50gm201to500km,

                        'rate_501_to_1000_kms' => $request->p50gm501to1000km,

                        'rate_1001_to_2000_kms' => $request->p50gm1001to2000km,

                        'above_2000_kms' => $request->p50gmAbove2000km,

                        'type' => $request->type,

                    ]);



                    $rates[1]->update([

                        'weight' => '51 to 200 gm',

                        'Local' => $request->p51gmTo200gmlocal,

                        'upto_200_kms' => $request->p51gmTo200gm200km,

                        'rate_201_to_500_kms' => $request->p51gmTo200gm201to500km,

                        'rate_501_to_1000_kms' => $request->p51gmTo200gm501to1000km,

                        'rate_1001_to_2000_kms' => $request->p51gmTo200gm1001to2000km,

                        'above_2000_kms' => $request->p51gmTo200gmAbove2000km,

                        'type' => $request->type,

                    ]);



                    $rates[2]->update([

                        'weight' => '201 to 500 gm',

                        'Local' => $request->p201gmTo500gmlocal,

                        'upto_200_kms' => $request->p201gmTo500gm200km,

                        'rate_201_to_500_kms' => $request->p201gmTo500gm201to500km,

                        'rate_501_to_1000_kms' => $request->p201gmTo500gm501to1000km,

                        'rate_1001_to_2000_kms' => $request->p201gmTo1001gm1001to2000km,

                        'above_2000_kms' => $request->p201gmTo500gmAbove2000km,

                        'type' => $request->type,

                    ]);



                    $rates[3]->update([

                        'weight' => 'Additional 500 gm or part thereof',

                        'Local' => $request->additional500gmlocal,

                        'upto_200_kms' => $request->additional500gm200km,

                        'rate_201_to_500_kms' => $request->additional201gm201to500km,

                        'rate_501_to_1000_kms' => $request->additional500gm501to1000km,

                        'rate_1001_to_2000_kms' => $request->additional500gm1001to2000km,

                        'above_2000_kms' => $request->additional500gmAbove2000km,

                        'type' => $request->type,

                    ]);

                    return redirect()->route('admin.postal-rates.index', ['type' => $request->type])->with('success', 'Parcel updated successfully!');
                } catch (\Exception $th) {

                    return back()->with('error', $th->getMessage())->withInput();
                }
            }

            return view('admin.postal-rates.edit', compact('rates'));
        }



        if ($request->type == 3) {

            if ($request->isMethod('POST')) {



                $this->validate($request, [

                    // Validate Up to 20 gm category

                    'p20gm' => 'required|integer|min:0',

                    // Validate 21 to 40 gm category

                    'p40gm' => 'required|integer|min:0',

                    // Validate 41 to 60 gm category

                    'p60gm' => 'required|integer|min:0',

                    // Validate 61 to 80 gm category

                    'p80gm' => 'required|integer|min:0',

                    // Validate 81 to 100 gm category

                    'p100gm' => 'required|integer|min:0',

                    // Validate 101 to 120 gm category

                    'p120gm' => 'required|integer|min:0',

                    // Validate 121 to 140 gm category

                    'p140gm' => 'required|integer|min:0',

                    // Validate 141 to 160 gm category

                    'p160gm' => 'required|integer|min:0',

                    // Validate 161 to 180 gm category

                    'p180gm' => 'required|integer|min:0',

                    // Validate 181 to 200 gm category

                    'p200gm' => 'required|integer|min:0',

                ]);



                try {


                    // Create Parcel for Up to 20gm

                    $rates[0]->update([

                        'weight' => 'Up to 20 gm',

                        'Local' => $request->p20gm,

                        'upto_200_kms' => $request->p20gm,

                        '201_to_1000_kms' => $request->p20gm,

                        '1001_to_2000_kms' => $request->p20gm,

                        'above_2000_kms' => $request->p20gm,

                        'type' => $request->type,

                    ]);



                    // Create Parcel for 21 to 40 gm

                    $rates[1]->update([

                        'weight' => '21 to 40 gm',

                        'Local' => $request->p40gm,

                        'upto_200_kms' => $request->p40gm,

                        '201_to_1000_kms' => $request->p40gm,

                        '1001_to_2000_kms' => $request->p40gm,

                        'above_2000_kms' => $request->p40gm,

                        'type' => $request->type,

                    ]);



                    // Create Parcel for 41 to 60 gm

                    $rates[2]->update([

                        'weight' => '41 to 60 gm',

                        'Local' => $request->p60gm,

                        'upto_200_kms' => $request->p60gm,

                        '201_to_1000_kms' => $request->p60gm,

                        '1001_to_2000_kms' => $request->p60gm,

                        'above_2000_kms' => $request->p60gm,

                        'type' => $request->type,

                    ]);



                    // Create Parcel for 61 to 80 gm

                    $rates[3]->update([

                        'weight' => '61 to 80 gm',

                        'Local' => $request->p80gm,

                        'upto_200_kms' => $request->p80gm,

                        '201_to_1000_kms' => $request->p80gm,

                        '1001_to_2000_kms' => $request->p80gm,

                        'above_2000_kms' => $request->p80gm,

                        'type' => $request->type,

                    ]);



                    // Create Parcel for 81 to 100 gm

                    $rates[4]->update([

                        'weight' => '81 to 100 gm',

                        'Local' => $request->p100gm,

                        'upto_200_kms' => $request->p100gm,

                        '201_to_1000_kms' => $request->p100gm,

                        '1001_to_2000_kms' => $request->p100gm,

                        'above_2000_kms' => $request->p100gm,

                        'type' => $request->type,

                    ]);



                    // Create Parcel for 101 to 120 gm

                    $rates[5]->update([

                        'weight' => '101 to 120 gm',

                        'Local' => $request->p120gm,

                        'upto_200_kms' => $request->p120gm,

                        '201_to_1000_kms' => $request->p120gm,

                        '1001_to_2000_kms' => $request->p120gm,

                        'above_2000_kms' => $request->p120gm,

                        'type' => $request->type,

                    ]);



                    // Create Parcel for 121 to 140 gm

                    $rates[6]->update([

                        'weight' => '121 to 140 gm',

                        'Local' => $request->p140gm,

                        'upto_200_kms' => $request->p140gm,

                        '201_to_1000_kms' => $request->p140gm,

                        '1001_to_2000_kms' => $request->p140gm,

                        'above_2000_kms' => $request->p140gm,

                        'type' => $request->type,

                    ]);



                    // Create Parcel for 141 to 160 gm

                    $rates[7]->update([

                        'weight' => '141 to 160 gm',

                        'Local' => $request->p160gm,

                        'upto_200_kms' => $request->p160gm,

                        '201_to_1000_kms' => $request->p160gm,

                        '1001_to_2000_kms' => $request->p160gm,

                        'above_2000_kms' => $request->p160gm,

                        'type' => $request->type,

                    ]);





                    // Create Parcel for 161 to 180 gm

                    $rates[8]->update([

                        'weight' => '161 to 180 gm',

                        'Local' => $request->p180gm,

                        'upto_200_kms' => $request->p180gm,

                        '201_to_1000_kms' => $request->p180gm,

                        '1001_to_2000_kms' => $request->p180gm,

                        'above_2000_kms' => $request->p180gm,

                        'type' => $request->type,

                    ]);



                    // Create Parcel for 181 to 200 gm

                    $rates[9]->update([

                        'weight' => '181 to 200 gm',

                        'Local' => $request->p200gm,

                        'upto_200_kms' => $request->p200gm,

                        '201_to_1000_kms' => $request->p200gm,

                        '1001_to_2000_kms' => $request->p200gm,

                        'above_2000_kms' => $request->p200gm,

                        'type' => $request->type,

                    ]);


                    return redirect()->route('admin.postal-rates.index', ['type' => $request->type])->with('success', 'Parcel updated successfully!');
                } catch (\Exception $th) {

                    return back()->with('error', $th->getMessage())->withInput();
                }
            }

            return view('admin.postal-rates.edit', compact('rates'));
        }



        if ($request->type == 4) {



            if ($request->isMethod('POST')) {



                $this->validate($request, [

                    // Validate Up to 50 gm category

                    'p50gmlocal' => 'required|integer|min:0',

                    'p50gm200km' => 'required|integer|min:0',

                    'p50gm201to1000km' => 'required|integer|min:0',

                    'p50gm1001to2000km' => 'required|integer|min:0',

                    'p50gmAbove2000km' => 'required|integer|min:0',



                    // Validate 51 to 100 gm category

                    'p51gmTo100gmlocal' => 'required|integer|min:0',

                    'p51gmTo100gm200km' => 'required|integer|min:0',

                    'p51gmTo100gm201to1000km' => 'required|integer|min:0',

                    'p51gmTo100gm1001to2000km' => 'required|integer|min:0',

                    'p51gmTo100gmAbove2000km' => 'required|integer|min:0',



                    // Validate 101 to 150 gm category

                    'p101gmTo150gmlocal' => 'required|integer|min:0',

                    'p101gmTo150gm200km' => 'required|integer|min:0',

                    'p101gmTo150gm201to1000km' => 'required|integer|min:0',

                    'p101gmTo150gm1001to2000km' => 'required|integer|min:0',

                    'p101gmTo150gmAbove2000km' => 'required|integer|min:0',



                    // Validate 151 to 200 gm category

                    'p151gmTo200gmlocal' => 'required|integer|min:0',

                    'p151gmTo200gm200km' => 'required|integer|min:0',

                    'p151gmTo200gm201to1000km' => 'required|integer|min:0',

                    'p151gmTo200gm1001to2000km' => 'required|integer|min:0',

                    'p151gmTo200gmAbove2000km' => 'required|integer|min:0',



                    // Validate 201 to 250 gm category

                    'p201gmTo250gmlocal' => 'required|integer|min:0',

                    'p201gmTo250gm200km' => 'required|integer|min:0',

                    'p201gmTo250gm201to1000km' => 'required|integer|min:0',

                    'p201gmTo250gm1001to2000km' => 'required|integer|min:0',

                    'p201gmTo250gmAbove2000km' => 'required|integer|min:0',

                ]);



                try {



                    // Create Parcel for "Up to 50 gm"

                    $rates[0]->update([

                        'weight' => 'Up to 50 gm',

                        'Local' => $request->p50gmlocal,

                        'upto_200_kms' => $request->p50gm200km,

                        '201_to_1000_kms' => $request->p50gm201to1000km,

                        '1001_to_2000_kms' => $request->p50gm1001to2000km,

                        'above_2000_kms' => $request->p50gmAbove2000km,

                        'type' => $request->type,

                    ]);



                    // Create Parcel for "51 to 100 gm"

                    $rates[1]->update([

                        'weight' => '51 to 100 gm',

                        'Local' => $request->p51gmTo100gmlocal,

                        'upto_200_kms' => $request->p51gmTo100gm200km,

                        '201_to_1000_kms' => $request->p51gmTo100gm201to1000km,

                        '1001_to_2000_kms' => $request->p51gmTo100gm1001to2000km,

                        'above_2000_kms' => $request->p51gmTo100gmAbove2000km,

                        'type' => $request->type,

                    ]);



                    // Create Parcel for "101 to 150 gm"

                    $rates[2]->update([

                        'weight' => '101 to 150 gm',

                        'Local' => $request->p101gmTo150gmlocal,

                        'upto_200_kms' => $request->p101gmTo150gm200km,

                        '201_to_1000_kms' => $request->p101gmTo150gm201to1000km,

                        '1001_to_2000_kms' => $request->p101gmTo150gm1001to2000km,

                        'above_2000_kms' => $request->p101gmTo150gmAbove2000km,

                        'type' => $request->type,

                    ]);



                    // Create Parcel for "151 to 200 gm"

                    $rates[3]->update([

                        'weight' => '151 to 200 gm',

                        'Local' => $request->p151gmTo200gmlocal,

                        'upto_200_kms' => $request->p151gmTo200gm200km,

                        '201_to_1000_kms' => $request->p151gmTo200gm201to1000km,

                        '1001_to_2000_kms' => $request->p151gmTo200gm1001to2000km,

                        'above_2000_kms' => $request->p151gmTo200gmAbove2000km,

                        'type' => $request->type,

                    ]);



                    // Create Parcel for "201 to 250 gm"

                    $rates[4]->update([

                        'weight' => '201 to 250 gm',

                        'Local' => $request->p201gmTo250gmlocal,

                        'upto_200_kms' => $request->p201gmTo250gm200km,

                        '201_to_1000_kms' => $request->p201gmTo250gm201to1000km,

                        '1001_to_2000_kms' => $request->p201gmTo250gm1001to2000km,

                        'above_2000_kms' => $request->p201gmTo250gmAbove2000km,

                        'type' => $request->type,

                    ]);



                    return redirect()->route('admin.postal-rates.index', ['type' => $request->type])->with('success', 'Parcel updated successfully!');
                } catch (\Exception $th) {

                    return back()->with('error', $th->getMessage())->withInput();
                }
            }

            return view('admin.postal-rates.edit', compact('rates'));
        }
    }
}
