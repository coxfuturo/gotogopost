<?php

namespace App\Http\Controllers\PPH;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Razorpay\Api\Api;
use Exception;
use Carbon\Carbon;
use App\Models\PphPayment;
use App\Models\PPHCommissionDetail;
use App\Models\PPH;
use App\Models\GotogoSpeedPostParcel;
use Illuminate\Support\Facades\Auth;


class PphPaymentController extends Controller
{

    public function index()
    {

        try {
            // Initialize Razorpay API with environment variables
            $api = new Api(env('RAZORPAY_KEY'), env('RAZORPAY_SECRET'));


            // Pass the created order to the view
            return view('pph.payment.index');
        } catch (Exception $e) {
            // Redirect back with an error message if something goes wrong
            return redirect()->back()->with('error', 'Unable to create Razorpay order: ' . $e->getMessage());
        }
    }

    public function store(Request $request)
    {
        // return auth()->user()->id;
        $input = $request->all();

        // Initialize Razorpay API
        $api = new Api(env('RAZORPAY_KEY'), env('RAZORPAY_SECRET'));

        try {
            // Create an initial payment record with status 'pending'
            try {
                $paymentRecord = PphPayment::create([
                    'pph_id' => auth()->user()->id,
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
            // General error catch for any unexpected errors
            return back()->with('error', 'Payment processing failed: ' . $e->getMessage());
        }
    }

    public function paymentHistory(Request $request)
    {

        try {
            // Initialize the Razorpay API client
            $api = new Api(env('RAZORPAY_KEY'), env('RAZORPAY_SECRET'));

            // Fetch the payment from Razorpay (use the ID from request or fallback)
            $paymentId = $request->get('payment_id', 'pay_PdnWKeKaGNOYy9'); // Example fallback ID
            $payment = $api->payment->fetch($paymentId);

            // Get franchise payment history
            $paymentHistory = PphPayment::where('pph_id', auth()->user()->id);

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
            return view('pph.payment.paymentHistory', compact('paymentHistory'));
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

        $data = PphPayment::where('id', $request->id)->first();

        $franchiseDetails = PPH::findOrFail(auth()->user()->id);


        $otherPageContent = View('pph.payment.printCommissionDetail', ['data' => $data, 'franchiseDetails' => $franchiseDetails])->render();

        return response()->json([

            'otherPageContent' => $otherPageContent

        ]);
    }



    public function commissionIndex(Request $request)
    {

        $id = Auth::guard('pph')->user()->id;
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $serviceTypes = [1, 3, 4]; // Allowed service types

        if ($startDate && $endDate) {
            $startDate = Carbon::parse($startDate)->startOfDay();
            $endDate = Carbon::parse($endDate)->endOfDay();
        } else {
            $startDate = Carbon::today()->startOfDay();
            $endDate = Carbon::today()->endOfDay();
        }

        // Fetch commission details for existing service types
        $commissionData = PPHCommissionDetail::where('pph_id', $id)
            ->whereIn('service_type', $serviceTypes)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->selectRaw('service_type, COALESCE(SUM(amount), 0) as total_amount, COALESCE(SUM(commission), 0) as total_commission, MAX(created_at) as created_at')
            ->groupBy('service_type')
            ->get()
            ->keyBy('service_type'); // Index by service_type for easy lookup

        // Ensure all service types are present with 0 values if missing
        $data = collect($serviceTypes)->map(function ($serviceType) use ($commissionData) {
            return (object) [
                'service_type' => GotogoSpeedPostParcel::getServiceType($serviceType),
                'total_amount' => $commissionData[$serviceType]->total_amount ?? 0,
                'total_commission' => $commissionData[$serviceType]->total_commission ?? 0,
                'created_at' => $commissionData[$serviceType]->created_at ?? null, // Avoid error on created_at
            ];
        });

        $franchiseDetails = PPH::findOrFail($id);

        return view('pph.payment.commissionIndex', compact('data', 'franchiseDetails'));
    }

    public function printCommissionDetail(Request $request)
    {

        $id = Auth::guard('pph')->user()->id;
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $serviceTypes = [1, 3, 4]; // Allowed service types
        $gstRate = 18; // GST percentage
        $tdsRate = 5; // TDS percentage

        if ($startDate && $endDate) {
            $startDate = \Carbon\Carbon::parse($startDate)->startOfDay();
            $endDate = \Carbon\Carbon::parse($endDate)->endOfDay();
        } else {
            $startDate = Carbon::today()->startOfDay();
            $endDate = Carbon::today()->endOfDay();
        }

        $data = PPHCommissionDetail::where('pph_id', $id)
            ->whereIn('service_type', $serviceTypes)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->selectRaw('SUM(commission) as total_commission')
            ->first();

        $totalCommission = $data->total_commission ?? 0;

        // GST and TDS Calculation
        $gstAmount = ($totalCommission * $gstRate) / 100;
        $tdsAmount = ($totalCommission * $tdsRate) / 100;
        $totalAmount = $totalCommission + $gstAmount - $tdsAmount;

        // Fetch PPH Details
        $pphDetails = PPH::findOrFail($id);

        // Render View
        $otherPageContent = View('pph.payment.printCommissionDetail', [
            'pphDetails' => $pphDetails,
            'data' => [
                'commission' => number_format($totalCommission, 2),
                'gst' => number_format($gstAmount, 2),
                'tds' => number_format($tdsAmount, 2),
                'total' => number_format($totalAmount, 2),
            ]
        ])->render();

        return response()->json([
            'otherPageContent' => $otherPageContent
        ]);
    }
}
