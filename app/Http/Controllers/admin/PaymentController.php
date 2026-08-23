<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\RegistrationPayment;

class PaymentController extends Controller
{
   public function index(){
     $data = RegistrationPayment::find(1);
    return view('admin/payment/paymentAmount',compact('data'));
   }

   public function store(Request $request)
{
    $request->validate([
        'franchise' => 'required|numeric',
        'cph'       => 'required|numeric',
        'pph'       => 'required|numeric',
        'combo'     => 'required|numeric',
    ]);

    $add = RegistrationPayment::find(1);

    if (!$add) {
        return redirect()->back()->with('error', 'Record not found.');
    }

    $add->franchise = $request->franchise;
    $add->cph = $request->cph;
    $add->pph = $request->pph;
    $add->combo = $request->combo;
    $add->save();

    return redirect()->route('admin.payment.amount')->with('success', 'Amount updated successfully!');
}

}
