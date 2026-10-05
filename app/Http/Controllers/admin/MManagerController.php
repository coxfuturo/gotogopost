<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\MManager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class MManagerController extends Controller
{
    public function index()
    {
        $managers = MManager::with('managerkyc')
            ->orderBy('id', 'desc')
            ->get();

        return view('admin.m_manager.index', compact('managers'));
    }

    public function create()
    {
        return view('admin.m_manager.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'mobile' => 'required|string|max:20',
            'email' => 'nullable|email|max:255',
            'gst_number' => 'nullable|string|max:50',
            'address' => 'nullable|string',
            'pincode' => 'nullable|string|max:10',
            'city' => 'nullable|string|max:100',
            'district' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
        ]);

        $manager = new MManager();

        $manager->generated_id = 'MM' . time();
        $manager->register_type = 'sales_marketing';
        $manager->customer_no = 'SM' . time();

        $manager->name = $request->name;
        $manager->father_name = $request->father_name;
        $manager->mobile = $request->mobile;
        $manager->gst_number = $request->gst_number;
        $manager->email = $request->email;
        $manager->address = $request->address;
        $manager->pincode = $request->pincode;
        $manager->city = $request->city;
        $manager->district = $request->district;
        $manager->state = $request->state;
        $manager->location = $request->location;

        $manager->status = 1;

        if ($request->filled('password')) {
            $manager->password = Hash::make($request->password);
        }

        $manager->save();

        return redirect()
            ->route('admin.m_manager.index')
            ->with('success', 'Sales Marketing created successfully.');
    }

    public function edit($id)
    {
        $manager = MManager::findOrFail($id);

        return view('admin.m_manager.edit', compact('manager'));
    }

   public function update(Request $request, $id)
{
    $manager = MManager::findOrFail($id);

    $request->validate([
        'name' => 'required|string|max:255',
        'father_name' => 'required|string|max:255',
        'mobile' => 'required|string|max:20',
        'email' => 'nullable|email|max:255',
        'gst_number' => 'nullable|string|max:50',

        'address' => 'nullable|string',
        'pincode' => 'nullable|string|max:10',
        'city' => 'nullable|string|max:100',
        'district' => 'nullable|string|max:100',
        'state' => 'nullable|string|max:100',

        'society_name' => 'nullable|string|max:255',
        'sector' => 'nullable|string|max:100',

        'latitude' => 'nullable',
        'longitude' => 'nullable',

        'status' => 'required|in:0,1',
        'payment_status' => 'required|in:0,1',
    ]);

    // Basic Details
    $manager->name = $request->name;
    $manager->father_name = $request->father_name;
    $manager->mobile = $request->mobile;
    $manager->email = $request->email;
    $manager->gst_number = $request->gst_number;

    // Address Details
    $manager->address = $request->address;
    $manager->pincode = $request->pincode;
    $manager->city = $request->city;
    $manager->district = $request->district;
    $manager->state = $request->state;
    $manager->location = $request->location;

    // Company / Location Details
    $manager->society = $request->society_name;
    $manager->sector = $request->sector;

    // Google Location
    $manager->latitude = $request->latitude;
    $manager->longitude = $request->longitude;

    // Status
    $manager->status = $request->status;
    $manager->payment_status = $request->payment_status;

    // Password - only update if entered
    if ($request->filled('password')) {
        $manager->password = Hash::make($request->password);
    }

    $manager->save();

    return redirect()
        ->route('admin.m_manager.index')
        ->with('success', 'Sales Marketing updated successfully.');
}

    /*
    |--------------------------------------------------------------------------
    | View Manager
    |--------------------------------------------------------------------------
    */

    public function view($id)
    {
        $manager = MManager::with('managerkyc')
            ->findOrFail($id);

        return view('admin.m_manager.view', compact('manager'));
    }

    /*
    |--------------------------------------------------------------------------
    | Update Status
    |--------------------------------------------------------------------------
    */

    public function status(Request $request)
    {
        $request->validate([
            'id' => 'required|integer',
            'status' => 'required|in:0,1',
        ]);

        $manager = MManager::findOrFail($request->id);

        $manager->status = $request->status;
        $manager->save();

        return response()->json([
            'success' => true,
            'message' => 'Status updated successfully',
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Advance Amount
    |--------------------------------------------------------------------------
    */

    public function securityDetails(Request $request, $id)
    {
        $manager = MManager::findOrFail($id);

        return view(
            'admin.m_manager.securityDetails',
            compact('manager')
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Credit Amount
    |--------------------------------------------------------------------------
    */

    public function creditDetails(Request $request, $id)
    {
        $manager = MManager::findOrFail($id);

        return view(
            'admin.m_manager.creditDetails',
            compact('manager')
        );
    }
    public function serviceStatus(Request $request)
{
    $request->validate([
        'manager_id' => 'required|integer',
        'service_type' => 'required|integer',
        'status' => 'required|in:0,1',
    ]);

    $manager = MManager::findOrFail($request->manager_id);

    switch ($request->service_type) {

        case 3:
            $manager->gotogo_business_parcel = $request->status;
            break;

        case 5:
            $manager->india_post_speed = $request->status;
            break;

        case 6:
            $manager->india_post_business = $request->status;
            break;

        default:
            return response()->json([
                'success' => false,
                'message' => 'Invalid service type'
            ]);
    }

    $manager->save();

    return response()->json([
        'success' => true,
        'status' => (string) $request->status,
        'serviceType' => (string) $request->service_type,
    ]);
}

    /*
    |--------------------------------------------------------------------------
    | Delete Manager
    |--------------------------------------------------------------------------
    */

    public function delete($id)
    {
        $manager = MManager::findOrFail($id);

        $manager->delete();

        return redirect()
            ->route('admin.m_manager.index')
            ->with('success', 'Sales Marketing deleted successfully.');
    }
}