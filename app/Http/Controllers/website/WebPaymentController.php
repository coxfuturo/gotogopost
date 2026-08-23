<?php

namespace App\Http\Controllers\website;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Razorpay\Api\Api;
use Exception;
use Carbon\Carbon;
use App\Models\WebPayment;
use  App\Http\Controllers\franchise\RateCalculator;

class WebPaymentController extends Controller
{
    public function index()
    {
        try {
            // Initialize Razorpay API with environment variables
            $api = new Api(env('RAZORPAY_KEY'), env('RAZORPAY_SECRET'));


            // Pass the created order to the view
            return view('website.pay');
        } catch (Exception $e) {
            // Redirect back with an error message if something goes wrong
            return redirect()->back()->with('error', 'Unable to create Razorpay order: ' . $e->getMessage());
        }
    }

    public function store(Request $request)
    {
        $input = $request->all();

        // Initialize Razorpay API
        $api = new Api(env('RAZORPAY_KEY'), env('RAZORPAY_SECRET'));

        try {
            // Create an initial payment record with status 'pending'
            try {
                $paymentRecord = WebPayment::create([
                    'razorpay_payment_id' => $input['razorpay_payment_id'] ?? null,
                    'amount' => $input['amount'] ?? 0,
                    'status' => 'pending',
                    'method' => 'razorpay',
                ]);
            } catch (\Exception $e) {
                return back()->with('error', 'Error creating payment record: ' . $e->getMessage());
            }

            if (!empty($input['razorpay_payment_id'])) {
                // Fetch the payment details from Razorpay
                try {
                    $payment = $api->payment->fetch($input['razorpay_payment_id']);
                } catch (\Exception $e) {
                    // Handle error fetching payment details from Razorpay
                    return back()->with('error', 'Error fetching payment details from Razorpay: ' . $e->getMessage());
                }

                // Attempt to capture the payment
                try {
                    $response = $payment->capture([
                        'amount' => $payment['amount'],
                    ]);
                } catch (\Exception $e) {
                    // Handle error capturing payment
                    return back()->with('error', 'Error capturing payment: ' . $e->getMessage());
                }

                try {
                    if ($response['status'] == 'captured') {
                        $paymentRecord->update(['status' => 'completed']);
                    } else {
                        $paymentRecord->update(['status' => 'failed']);
                    }
                } catch (\Exception $e) {
                    return back()->with('error', 'Error updating payment status: ' . $e->getMessage());
                }

                return back()->with('success', $paymentRecord->id);
            } else {
                // Update the payment status to 'failed' if payment ID is empty
                try {
                    $paymentRecord->update(['status' => 'failed']);
                } catch (\Exception $e) {
                    return back()->with('error', 'Error updating payment status to failed: ' . $e->getMessage());
                }

                return back()->with('error', 'Payment failed: Payment ID is missing');
            }
        } catch (\Exception $e) {
            // General error catch for any unexpected errors
            return back()->with('error', 'Payment processing failed: ' . $e->getMessage());
        }
    }

    public function printPaymentHistory(Request $request)

    {

        $data = WebPayment::where('id', $request->id)->first();


        $otherPageContent = View('website.layouts.printCommissionDetail', ['data' => $data])->render();

        return response()->json([

            'otherPageContent' => $otherPageContent

        ]);
    }



}
