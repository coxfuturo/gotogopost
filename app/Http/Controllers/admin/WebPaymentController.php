<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Razorpay\Api\Api;
use Exception;
use Carbon\Carbon;
use App\Models\WebPayment;

class WebPaymentController extends Controller
{
    public function paymentHistory(Request $request)
    {
       
        try {
            // Initialize the Razorpay API client
            // $api = new Api(env('RAZORPAY_KEY'), env('RAZORPAY_SECRET'));
            $api = new Api('rzp_live_hZ7MLP0RaGm3Dx', 'XVMFy4TcNEkX9Yf2x2nhjUPn');
    
            // Fetch the payment from Razorpay (use the ID from request or fallback)
            // $paymentId = $request->get('payment_id', 'pay_PdnWKeKaGNOYy9'); // Example fallback ID
            // $payment = $api->payment->fetch($paymentId);
    
            // Get franchise payment history
            $paymentHistory = WebPayment::orderBy('id','DESC');
    
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
    
            // Execute the query and get the results
            $paymentHistory = $paymentHistory->get();
    
            // Return the view with the payment history
            return view('admin.payment.webpaymentHistory', compact('paymentHistory'));
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

      $data = WebPayment::where('id', $request->id)->first();
    

        $otherPageContent = View('admin.payment.printCommissionDetail', ['data' => $data])->render();

        return response()->json([

            'otherPageContent' => $otherPageContent

        ]);
    }
}
