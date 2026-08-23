<?php

namespace App\Http\Controllers\admin;

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
use App\Models\CMS;
use App\Models\PPH;
use App\Models\DeliveryBoy;
use App\Models\PPHBag;
use App\Models\MessageFromWebsites;
use App\Models\PPHCommissionDetail;
use App\Models\Mail;
use Carbon\Carbon;


class DashboardController extends Controller
{


    public function index(Request $request)
    {

        $start = $request->start ? Carbon::parse($request->start)->format('Y-m-d') : Carbon::today()->startOfDay();
        $end = $request->end ? Carbon::parse($request->end)->format('Y-m-d') : Carbon::today()->endOfDay();

        $data['gotogoList'] = $this->getGotogoList($start, $end);
        $data['indiapostlist'] = $this->getIndiaPostSpeedPostPaginated($start, $end, $request);

        $data['gotogolistList'] = $this->getGotogoParcelCount($start, $end);
        $data['gotogolistAmount'] = $this->getGotogoPaymentTotal($start, $end);

        $data['indiapostlistList'] = $this->getIndiaPostParcelCount($start, $end);
        $data['gotogolistIndiaAmount'] = $this->getIndiaPostPaymentTotal($start, $end);

        $data['commissionList'] = $this->getCommissionTotal($start, $end);
        $data['deliveryboy'] = $this->getActiveDeliveryBoyCount();


        $data['MailE2e'] = $this->getMailE2E($start, $end);
        $data['MailE2h'] = $this->getMailE2H($start, $end);

        $data['totalEmailAmount'] = $this->getMailTotalAmount($start, $end);
        $data['totalMailCount'] = $data['MailE2e'] + $data['MailE2h'];



        $data['deliveryboy_count'] = DeliveryBoy::count();
        $data['franchise_count'] = Franchise::count();
        $data['cms_count'] = CMS::count();
        $data['pph_count'] = PPH::count();

        return view('admin.dashboard', $data);
    }

    // ----------- Function Section Below (all filtered by date) ------------
    
    /**
 * Get India Post Speed Post with Pagination
 */
private function getIndiaPostSpeedPostPaginated($start, $end, $request)
{
    return IndiaPostSpeedPostParcel::whereBetween('created_at', [$start, $end])
        ->orderBy('created_at', 'desc')
        ->paginate(20) // 20 records per page
        ->appends($request->query()); // Preserve filter parameters in pagination links
}
    
    
    
    
    
    
    
    

    private function getGotogoList($start, $end)
    {

        $start = Carbon::today()->startOfDay();
        $end =  Carbon::today()->endOfDay();

        return GotogoSpeedPostParcel::whereBetween('created_at', [$start, $end])->get()
            ->merge(GotogoBusinessParcel::whereBetween('created_at', [$start, $end])->get())
            ->merge(GotogoRegisteredParcel::whereBetween('created_at', [$start, $end])->get());
    }

    private function getIndiaPostList($start, $end)
    {

        $start = Carbon::today()->startOfDay();
        $end =  Carbon::today()->endOfDay();

        return IndiaPostSpeedPostParcel::whereBetween('created_at', [$start, $end])->get()
            ->merge(IndiaPostBusinessParcel::whereBetween('created_at', [$start, $end])->get())
            ->merge(IndiaPostRegisteredParcel::whereBetween('created_at', [$start, $end])->get());
    }

    private function getGotogoParcelCount($start, $end)
    {
        return GotogoSpeedPostParcel::whereBetween('created_at', [$start, $end])->count()
            + GotogoBusinessParcel::whereBetween('created_at', [$start, $end])->count()
            + GotogoRegisteredParcel::whereBetween('created_at', [$start, $end])->count();
    }

    private function getGotogoPaymentTotal($start, $end)
    {
        return GotogoSpeedPostParcel::whereBetween('created_at', [$start, $end])->sum('payment_amount')
            + GotogoBusinessParcel::whereBetween('created_at', [$start, $end])->sum('payment_amount')
            + GotogoRegisteredParcel::whereBetween('created_at', [$start, $end])->sum('payment_amount');
    }

    private function getIndiaPostParcelCount($start, $end)
    {
        return IndiaPostSpeedPostParcel::whereBetween('created_at', [$start, $end])->count()
            + IndiaPostBusinessParcel::whereBetween('created_at', [$start, $end])->count()
            + IndiaPostRegisteredParcel::whereBetween('created_at', [$start, $end])->count();
    }

    private function getIndiaPostPaymentTotal($start, $end)
    {
        return IndiaPostSpeedPostParcel::whereBetween('created_at', [$start, $end])->sum('payment_amount')
            + IndiaPostBusinessParcel::whereBetween('created_at', [$start, $end])->sum('payment_amount')
            + IndiaPostRegisteredParcel::whereBetween('created_at', [$start, $end])->sum('payment_amount');
    }

    private function getCommissionTotal($start, $end)
    {
        return FranchiseCommissionDetail::whereBetween('created_at', [$start, $end])->sum('commission');
    }

    private function getActiveDeliveryBoyCount()
    {
        return DeliveryBoy::count();
    }

    private function getMailE2E($start, $end)
    {
        return Mail::whereBetween('created_at', [$start, $end])
            ->where(function ($query) {
                $query->where('service_type', 'mail_to_mail_bulk')
                    ->orWhere('service_type', 'mail_to_mail_single');
            })->count();
    }

    private function getMailE2H($start, $end)
    {
        return Mail::whereBetween('created_at', [$start, $end])
            ->where(function ($query) {
                $query->where('service_type', 'mail_to_franchise_bulk')
                    ->orWhere('service_type', 'mail_to_franchise_single');
            })->count();
    }

    private function getMailTotalAmount($start, $end)
    {
        return Mail::whereBetween('created_at', [$start, $end])
            ->withSum('attachments', 'payment_amount')
            ->get()
            ->sum('attachments_sum_payment_amount');
    }





















    //====================================================================================================================

    public function websiteMessageIndex(Request $request)
    {
        $website_message = MessageFromWebsites::orderBy('created_at', 'DESC')->get();
        return view('admin.websiteMessage.index', compact('website_message'));
    }



    public function websiteMessageDelete($id)
    {

        try {
            MessageFromWebsites::findorfail($id)->delete();
            return redirect()->route('websideMessage.index')->with('success', 'Website Message Deleted Successfully');
        } catch (\Exception $th) {
            return back()->with('error', $th->getMessage())->withInput();
        }
    }


    //====================================================================================================================


}
