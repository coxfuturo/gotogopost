<?php

namespace App\Http\Controllers\api\franchise;

use App\Http\Controllers\Controller;
use App\Models\Franchise;
use App\Models\FranchiseNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use DB;
class DashboardController extends Controller
{


    public function index(Request $request)
    {

        $user = Auth::guard('apifranchise')->user();

        return response()->json([
            'success' => true,
            'message' => 'Welcome to the dashboard',
            'user' => $user
        ], 200);
    }

     // franchise notification 
     public function franchiseNotification(Request $request)
     {
   
        // return FranchiseNotification::get();
         $date = $request->input('date');
 
         $formattedDate = $date ? Carbon::parse($date)->format('Y-m-d') : Carbon::today()->format('Y-m-d');
         $id = Auth::guard('apifranchise')->user()->id;
         $get_notification = FranchiseNotification::where('franchise_id', $id)
             ->whereDate('created_at', $formattedDate)
             ->get();
         return response()->json([
             'status' => 'success',
             'message' => 'Franchise notifications fetched successfully',
             'data' => $get_notification
         ]);
     }
}
