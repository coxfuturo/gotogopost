<?php



namespace App\Http\Controllers\admin;



use App\Http\Controllers\Controller;
use App\Models\Franchise;
use App\Models\CMS;
use App\Models\PPH;

use App\Models\IndiaPostLink;

use App\Models\GotogoLink;


use Illuminate\Http\Request;

use DB;





class LinkController extends Controller

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


    public function gotogoLinks(Request $request)
    {
        $link = GotogoLink::with(['franchise', 'cms', 'pph'])->get();

        return view('admin.link.gotogo', ['link' => $link]);
    }


    public function indiaPostLinks()

    {
        $link = IndiaPostLink::with('franchise')->get();
        return view('admin.link.indiaPost', ['link' => $link]);
    }



    public function create()

    {

        // PostalRates::query()->truncate();


        $franchise = Franchise::where('status', 1)->get();

        $cms = CMS::where('status', 1)->get();
        $pph = PPH::where('status', 1)->get();

        $serviceType = [

            'gotoSpeed' => 'Gotogo Post Speed',

            'gotoSuperFast' => 'Gotogo Post SuperFast',

            'gotoBusiness' => 'Gotogo Post Business Parcel',

            'gotoRegistered' => 'Gotogo Post Registered',

            'IPSpeed' => 'India Post Speed',

            'IPBusiness' => 'India Post Business',

            'IPRegistered' => 'India Post Registered',

        ];

        return view('admin.link.create', ['franchise' => $franchise, 'serviceType' => $serviceType, 'cms' => $cms, 'pph' => $pph]);
    }

















    public function gotogoLink(Request $request)
    {
        // Validate the input data
        $request->validate([
            'franchise_no' => 'required|string',
            'cms_no' => 'required|string',
            'pph_no' => 'required|string',
        ]);

        // Check if a record with the given franchise_no exists, and update or create it accordingly
        GotogoLink::updateOrCreate(
            ['franchise_no' => $request->input('franchise_no')],  // Condition to check
            [
                'cms_no' => $request->input('cms_no'),            // Data to update if exists or to create if not
                'pph_no' => $request->input('pph_no'),
            ]
        );

        return redirect()->back()->with('success', "Link formed successfully");
    }



    public function indiaPostLink(Request $request)
    {


        // Validate the input data
        $request->validate([
            'franchise_no' => 'required|string',
            'city' => 'required|string',
            'bnpl_no' => 'required|string',
            'customer_id' => 'required|string',
            'contract_id' => 'required|string',
        ]);

        // Update or create the record
        IndiaPostLink::updateOrCreate(
            ['franchise_no' => $request->input('franchise_no')],
            [
                'city' => $request->input('city'),
                'bnpl_no' => $request->input('bnpl_no'),
                'customer_id' => $request->input('customer_id'),
                'contract_id' => $request->input('contract_id'),
            ]
        );

        return redirect()->back()->with('success', "Link formed successfully");
    }
}
