<?php

namespace App\Http\Controllers\franchise;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Razorpay\Api\Api;
use Exception;

class ParcelPaymentController extends Controller
{
    
    public function index()
    {
        try {

            return view('franchise.parcel-payment.index');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Unable to create Razorpay order: ' . $e->getMessage());
        }
    }

    public function store(Request $request)
    {
        $input = $request->all();

        $api = new Api(env('RAZORPAY_KEY'), env('RAZORPAY_SECRET'));

        $payment = $api->payment->fetch($input['razorpay_payment_id']);

        if (count($input)  && !empty($input['razorpay_payment_id'])) {

            try {

                $response = $api->payment->fetch($input['razorpay_payment_id'])->capture(array('amount' => $payment['amount']));
            } catch (Exception $e) {

                return back()->with('error', $e->getMessage());
            }
        }

        return back()->with('success', 'Payment Done successfully');
    }

    
}
