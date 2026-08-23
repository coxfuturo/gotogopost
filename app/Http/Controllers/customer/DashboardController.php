<?php

namespace App\Http\Controllers\customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ECustomer;
use App\Models\GotogoBusinessParcel;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index(){
        $id= Auth::guard('customer')->id();
        $query = GotogoBusinessParcel::where('cod_customer_id', $id)

            ->where('insert_type', 1)
                
            ->orderBy('created_at', 'desc');
              $query->where('payment_method', 'cod');

            $query->whereDate('created_at', Carbon::today());
            $datas = $query->count();

            $query->where('payment_method', 'prepaid');
            $prepaid = $query->count();

        return view('customer.dashboard',compact('datas','prepaid'));
    }

    public function prepaid(){
        // return 000;
        $id= Auth::guard('prepaid')->id();
        $query = GotogoBusinessParcel::where('cod_customer_id', $id)

            ->where('insert_type', 1)
                
            ->orderBy('created_at', 'desc');
              $query->where('payment_method', 'cod');

            $query->whereDate('created_at', Carbon::today());
            $datas = $query->count();

            $query->where('payment_method', 'prepaid');
            $prepaid = $query->count();

        return view('prepaid.dashboard',compact('datas','prepaid'));
    }
}
