<?php

namespace App\Http\Controllers\franchise;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\GotogoSpeedPostParcel;
use App\Models\GotogoBusinessParcel;
use App\Models\IndiaPostSpeedPostParcel;
use App\Models\GotogoRegisteredParcel;
use App\Models\FranchiseCommissionDetail;
use App\Models\IndiaPostRegisteredParcel;
use App\Models\IndiaPostBusinessParcel;
use App\Models\Franchise;
use App\Models\DeliveryBoy;
use App\Models\PickupDetails;
use App\Models\Mail;
use Carbon\Carbon;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $franchiseId = Franchise::getFranchiseId();

        $start = $request->start ? Carbon::parse($request->start)->format('Y-m-d') : Carbon::today()->startOfDay();
        $end = $request->end ? Carbon::parse($request->end)->format('Y-m-d') : Carbon::today()->endOfDay();

        $data['gotogolist'] = $this->getGotogoList($franchiseId, $start, $end);
        $data['indiapostlist'] = $this->getIndiaPostList($franchiseId, $start, $end);

        $data['gotogolistList'] = $this->getGotogoParcelCount($franchiseId, $start, $end);
        $data['gotogolistAmount'] = $this->getGotogoPaymentTotal($franchiseId, $start, $end);

        $data['indiapostlistList'] = $this->getIndiaPostParcelCount($franchiseId, $start, $end);
        $data['gotogolistIndiaAmount'] = $this->getIndiaPostPaymentTotal($franchiseId, $start, $end);

        $data['commissionList'] = $this->getCommissionTotal($franchiseId, $start, $end);
        $data['deliveryboy'] = $this->getActiveDeliveryBoyCount($franchiseId);
        $data['pickuplist'] = $this->getPickupCount($franchiseId, $start, $end);

        $data['MailE2e'] = $this->getMailE2E($franchiseId, $start, $end);
        $data['MailE2h'] = $this->getMailE2H($franchiseId, $start, $end);
        $data['totalEmailAmount'] = $this->getMailTotalAmount($franchiseId, $start, $end);
        $data['totalEmailAmounte2h'] = $this->getMailTotalAmounte2h($franchiseId, $start, $end);
        $data['totalMailCount'] = $data['MailE2e'] + $data['MailE2h'];

        $data['totalBalance'] = $this->getMailTotalBalance($franchiseId);

        return view('franchise.dashboard', $data);
    }

    // ----------- Function Section Below (all filtered by date) ------------

    private function getGotogoList($franchiseId, $start, $end)
    {
        $start = Carbon::parse($start)->startOfDay();
        $end = Carbon::parse($end)->endOfDay();

        $perPage = 10;
        $page = request()->get('gotogo_page', 1);

        // Get data from all three tables
        $speedPost = GotogoSpeedPostParcel::where('franchise_id', $franchiseId)
            ->whereBetween('created_at', [$start, $end])
            ->get();

        $businessParcel = GotogoBusinessParcel::where('franchise_id', $franchiseId)
            ->whereBetween('created_at', [$start, $end])
            ->get();

        $registeredParcel = GotogoRegisteredParcel::where('franchise_id', $franchiseId)
            ->whereBetween('created_at', [$start, $end])
            ->get();

        // Combine all collections
        $combined = $speedPost->merge($businessParcel)->merge($registeredParcel)
            ->sortByDesc('created_at')
            ->values();

        // Manual pagination
        $total = $combined->count();
        $offset = ($page - 1) * $perPage;
        $items = $combined->slice($offset, $perPage);

        $gotogoList = new \Illuminate\Pagination\LengthAwarePaginator(
            $items,
            $total,
            $perPage,
            $page,
            [
                'path' => \Illuminate\Pagination\Paginator::resolveCurrentPath(),
                'pageName' => 'gotogo_page',
            ]
        );

        return $gotogoList;
    }

   private function getIndiaPostList($franchiseId, $start, $end)
{
    $start = Carbon::parse($start)->startOfDay();
    $end = Carbon::parse($end)->endOfDay();

    $perPage = 10;
    $page = request()->get('india_post_page', 1);
    
    // DEBUG LOG
    \Log::info("IndiaPost Pagination - Page: $page, Offset: " . (($page - 1) * $perPage));

    
    $paginator = IndiaPostSpeedPostParcel::where('franchise_id', $franchiseId)
        ->whereBetween('created_at', [$start, $end])
        ->orderBy('created_at', 'ASC')
        ->paginate($perPage, ['*'], 'india_post_page');
   
    $paginator->appends(request()->except('india_post_page'));
    
    return $paginator;
}

    private function getGotogoParcelCount($franchiseId, $start, $end)
    {
        $start = Carbon::parse($start)->startOfDay();
        $end = Carbon::parse($end)->endOfDay();

        return GotogoSpeedPostParcel::where('franchise_id', $franchiseId)
            ->whereBetween('created_at', [$start, $end])
            ->count()
            + GotogoBusinessParcel::where('franchise_id', $franchiseId)
                ->whereBetween('created_at', [$start, $end])
                ->count()
            + GotogoRegisteredParcel::where('franchise_id', $franchiseId)
                ->whereBetween('created_at', [$start, $end])
                ->count();
    }

    private function getGotogoPaymentTotal($franchiseId, $start, $end)
    {
        $start = Carbon::parse($start)->startOfDay();
        $end = Carbon::parse($end)->endOfDay();

        return GotogoSpeedPostParcel::where('franchise_id', $franchiseId)
            ->whereBetween('created_at', [$start, $end])
            ->sum('payment_amount')
            + GotogoBusinessParcel::where('franchise_id', $franchiseId)
                ->whereBetween('created_at', [$start, $end])
                ->sum('payment_amount')
            + GotogoRegisteredParcel::where('franchise_id', $franchiseId)
                ->whereBetween('created_at', [$start, $end])
                ->sum('payment_amount');
    }

    private function getIndiaPostParcelCount($franchiseId, $start, $end)
    {
        $start = Carbon::parse($start)->startOfDay();
        $end = Carbon::parse($end)->endOfDay();

        return IndiaPostSpeedPostParcel::where('franchise_id', $franchiseId)
            ->whereBetween('created_at', [$start, $end])
            ->count()
            + IndiaPostBusinessParcel::where('franchise_id', $franchiseId)
                ->whereBetween('created_at', [$start, $end])
                ->count()
            + IndiaPostRegisteredParcel::where('franchise_id', $franchiseId)
                ->whereBetween('created_at', [$start, $end])
                ->count();
    }

    private function getIndiaPostPaymentTotal($franchiseId, $start, $end)
    {
        $start = Carbon::parse($start)->startOfDay();
        $end = Carbon::parse($end)->endOfDay();

        return IndiaPostSpeedPostParcel::where('franchise_id', $franchiseId)
            ->whereBetween('created_at', [$start, $end])
            ->sum('payment_amount')
            + IndiaPostBusinessParcel::where('franchise_id', $franchiseId)
                ->whereBetween('created_at', [$start, $end])
                ->sum('payment_amount')
            + IndiaPostRegisteredParcel::where('franchise_id', $franchiseId)
                ->whereBetween('created_at', [$start, $end])
                ->sum('payment_amount');
    }

    private function getCommissionTotal($franchiseId, $start, $end)
    {
        $start = Carbon::parse($start)->startOfDay();
        $end = Carbon::parse($end)->endOfDay();

        return FranchiseCommissionDetail::where('franchise_id', $franchiseId)
            ->whereBetween('created_at', [$start, $end])
            ->sum('commission');
    }

    private function getActiveDeliveryBoyCount($franchiseId)
    {
        return DeliveryBoy::where('franchise_id', $franchiseId)
            ->where('status', 1)
            ->count();
    }

    private function getPickupCount($franchiseId, $start, $end)
    {
        $start = Carbon::parse($start)->startOfDay();
        $end = Carbon::parse($end)->endOfDay();

        return PickupDetails::where('franchise_id', $franchiseId)
            ->whereBetween('created_at', [$start, $end])
            ->count();
    }

    private function getMailE2E($franchiseId, $start, $end)
    {
        $start = Carbon::parse($start)->startOfDay();
        $end = Carbon::parse($end)->endOfDay();

        return Mail::where('franchise_id', $franchiseId)
            ->whereBetween('created_at', [$start, $end])
            ->where(function ($query) {
                $query->where('service_type', 'mail_to_mail_bulk')
                    ->orWhere('service_type', 'mail_to_mail_single');
            })
            ->count();
    }

    private function getMailE2H($franchiseId, $start, $end)
    {
        $start = Carbon::parse($start)->startOfDay();
        $end = Carbon::parse($end)->endOfDay();

        return Mail::where('franchise_id', $franchiseId)
            ->whereBetween('created_at', [$start, $end])
            ->where(function ($query) {
                $query->where('service_type', 'mail_to_franchise_bulk')
                    ->orWhere('service_type', 'mail_to_franchise_single');
            })
            ->count();
    }

    private function getMailTotalAmount($franchiseId, $start, $end)
    {
        $start = Carbon::parse($start)->startOfDay();
        $end = Carbon::parse($end)->endOfDay();

        return Mail::where('franchise_id', $franchiseId)
            ->where('service_type', 'mail_to_mail_single')
            ->whereBetween('created_at', [$start, $end])
            ->withSum('attachments', 'payment_amount')
            ->get()
            ->sum('attachments_sum_payment_amount');
    }

    private function getMailTotalAmounte2h($franchiseId, $start, $end)
    {
        $start = Carbon::parse($start)->startOfDay();
        $end = Carbon::parse($end)->endOfDay();

        return Mail::where('franchise_id', $franchiseId)
            ->where('service_type', 'mail_to_franchise_single')
            ->whereBetween('created_at', [$start, $end])
            ->withSum('attachments', 'payment_amount')
            ->get()
            ->sum('attachments_sum_payment_amount');
    }

    private function getMailTotalBalance($franchiseId)
    {
        $franchise = Franchise::findOrFail($franchiseId);
        $gotogo_balance = $franchise->gotogo_balance;
        $credit_balance = $franchise->credit_balance;
        return $gotogo_balance + $credit_balance;
    }
	
	
	
	
	
	
	
	
	
	
	
	
}

