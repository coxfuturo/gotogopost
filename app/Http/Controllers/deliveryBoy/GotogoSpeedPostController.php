<?php



namespace App\Http\Controllers\deliveryBoy;



use App\Http\Controllers\Controller;

use App\Models\GotogoSpeedPostParcel;

use App\Models\DeliveryBoy;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;

use Carbon\Carbon;






class GotogoSpeedPostController extends Controller

{

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $serviceStatuses = DeliveryBoy::checkServiceStatus(GotogoSpeedPostParcel::SERVICE_TYPE_GOTO_POST_SPEED);
            if (!$serviceStatuses) {
                return abort(403, 'Service not available.');
            }

            return $next($request);
        });
    }


    public function index(Request $request)

    {
       
        $searchKey = $request->input('searchKey');
        $date = $request->input('date');
        $userGeneratedId = Auth::guard('delboy')->user()->generated_id;
        $id = Auth::guard('delboy')->user()->id;

        $parentFranchiseId = Auth::guard('delboy')->user()->franchise_id;
        $query = GotogoSpeedPostParcel::whereHas('roleUser', function ($query) use ($userGeneratedId, $parentFranchiseId) {
            $query->where('email', $userGeneratedId)
                  ->where('franchise_id', $parentFranchiseId);
        })
        ->orWhere('pickup_boy_id', $id);
         
        if ($searchKey) {

            $query->where(function ($q) use ($searchKey) {

                $q->where('pickup_name', 'LIKE', "%{$searchKey}%")

                    ->orWhere('pickup_mobile', 'LIKE', "%{$searchKey}%")

                    ->orWhere('pickup_email', 'LIKE', "%{$searchKey}%")

                    ->orWhere('pickup_pincode', 'LIKE', "%{$searchKey}%")

                    ->orWhere('pickup_city', 'LIKE', "%{$searchKey}%")

                    ->orWhere('pickup_state', 'LIKE', "%{$searchKey}%")

                    ->orWhere('pickup_address', 'LIKE', "%{$searchKey}%")

                    ->orWhere('consignee_name', 'LIKE', "%{$searchKey}%")

                    ->orWhere('consignee_mobile', 'LIKE', "%{$searchKey}%")

                    ->orWhere('consignee_email', 'LIKE', "%{$searchKey}%")

                    ->orWhere('consignee_pincode', 'LIKE', "%{$searchKey}%")

                    ->orWhere('consignee_city', 'LIKE', "%{$searchKey}%")

                    ->orWhere('consignee_state', 'LIKE', "%{$searchKey}%")

                    ->orWhere('consignee_address', 'LIKE', "%{$searchKey}%")

                    ->orWhere('barcode_no', 'LIKE', "%{$searchKey}%");
            });

            $datas = $query->get();

            return view('deliveryBoy.gotogoSpeedPost.index', compact('datas'));
        }

        // Add date range filtering

        if ($date) {
            $date = Carbon::createFromFormat('d-m-Y', $date)->startOfDay()->toDateString();
            $query->whereDate('created_at', '=', $date);
            $datas = $query->get();

            return view('deliveryBoy.gotogoSpeedPost.index', compact('datas'));
        } else {

            $query->whereDate('created_at', Carbon::today());
            $datas = $query->get();

            return view('deliveryBoy.gotogoSpeedPost.index', compact('datas'));
        }
    }


    public function view($id)

    {

        $data = GotogoSpeedPostParcel::findorfail($id);

        $fuel_charge = $data->fuel_charge;
        $pickup_charge = $data->pickup_charge;
        $other_service_charge = $data->other_service_charge;
        $total_payment_amount = $data->payment_amount;
        $net_price = $total_payment_amount / 1.18;
        $amount = $net_price - ($fuel_charge + $pickup_charge + $other_service_charge);
        $gst = number_format($net_price * 0.18, 2, '.', '');
        $net_price_formatted = number_format($net_price, 2, '.', '');


        $rateDetails = [
            'fuel_charge' => $fuel_charge,
            'pickup_charge' => $pickup_charge,
            'other_service_charge' => $other_service_charge,
            'total_payment_amount' => number_format($total_payment_amount, 2, '.', ''),
            'net_price' => $net_price_formatted,
            'amount' => number_format($amount, 2, '.', ''),
            'gst' => $gst,
        ];

        return view('deliveryBoy.gotogoSpeedPost.view', compact('data', 'rateDetails'));
    }
}
