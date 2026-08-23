<?php

namespace App\Http\Controllers\franchise;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\FranchiseCommissionDetail;
use App\Models\Franchise;
use App\Models\GotogoSpeedPostParcel;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class FranchiseCommissionController extends Controller
{

    public function index(Request $request, RateCalculator $rateCalculator)
    {
        
        $id = Franchise::getFranchiseId();

        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        if ($startDate && $endDate) {
            $startDate = \Carbon\Carbon::parse($startDate)->startOfDay();
            $endDate = \Carbon\Carbon::parse($endDate)->endOfDay();
            $data = FranchiseCommissionDetail::where('franchise_id', $id)
                ->selectRaw('
                id, 
                franchise_id, 
                service_type, 
                created_at, 
                SUM(amount) as total_amount, 
                SUM(commission) as total_commission,
                SUM(CASE WHEN LOWER(payment_method) = "prepaid" THEN amount ELSE 0 END) as prepaid_amount,
                SUM(CASE WHEN LOWER(payment_method) = "cod" THEN amount ELSE 0 END) as cod_amount,
                SUM(CASE WHEN LOWER(payment_method) = "prepaid" THEN commission ELSE 0 END) as prepaid_commission,
                SUM(CASE WHEN LOWER(payment_method) = "cod" THEN commission ELSE 0 END) as cod_commission
            ')
                ->whereBetween('created_at', [$startDate, $endDate])
                ->groupBy('service_type')
                ->get();
        } else {
            $today = Carbon::today();
            $data = FranchiseCommissionDetail::where('franchise_id', $id)
                ->selectRaw('
                id, 
                franchise_id, 
                service_type, 
                created_at, 
                SUM(amount) as total_amount, 
                SUM(commission) as total_commission,
                SUM(CASE WHEN LOWER(payment_method) = "prepaid" THEN amount ELSE 0 END) as prepaid_amount,
                SUM(CASE WHEN LOWER(payment_method) = "cod" THEN amount ELSE 0 END) as cod_amount,
                SUM(CASE WHEN LOWER(payment_method) = "prepaid" THEN commission ELSE 0 END) as prepaid_commission,
                SUM(CASE WHEN LOWER(payment_method) = "cod" THEN commission ELSE 0 END) as cod_commission
            ')
                ->whereDate('created_at', $today)
                ->groupBy('service_type')
                ->get();
        }

        // Calculate India Post commissions
        $indiaPostSpeedPostCommission = $rateCalculator->calculateCommissionForIndiaPostByRevenue(5, $id, $startDate, $endDate);
        $indiaPostBusinessCommission = $rateCalculator->calculateCommissionForIndiaPostByRevenue(6, $id, $startDate, $endDate);

        foreach ($data as &$entry) {
            $entry->service_type = intval($entry->service_type);
            if ($entry->service_type == 5) {
                $entry->total_commission = $indiaPostSpeedPostCommission['commission'];
                $entry->total_amount = $indiaPostSpeedPostCommission['amount'];
            } elseif ($entry->service_type == 6) {
                $entry->total_commission = $indiaPostBusinessCommission['commission'];
                $entry->total_amount = $indiaPostBusinessCommission['amount'];
            }
        }

        // Ensure all service types are present
        $allServiceTypes = [1, 3, 4, 5, 6, 9];
        $dataMap = $data->keyBy('service_type');

        foreach ($allServiceTypes as $serviceType) {
            if (!isset($dataMap[$serviceType])) {
                $data->push((object)[
                    'id' => null,
                    'franchise_id' => $id,
                    'service_type' => $serviceType,
                    'created_at' => null,
                    'total_amount' => "0",
                    'total_commission' => "0",
                    'prepaid_amount' => "0",
                    'cod_amount' => "0",
                    'prepaid_commission' => "0",
                    'cod_commission' => "0",
                    'service_name' => GotogoSpeedPostParcel::getServiceType($serviceType),
                ]);
            }
        }

        foreach ($data as &$entry) {
            $entry->service_name = GotogoSpeedPostParcel::getServiceType($entry->service_type);
        }

        // Split data into groups
        $gotogoCommission = [];
        $indiaPostCommission = [];
        $totalGotogoCommission = 0;
        $totalIndiaPostCommission = 0;

        foreach ($data as &$entry) {
            if (in_array($entry->service_type, [5, 6])) {
                $indiaPostCommission[] = $entry;
                $totalIndiaPostCommission += (float) $entry->total_commission;
            } else {
                $gotogoCommission[] = $entry;
                $totalGotogoCommission += (float) $entry->total_commission;
            }
        }
// return $indiaPostCommission;
        $franchiseDetails = Franchise::findOrFail($id);

        return view('franchise.dailyBookingReport.commission.index', compact(
            'gotogoCommission',
            'indiaPostCommission',
            'totalGotogoCommission',
            'totalIndiaPostCommission',
            'franchiseDetails'
        ));
    }
    public function printCommissionDetail(Request $request, RateCalculator $rateCalculator)
    {
        $id = Franchise::getFranchiseId();

        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        if ($startDate && $endDate) {
            $startDate = \Carbon\Carbon::parse($startDate)->startOfDay();
            $endDate = \Carbon\Carbon::parse($endDate)->endOfDay();
            $data = FranchiseCommissionDetail::where('franchise_id', $id)
                ->selectRaw('id, franchise_id, service_type, created_at, SUM(amount) as total_amount, SUM(commission) as total_commission')
                ->whereBetween('created_at', [$startDate, $endDate])
                ->groupBy('service_type')
                ->get();
        } else {
            $today = Carbon::today();
            $data = FranchiseCommissionDetail::where('franchise_id', $id)
                ->selectRaw('id, franchise_id, service_type, created_at, SUM(amount) as total_amount, SUM(commission) as total_commission')
                ->whereDate('created_at', $today)
                ->groupBy('service_type')
                ->get();
        }

        // India Post Commission Calculation
        $indiaPostSpeedPostCommission = $rateCalculator->calculateCommissionForIndiaPostByRevenue(5, $id, $startDate ?? null, $endDate ?? null);
        $indiaPostBusinessCommission = $rateCalculator->calculateCommissionForIndiaPostByRevenue(6, $id, $startDate ?? null, $endDate ?? null);

        foreach ($data as &$entry) {
            $entry->service_type = intval($entry->service_type);
            if ($entry->service_type == 5) {
                $entry->total_commission = $indiaPostSpeedPostCommission['commission'];
                $entry->total_amount = $indiaPostSpeedPostCommission['amount'];
            } elseif ($entry->service_type == 6) {
                $entry->total_commission = $indiaPostBusinessCommission['commission'];
                $entry->total_amount = $indiaPostBusinessCommission['amount'];
            }
        }

        // Missing service types ko zero data dena
        $allServiceTypes = [1, 3, 4, 5, 6, 9];
        $dataMap = $data->keyBy('service_type');

        foreach ($allServiceTypes as $serviceType) {
            if (!isset($dataMap[$serviceType])) {
                $data->push((object)[
                    'id' => null,
                    'franchise_id' => $id,
                    'service_type' => $serviceType,
                    'created_at' => null,
                    'total_amount' => "0",
                    'total_commission' => "0",
                    'service_name' => GotogoSpeedPostParcel::getServiceType($serviceType),
                ]);
            }
        }

        foreach ($data as &$entry) {
            $entry->service_name = GotogoSpeedPostParcel::getServiceType($entry->service_type);
        }

        // Data ko do groups me split karna
        $gotogoCommission = [];
        $indiaPostCommission = [];
        $totalGotogoCommission = 0;
        $totalIndiaPostCommission = 0;

        foreach ($data as &$entry) {
            if (in_array($entry->service_type, [5, 6])) {
                $indiaPostCommission[] = $entry;
                $totalIndiaPostCommission += (float) $entry->total_commission;
            } else {
                $gotogoCommission[] = $entry;
                $totalGotogoCommission += (float) $entry->total_commission;
            }
        }

        // GST and TDS Calculation
        $gstRate = 18;
        $tdsRate = 5;



        $gstGotogo = ($totalGotogoCommission * $gstRate) / 100;
        $tdsGotogo = ($totalGotogoCommission * $tdsRate) / 100;
        $totalGotogo = $totalGotogoCommission + $gstGotogo - $tdsGotogo;


        $gstIndiaPost = ($totalIndiaPostCommission * $gstRate) / 100;
        $tdsIndiaPost = ($totalIndiaPostCommission * $tdsRate) / 100;
        $totalIndiaPost = $totalIndiaPostCommission + $gstIndiaPost - $tdsIndiaPost;

        $franchiseDetails = Franchise::findOrFail($id);

        // Same data pass for printing
        $otherPageContent = View('franchise.commission.printCommissionDetail', compact(
            'gotogoCommission',
            'indiaPostCommission',
            'totalGotogoCommission',
            'totalIndiaPostCommission',
            'totalGotogo',
            'totalIndiaPost',
            'gstGotogo',
            'gstIndiaPost',
            'tdsGotogo',
            'tdsIndiaPost',
            'franchiseDetails'
        ))->render();

        return response()->json([
            'otherPageContent' => $otherPageContent
        ]);
    }
}
