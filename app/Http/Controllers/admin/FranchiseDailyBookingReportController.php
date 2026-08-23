<?php



namespace App\Http\Controllers\admin;



use App\Http\Controllers\Controller;

use App\Models\Parcel;

use App\Models\Franchise;

use Illuminate\Http\Request;

use Carbon\Carbon;

use App\Models\GotogoSpeedPostParcel;
use App\Models\GotogoSuperFastParcel;
use App\Models\GotogoBusinessParcel;
use App\Models\GotogoRegisteredParcel;
use App\Models\IndiaPostSpeedPostParcel;
use App\Models\IndiaPostBusinessParcel;
use App\Models\IndiaPostRegisteredParcel;





class FranchiseDailyBookingReportController extends Controller

{


    public function allFranchise(Request $request)
    {
        // Get all franchises
        $allFranchises = Franchise::all();

        // Map service type numbers to model classes
        $models = [
            GotogoSpeedPostParcel::getServiceType(1) => GotogoSpeedPostParcel::class,
            GotogoSuperFastParcel::getServiceType(2) => GotogoSuperFastParcel::class,
            GotogoBusinessParcel::getServiceType(3) => GotogoBusinessParcel::class,
            GotogoRegisteredParcel::getServiceType(4) => GotogoRegisteredParcel::class,
            IndiaPostSpeedPostParcel::getServiceType(5) => IndiaPostSpeedPostParcel::class,
            IndiaPostBusinessParcel::getServiceType(6) => IndiaPostBusinessParcel::class,
            IndiaPostRegisteredParcel::getServiceType(7) => IndiaPostRegisteredParcel::class,
        ];

        // Initialize the summary data
        $summaryData = [];
        foreach ($models as $serviceType => $model) {
            $summaryData[$serviceType] = [
                'service_type' => $serviceType,
                'No_of_article' => 0,
                'total_value' => 0,
                'wallet_balance' => 0,
                'remaining_balance' => 0,
            ];
        }

        if ($request->date) {
            $date = Carbon::createFromFormat('d-m-Y', $request->date)->startOfDay();
        } else {
            $date = Carbon::today()->startOfDay();
        }


        // Loop through all franchises and aggregate data
        foreach ($allFranchises as $franchise) {
            foreach ($models as $serviceType => $model) {
                $summaryData[$serviceType]['No_of_article'] += $model::where('franchise_id', $franchise->id)
                    ->whereDate('created_at', $date)
                    ->count();
                $summaryData[$serviceType]['total_value'] += $model::where('franchise_id', $franchise->id)
                    ->whereDate('created_at', $date)
                    ->sum('payment_amount');
                $summaryData[$serviceType]['wallet_balance'] += $franchise->wallet_balance;
                $summaryData[$serviceType]['remaining_balance'] += $franchise->remaining_balance;
            }
        }

        // Prepare data in the required format
        $data = [];
        $serialno = 1;
        foreach ($summaryData as $serviceType => $values) {
            $data[] = [
                'serial_no' => $serialno,
                'service_type' => $values['service_type'],
                'No_of_article' => $values['No_of_article'],
                'total_value' => $values['total_value'],
                'wallet_balance' => $values['wallet_balance'],
                'remaining_balance' => $values['remaining_balance'],
            ];
            $serialno++;
        }

        if ($request->date) {
            return response()->json(['status' => 200, 'message' => 'filter data succesfull', 'data' => $data]);
        }
        // Return the view with the data
        return view('admin.daily-booking-report.index', ['data' => $data]);
    }



    public static function eachFranchise($id)
    {
        $franchise_id = $id;
        $franchiseDetails = Franchise::where('id', $franchise_id)->first();

        // Map service type numbers to model classes
        $models = [
            GotogoSpeedPostParcel::getServiceType(1) => GotogoSpeedPostParcel::class,
            GotogoSuperFastParcel::getServiceType(2) => GotogoSuperFastParcel::class,
            GotogoBusinessParcel::getServiceType(3) => GotogoBusinessParcel::class,
            GotogoRegisteredParcel::getServiceType(4) => GotogoRegisteredParcel::class,
            IndiaPostSpeedPostParcel::getServiceType(5) => IndiaPostSpeedPostParcel::class,
            IndiaPostBusinessParcel::getServiceType(6) => IndiaPostBusinessParcel::class,
            IndiaPostRegisteredParcel::getServiceType(7) => IndiaPostRegisteredParcel::class,
        ];

        $data = [];
        $serialno = 1; // Initialize serial number

        foreach ($models as $serviceType => $model) {
            $data[$serialno] = [
                'No_of_article' => $model::where('franchise_id', $franchise_id)
                    ->whereDate('created_at', Carbon::today())
                    ->count(),
                'total_value' => $model::where('franchise_id', $franchise_id)
                    ->whereDate('created_at', Carbon::today())
                    ->sum('payment_amount'),
                'service_type' => $serviceType,
                'wallet_balance' => $franchiseDetails->wallet_balance,
                'remaining_balance' => $franchiseDetails->remaining_balance,
            ];

            $serialno++; // Increment serial number for next entry
        }

        return $data;
    }



    public static function eachFranchisefilterbyDate($id, $date)
    {
        $franchise_id = $id;
        $franchiseDetails = Franchise::where('id', $franchise_id)->first();

        $date = Carbon::createFromFormat('d-m-Y', $date)->startOfDay();

        // Map service type numbers to model classes
        $models = [
            GotogoSpeedPostParcel::getServiceType(1) => GotogoSpeedPostParcel::class,
            GotogoSuperFastParcel::getServiceType(2) => GotogoSuperFastParcel::class,
            GotogoBusinessParcel::getServiceType(3) => GotogoBusinessParcel::class,
            GotogoRegisteredParcel::getServiceType(4) => GotogoRegisteredParcel::class,
            IndiaPostSpeedPostParcel::getServiceType(5) => IndiaPostSpeedPostParcel::class,
            IndiaPostBusinessParcel::getServiceType(6) => IndiaPostBusinessParcel::class,
            IndiaPostRegisteredParcel::getServiceType(7) => IndiaPostRegisteredParcel::class,
        ];

        $data = [];
        $serialno = 1; // Initialize serial number

        foreach ($models as $serviceType => $model) {
            $data[$serialno] = [
                'No_of_article' => $model::where('franchise_id', $franchise_id)
                    ->whereDate('created_at', $date)
                    ->count(),
                'total_value' => $model::where('franchise_id', $franchise_id)
                    ->whereDate('created_at', $date)
                    ->sum('payment_amount'),
                'service_type' => $serviceType,
                'wallet_balance' => $franchiseDetails->wallet_balance,
                'remaining_balance' => $franchiseDetails->remaining_balance,
            ];

            $serialno++; // Increment serial number for next entry
        }

        return $data;
    }
}
