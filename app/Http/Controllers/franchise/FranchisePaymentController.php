<?php

namespace App\Http\Controllers\franchise;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Razorpay\Api\Api;
use Exception;
use Carbon\Carbon;
use App\Models\FranchisePayment;
use App\Models\Franchise;
use App\Models\FranchiseCredit;
use Illuminate\Support\Facades\Auth;


class FranchisePaymentController extends Controller
{


    public function index($type)
    {
        try {
            // Initialize Razorpay API with environment variables
            $api = new Api(env('RAZORPAY_KEY'), env('RAZORPAY_SECRET'));


            // Pass the created order to the view
            return view('franchise.franchise-payment.index', compact('type'));
        } catch (Exception $e) {
            // Redirect back with an error message if something goes wrong
            return redirect()->back()->with('error', 'Unable to create Razorpay order: ' . $e->getMessage());
        }
    }


    public function store(Request $request)
    {
        $input = $request->all();

        // Initialize Razorpay API
        $api = new Api('rzp_live_hZ7MLP0RaGm3Dx', 'XVMFy4TcNEkX9Yf2x2nhjUPn');
        // $api = new Api(env('RAZORPAY_KEY'), env('RAZORPAY_SECRET'));

           $amount = $input['amount'];
        try {
            // Create an initial payment record with status 'pending'
            try {
                $paymentRecord = FranchisePayment::create([
                    'franchise_id' => Auth::guard('franchise')->user()->id,
                    'razorpay_payment_id' => $input['razorpay_payment_id'] ?? null,
                    'amount' => $amount ?? 0,
                    'status' => 'pending',
                    'type' => $input['type'],
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
                    return back()->with('error', 'Error fetching payment details from Razorpay: ' . $e->getMessage());
                }

                // Attempt to capture the payment
                try {
                    $response = $payment->capture([
                        'amount' => $payment['amount'],
                    ]);
                } catch (\Exception $e) {
                    return back()->with('error', 'Error capturing payment: ' . $e->getMessage());
                }

                try {
                    if ($response['status'] == 'captured') {
                        $paymentRecord->update(['status' => 'completed']);

                        // Update franchise balance
                        $user = Auth::guard('franchise')->user();

                        if ($input['type'] === 'gotogo') {
                            $user->gotogo_balance += $amount; // Fixed variable name
                        }

                        if ($input['type'] === 'indiapost') {
                            $user->indiapost_balance += $amount; // Fixed variable name
                        }

                        $user->save(); // Save the updated balance

                    } else {
                        $paymentRecord->update(['status' => 'failed']);
                    }
                } catch (\Exception $e) {
                    return back()->with('error', 'Error updating payment status: ' . $e->getMessage());
                }

                return back()->with('success', 'Payment done successfully');
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
            return back()->with('error', 'Payment processing failed: ' . $e->getMessage());
        }
    }






    public function paymentHistory(Request $request)
    {
        try {

            // Get franchise payment history
            $paymentHistory = FranchisePayment::where('franchise_id', Auth::guard('franchise')->user()->id);

            // Filter by start date if provided in the request
            if ($request->has('start_date') && !empty($request->start_date)) {
                $startDate = Carbon::parse($request->start_date)->startOfDay(); // Convert to start of the day
                $paymentHistory = $paymentHistory->where('created_at', '>=', $startDate);
            }

            // Filter by end date if provided in the request
            if ($request->has('end_date') && !empty($request->end_date)) {
                $endDate = Carbon::parse($request->end_date)->endOfDay(); // Convert to end of the day
                $paymentHistory = $paymentHistory->where('created_at', '<=', $endDate);
            }
            $paymentHistory->orderBy('id','desc');
            // Execute the query and get the results
            $paymentHistory = $paymentHistory->get();

            // Return the view with the payment history
            return view('franchise.franchise-payment.paymentHistory', compact('paymentHistory'));
        } catch (\Razorpay\ApiError $e) {
            // Handle Razorpay API specific exceptions
            return response()->json([
                'success' => false,
                'message' => 'Razorpay API Error: ' . $e->getMessage(),
            ]);
        } catch (Exception $e) {
            // Handle any general exceptions
            return response()->json([
                'success' => false,
                'message' => 'Error fetching payment: ' . $e->getMessage(),
            ]);
        }
    }


    public function printPaymentHistory(Request $request)

    {

        $data = FranchisePayment::where('id', $request->id)->first();

        $franchiseDetails = Franchise::findOrFail(Auth::guard('franchise')->user()->id);


        $otherPageContent = View('franchise.franchise-payment.printCommissionDetail', ['data' => $data, 'franchiseDetails' => $franchiseDetails])->render();

        return response()->json([

            'otherPageContent' => $otherPageContent

        ]);
    }

    public function creditPayment(Request $request)
    {
        $id= Auth::guard('franchise')->user()->id;

        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $franchiseDetails = Franchise::findOrFail($id);
        if ($startDate && $endDate) {

            $startDate = \Carbon\Carbon::parse($startDate)->startOfDay();
            $endDate = \Carbon\Carbon::parse($endDate)->endOfDay();
            $data = FranchiseCredit::where('franchise_id', $id)
                ->whereBetween('created_at', [$startDate, $endDate])
                ->orderBy('created_at', 'desc')
                ->get();
        } else {
            $today = Carbon::today();
            $data = FranchiseCredit::where('franchise_id', $id)
                ->orderBy('created_at', 'desc')
                ->get();
        }

        return view('franchise.franchise-payment.creditPayment', compact('data', 'franchiseDetails'));
    }

   

}
