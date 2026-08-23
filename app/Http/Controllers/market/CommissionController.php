<?php

namespace App\Http\Controllers\market;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\FranchiseCommissionDetail;
use App\Models\Franchise;
use App\Models\MManager;
use App\Models\GotogoSpeedPostParcel;
use App\Models\ManagerCommissionDetail;
use  App\Http\Controllers\market\RateCalculator;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class CommissionController extends Controller
{
    public function index(Request $request, RateCalculator $rateCalculator)
    {
        
        $id = MManager::getManagerId();

        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        if ($startDate && $endDate) {
            $startDate = \Carbon\Carbon::parse($startDate)->startOfDay();
            $endDate = \Carbon\Carbon::parse($endDate)->endOfDay();
            $data = ManagerCommissionDetail::where('commission_id', $id)
                ->selectRaw('
                id, 
                commission_id, 
                servicetype, 
                created_at, 
                SUM(amount) as total_amount, 
                SUM(commission) as total_commission,
                SUM(CASE WHEN LOWER(payment_method) = "prepaid" THEN amount ELSE 0 END) as prepaid_amount,
                SUM(CASE WHEN LOWER(payment_method) = "cod" THEN amount ELSE 0 END) as cod_amount,
                SUM(CASE WHEN LOWER(payment_method) = "prepaid" THEN commission ELSE 0 END) as prepaid_commission,
                SUM(CASE WHEN LOWER(payment_method) = "cod" THEN commission ELSE 0 END) as cod_commission
            ')
                ->whereBetween('created_at', [$startDate, $endDate])
                ->groupBy('servicetype')
                ->get();
        } else {
            $today = Carbon::today();
            $data = ManagerCommissionDetail::where('commission_id', $id)
                ->selectRaw('
                id, 
                commission_id, 
                servicetype, 
                created_at, 
                SUM(amount) as total_amount, 
                SUM(commission) as total_commission,
                SUM(CASE WHEN LOWER(payment_method) = "prepaid" THEN amount ELSE 0 END) as prepaid_amount,
                SUM(CASE WHEN LOWER(payment_method) = "cod" THEN amount ELSE 0 END) as cod_amount,
                SUM(CASE WHEN LOWER(payment_method) = "prepaid" THEN commission ELSE 0 END) as prepaid_commission,
                SUM(CASE WHEN LOWER(payment_method) = "cod" THEN commission ELSE 0 END) as cod_commission
            ')
                ->whereDate('created_at', $today)
                ->groupBy('servicetype')
                ->get();
        }

        // Calculate India Post commissions
        $indiaPostSpeedPostCommission = $rateCalculator->calculateCommissionForIndiaPostByRevenue(5, $id, $startDate, $endDate);
        $indiaPostBusinessCommission = $rateCalculator->calculateCommissionForIndiaPostByRevenue(6, $id, $startDate, $endDate);

        foreach ($data as &$entry) {
            $entry->servicetype = intval($entry->servicetype);
            if ($entry->servicetype == 5) {
                $entry->total_commission = $indiaPostSpeedPostCommission['commission'];
                $entry->total_amount = $indiaPostSpeedPostCommission['amount'];
            } elseif ($entry->servicetype == 6) {
                $entry->total_commission = $indiaPostBusinessCommission['commission'];
                $entry->total_amount = $indiaPostBusinessCommission['amount'];
            }
        }

        // Ensure all service types are present
        $allServiceTypes = [1, 3, 4, 5, 6, 9];
        $dataMap = $data->keyBy('servicetype');

        foreach ($allServiceTypes as $serviceType) {
            if (!isset($dataMap[$serviceType])) {
                $data->push((object)[
                    'id' => null,
                    'franchise_id' => $id,
                    'servicetype' => $serviceType,
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
            $entry->service_name = GotogoSpeedPostParcel::getServiceType($entry->servicetype);
        }

        // Split data into groups
        $gotogoCommission = [];
        $indiaPostCommission = [];
        $totalGotogoCommission = 0;
        $totalIndiaPostCommission = 0;

        foreach ($data as &$entry) {
            if (in_array($entry->servicetype, [5, 6])) {
                $indiaPostCommission[] = $entry;
                $totalIndiaPostCommission += (float) $entry->total_commission;
            } else {
                $gotogoCommission[] = $entry;
                $totalGotogoCommission += (float) $entry->total_commission;
            }
        }
// return $indiaPostCommission;
        $franchiseDetails = MManager::findOrFail($id);

        return view('market.commission.index', compact(
            'gotogoCommission',
            'indiaPostCommission',
            'totalGotogoCommission',
            'totalIndiaPostCommission',
            'franchiseDetails'
        ));
    }


    public function printCommissionDetail(Request $request, RateCalculator $rateCalculator)
    {
        $id = MManager::getManagerId();

        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        if ($startDate && $endDate) {
            $startDate = \Carbon\Carbon::parse($startDate)->startOfDay();
            $endDate = \Carbon\Carbon::parse($endDate)->endOfDay();
            $data = ManagerCommissionDetail::where('commission_id', $id)
                ->selectRaw('id, commission_id, servicetype, created_at, SUM(amount) as total_amount, SUM(commission) as total_commission')
                ->whereBetween('created_at', [$startDate, $endDate])
                ->groupBy('servicetype')
                ->get();
        } else {
            $today = Carbon::today();
            $data = ManagerCommissionDetail::where('commission_id', $id)
                ->selectRaw('id, commission_id, servicetype, created_at, SUM(amount) as total_amount, SUM(commission) as total_commission')
                ->whereDate('created_at', $today)
                ->groupBy('servicetype')
                ->get();
        }

        // India Post Commission Calculation
        $indiaPostSpeedPostCommission = $rateCalculator->calculateCommissionForIndiaPostByRevenue(5, $id, $startDate ?? null, $endDate ?? null);
        $indiaPostBusinessCommission = $rateCalculator->calculateCommissionForIndiaPostByRevenue(6, $id, $startDate ?? null, $endDate ?? null);

        foreach ($data as &$entry) {
            $entry->servicetype = intval($entry->servicetype);
            if ($entry->servicetype == 5) {
                $entry->total_commission = $indiaPostSpeedPostCommission['commission'];
                $entry->total_amount = $indiaPostSpeedPostCommission['amount'];
            } elseif ($entry->servicetype == 6) {
                $entry->total_commission = $indiaPostBusinessCommission['commission'];
                $entry->total_amount = $indiaPostBusinessCommission['amount'];
            }
        }

        // Missing service types ko zero data dena
        $allServiceTypes = [1, 3, 4, 5, 6, 9];
        $dataMap = $data->keyBy('servicetype');

        foreach ($allServiceTypes as $serviceType) {
            if (!isset($dataMap[$serviceType])) {
                $data->push((object)[
                    'id' => null,
                    'franchise_id' => $id,
                    'servicetype' => $serviceType,
                    'created_at' => null,
                    'total_amount' => "0",
                    'total_commission' => "0",
                    'service_name' => GotogoSpeedPostParcel::getServiceType($serviceType),
                ]);
            }
        }

        foreach ($data as &$entry) {
            $entry->service_name = GotogoSpeedPostParcel::getServiceType($entry->servicetype);
        }

        // Data ko do groups me split karna
        $gotogoCommission = [];
        $indiaPostCommission = [];
        $totalGotogoCommission = 0;
        $totalIndiaPostCommission = 0;

        foreach ($data as &$entry) {
            if (in_array($entry->servicetype, [5, 6])) {
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

        $franchiseDetails = MManager::findOrFail($id);

        // Same data pass for printing
        $otherPageContent = View('market.commission.printCommissionDetail', compact(
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
