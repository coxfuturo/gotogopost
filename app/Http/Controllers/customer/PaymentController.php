<?php

namespace App\Http\Controllers\customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function index(){
        return view('customer.payment.index');
    }

    public function recharge_index(){
        return view('customer.payment.recharge');
    }
}
