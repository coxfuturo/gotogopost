<?php

namespace App\Http\Controllers\franchise;

use App\Http\Controllers\Controller;
use App\Models\Parcel;
use App\Models\Franchise;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DailyBookingReportController extends Controller
{
    public function index(Request $request)
    {

        $franchiseDetails = Franchise::where('id', auth()->id())->first();

        $data = [];
        for ($i = 1; $i < 8; $i++) {
            $data[$i]['No_of_arcticle'] = Parcel::where('franchise_id', auth()->id())->where('service_type', $i)->whereDate('created_at', Carbon::today())->count();
            $data[$i]['total_value'] = Parcel::where('franchise_id', auth()->id())->where('service_type', $i)->whereDate('created_at', Carbon::today())->sum('payment_amount');
            $data[$i]['service_type'] =  Parcel::getServiceType($i);
            $data[$i]['wallet_balance'] =  $franchiseDetails->wallet_balance;
            $data[$i]['remaining_balance'] =  $franchiseDetails->remaining_balance;
        }

        return view('franchise.dailyBookingReport.index', ['data' => $data]);
    }
}
