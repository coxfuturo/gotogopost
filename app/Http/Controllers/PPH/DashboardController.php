<?php

namespace App\Http\Controllers\PPH;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\GotogoSpeedPostParcel;
use App\Models\GotogoBusinessParcel;
use App\Models\IndiaPostSpeedPostParcel;
use App\Models\GotogoRegisteredParcel;
use App\Models\FranchiseCommissionDetail;
use App\Models\IndiaPostRegisteredParcel;
use App\Models\IndiaPostBusinessParcel;
use App\Models\Franchise;
use App\Models\PPHBag;
use App\Models\DeliveryBoy;
use App\Models\PickupDetails;
use App\Models\CMSBag;
use App\Models\PPHCommissionDetail;
use App\Models\Mail;
use Carbon\Carbon;

class DashboardController extends Controller
{


    public function parcelInsideGoToGoBagCount($start, $end)
    {
        $CMSId = Auth::guard('pph')->user()->id;

        $allbgIdOfGoToGoSpeedPost = CMSBag::where('pph_id', $CMSId)
            ->whereBetween('received_date', [$start, $end])
            ->where('service_type', 1)
            ->pluck('id');

        $totalCountSpeedPost = GotogoSpeedPostParcel::whereIn('source_cms_bag_id', $allbgIdOfGoToGoSpeedPost)
            ->orWhereIn('destination_cms_bag_id', $allbgIdOfGoToGoSpeedPost)
            ->count();

        $allbgIdOfGoToGoBussinesParcel = CMSBag::where('pph_id', $CMSId)
            ->whereBetween('received_date', [$start, $end])
            ->where('service_type', 3)
            ->pluck('id');

        $totalCountBussinesParcel = GotogoBusinessParcel::whereIn('source_cms_bag_id', $allbgIdOfGoToGoBussinesParcel)
            ->orWhereIn('destination_cms_bag_id', $allbgIdOfGoToGoBussinesParcel)
            ->count();

        $allbgIdOfGoToGoRegister = CMSBag::where('pph_id', $CMSId)
            ->whereBetween('received_date', [$start, $end])
            ->where('service_type', 4)
            ->pluck('id');

        $totalCountRegister = GotogoRegisteredParcel::whereIn('source_cms_bag_id', $allbgIdOfGoToGoRegister)
            ->orWhereIn('destination_cms_bag_id', $allbgIdOfGoToGoRegister)
            ->count();

        $allParcelInsideCount = $totalCountSpeedPost + $totalCountBussinesParcel + $totalCountRegister;

        $goToGoBagCount = count($allbgIdOfGoToGoSpeedPost) + count($allbgIdOfGoToGoBussinesParcel) + count($allbgIdOfGoToGoRegister);


        return ['allParcelInsideCount' => $allParcelInsideCount, 'goToGoBagCount' => $goToGoBagCount];
    }

    public function parcelInsideGoToGoBagSum($start, $end)
    {
        $CMSId = Auth::guard('pph')->user()->id;

        $allbgIdOfGoToGoSpeedPost = CMSBag::where('pph_id', $CMSId)
            ->whereBetween('received_date', [$start, $end])
            ->where('service_type', 1)
            ->pluck('id');

        $totalCountSpeedPost = GotogoSpeedPostParcel::whereIn('source_cms_bag_id', $allbgIdOfGoToGoSpeedPost)
            ->orWhereIn('destination_cms_bag_id', $allbgIdOfGoToGoSpeedPost)
            ->sum('payment_amount');

        $allbgIdOfGoToGoBussinesParcel = CMSBag::where('pph_id', $CMSId)
            ->whereBetween('received_date', [$start, $end])
            ->where('service_type', 3)
            ->pluck('id');

        $totalCountBussinesParcel = GotogoBusinessParcel::whereIn('source_cms_bag_id', $allbgIdOfGoToGoBussinesParcel)
            ->orWhereIn('destination_cms_bag_id', $allbgIdOfGoToGoBussinesParcel)
            ->sum('payment_amount');

        $allbgIdOfGoToGoRegister = CMSBag::where('pph_id', $CMSId)
            ->whereBetween('received_date', [$start, $end])
            ->where('service_type', 4)
            ->pluck('id');

        $totalCountRegister = GotogoRegisteredParcel::whereIn('source_cms_bag_id', $allbgIdOfGoToGoRegister)
            ->orWhereIn('destination_cms_bag_id', $allbgIdOfGoToGoRegister)
            ->sum('payment_amount');

        $allParcelInsideSum = $totalCountSpeedPost + $totalCountBussinesParcel + $totalCountRegister;

        return $allParcelInsideSum;
    }

    public function totalCreatedBagsCount($start, $end)
    {
        $CMSId = Auth::guard('pph')->user()->id;

        return PPHBag::where('pph_id', $CMSId)->whereBetween('created_at', [$start, $end])->count();
    }

    //  india Post sum and count
    public function indiaPostInsideGoToGoBagCount($start, $end)
    {
        $CMSId = Auth::guard('pph')->user()->id;

        $allbgIdOfGoToGoSpeedPost = CMSBag::where('pph_id', $CMSId)
            ->whereBetween('received_date', [$start, $end])
            ->where('service_type', 5)
            ->pluck('id');

        $totalCountSpeedPost = IndiaPostSpeedPostParcel::whereIn('source_cms_bag_id', $allbgIdOfGoToGoSpeedPost)
            ->orWhereIn('destination_cms_bag_id', $allbgIdOfGoToGoSpeedPost)
            ->count();

        $allbgIdOfGoToGoBussinesParcel = CMSBag::where('pph_id', $CMSId)
            ->whereBetween('received_date', [$start, $end])
            ->where('service_type', 6)
            ->pluck('id');

        $totalCountBussinesParcel = IndiaPostBusinessParcel::whereIn('source_cms_bag_id', $allbgIdOfGoToGoBussinesParcel)
            ->orWhereIn('destination_cms_bag_id', $allbgIdOfGoToGoBussinesParcel)
            ->count();


        $allIndiaPostInsideCount = $totalCountSpeedPost + $totalCountBussinesParcel;

        $allIndiaPostBagCount = count($allbgIdOfGoToGoSpeedPost) + count($allbgIdOfGoToGoBussinesParcel);

        return ['allIndiaPostInsideCount' => $allIndiaPostInsideCount, 'allIndiaPostBagCount' => $allIndiaPostBagCount];
    }

    public function indiaPostInsideGoToGoBagSum($start, $end)
    {
        $CMSId = Auth::guard('pph')->user()->id;

        $allbgIdOfGoToGoSpeedPost = CMSBag::where('pph_id', $CMSId)
            ->whereBetween('received_date', [$start, $end])
            ->where('service_type', 5)
            ->pluck('id');

        $totalCountSpeedPost = IndiaPostSpeedPostParcel::whereIn('source_cms_bag_id', $allbgIdOfGoToGoSpeedPost)
            ->orWhereIn('destination_cms_bag_id', $allbgIdOfGoToGoSpeedPost)
            ->sum('payment_amount');

        $allbgIdOfGoToGoBussinesParcel = CMSBag::where('pph_id', $CMSId)
            ->whereBetween('received_date', [$start, $end])
            ->where('service_type', 6)
            ->pluck('id');

        $totalCountBussinesParcel = IndiaPostBusinessParcel::whereIn('source_cms_bag_id', $allbgIdOfGoToGoBussinesParcel)
            ->orWhereIn('destination_cms_bag_id', $allbgIdOfGoToGoBussinesParcel)
            ->sum('payment_amount');


        $allIndiaPostInsideSum = $totalCountSpeedPost + $totalCountBussinesParcel;

        return $allIndiaPostInsideSum;
    }


    public function parcelInsideGoToGoBag($start, $end)
    {

        // dd($formattedDate);
        $CMSId = Auth::guard('pph')->user()->id;

        $allbgIdOfGoToGoSpeedPost = CMSBag::where('pph_id', $CMSId)
            ->where('service_type', 1)
            ->whereBetween('received_date', [$start, $end])
            ->pluck('id');

        $totalCountSpeedPost = GotogoSpeedPostParcel::whereIn('source_cms_bag_id', $allbgIdOfGoToGoSpeedPost)
            ->orWhereIn('destination_cms_bag_id', $allbgIdOfGoToGoSpeedPost)
            ->orderBy('created_at', 'desc')
            ->get();

        $allbgIdOfGoToGoBussinesParcel = CMSBag::where('pph_id', $CMSId)
            ->where('service_type', 3)
            ->whereBetween('received_date', [$start, $end])
            ->pluck('id');

        $totalCountBussinesParcel = GotogoBusinessParcel::whereIn('source_cms_bag_id', $allbgIdOfGoToGoBussinesParcel)
            ->orWhereIn('destination_cms_bag_id', $allbgIdOfGoToGoBussinesParcel)
            ->orderBy('created_at', 'desc')
            ->get();

        $allbgIdOfGoToGoRegister = CMSBag::where('pph_id', $CMSId)
            ->where('service_type', 4)
            ->whereBetween('received_date', [$start, $end])
            ->pluck('id');

        $allParcelInsideList = GotogoRegisteredParcel::whereIn('source_cms_bag_id', $allbgIdOfGoToGoRegister)
            ->orWhereIn('destination_cms_bag_id', $allbgIdOfGoToGoRegister)
            ->orderBy('created_at', 'desc')
            ->get();

        $gotogoList = $totalCountSpeedPost->merge($totalCountBussinesParcel)->merge($allParcelInsideList);

        return $gotogoList;
    }

    // india post list  
    public function parcelInsideIndiaPostBag($start, $end)
    {
        $CMSId = Auth::guard('pph')->user()->id;

        $allbgIdOfGoToGoSpeedPost = CMSBag::where('pph_id', $CMSId)
            ->where('service_type', 5)
            ->whereBetween('received_date', [$start, $end])
            ->pluck('id');

        $totalCountSpeedPost = IndiaPostSpeedPostParcel::whereIn('source_cms_bag_id', $allbgIdOfGoToGoSpeedPost)
            ->orWhereIn('destination_cms_bag_id', $allbgIdOfGoToGoSpeedPost)
            ->orderBy('created_at', 'desc')
            ->get();

        $allbgIdOfGoToGoBussinesParcel = CMSBag::where('pph_id', $CMSId)
            ->where('service_type', 6)
            ->whereBetween('received_date', [$start, $end])
            ->pluck('id');

        $totalCountBussinesParcel = IndiaPostBusinessParcel::whereIn('source_cms_bag_id', $allbgIdOfGoToGoBussinesParcel)
            ->orWhereIn('destination_cms_bag_id', $allbgIdOfGoToGoBussinesParcel)
            ->orderBy('created_at', 'desc')
            ->get();

        $allbgIdOfGoToGoRegister = CMSBag::where('pph_id', $CMSId)
            ->where('service_type', 7)
            ->whereBetween('received_date', [$start, $end])
            ->pluck('id');

        $totalCountRegister = IndiaPostRegisteredParcel::whereIn('source_cms_bag_id', $allbgIdOfGoToGoRegister)
            ->orWhereIn('destination_cms_bag_id', $allbgIdOfGoToGoRegister)
            ->orderBy('created_at', 'desc')
            ->get();


        $gotogoIndiaPostList = $totalCountSpeedPost->merge($totalCountBussinesParcel)->merge($totalCountRegister);


        return $gotogoIndiaPostList;
    }

    public function index(Request $request)
    {

        $data = [];

        $start = $request->start ? Carbon::parse($request->start)->format('Y-m-d') : Carbon::today()->startOfDay();
        $end = $request->end ? Carbon::parse($request->end)->format('Y-m-d') : Carbon::today()->endOfDay();

        // Total count of parcels
        $data['gotogolistList'] = $this->parcelInsideGoToGoBagCount($start, $end)['allParcelInsideCount'];;

        // Total payment of Parcel
        $data['gotogolistAmount'] = $this->parcelInsideGoToGoBagSum($start, $end);

        // Total count of india Post
        $data['indiapostlistList'] = $this->indiaPostInsideGoToGoBagCount($start, $end)['allIndiaPostInsideCount'];
        $data['totalReceivedBagsCount'] = $this->indiaPostInsideGoToGoBagCount($start, $end)['allIndiaPostBagCount'] + $this->parcelInsideGoToGoBagCount($start, $end)['goToGoBagCount'];
        $data['totalCreatedBagsCount'] =  $this->totalCreatedBagsCount($start, $end);

        // Total payment of india post
        $data['gotogolistIndiaAmount'] = $this->indiaPostInsideGoToGoBagSum($start, $end);

        // list data
        $data['gotogoList'] = $this->parcelInsideGoToGoBag($start, $end);
        $data['indiapostlist'] = $this->parcelInsideIndiaPostBag($start, $end);
        // $data['indiapostlist'] = $indiaPostSpeedList->merge($indiaPostBusinessList)->merge($indiaPostRegisteredList);


        $CMSId = Auth::guard('pph')->user()->id;
        $data['commissionList'] = PPHCommissionDetail::where('pph_id', $CMSId)->whereBetween('created_at', [$start, $end])->sum('commission');

        return view('pph.dashboard', $data);
    }
}
