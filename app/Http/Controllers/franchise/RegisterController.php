<?php

namespace App\Http\Controllers;

use App\Models\Franchise;
use App\Models\FranchiseKyc;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\Models\Role;

class RegisterController extends Controller
{
    public function registerFranchise(Request $request)
    {
        // ✅ VALIDATION (Form ke according)
        $validated = $request->validate([
            'register_type' => 'required',
            'father_name'   => 'required|string|max:255',
            'mobile'        => 'required|digits:10|unique:franchises,mobile',
            'email'         => 'required|email|unique:franchises,email',
            'pincode'       => 'required|digits:6',
            'city'          => 'required|string',
            'district'      => 'required|string',
            'state'         => 'required|string',
            'address'       => 'required|string',
            'natality'      => 'required|string',
            'age'           => 'required|integer|min:18',
            'gender'        => 'required',
            'society_name'  => 'required|string',
            'sector'        => 'required|string',
            'generated_id'  => 'required|string',

            // KYC
            'adhar_card'    => 'nullable|digits:12',
            'pan_card'      => 'nullable|string',
            'ifsc_code'     => 'nullable|string|max:11',
            'account_number'=> 'nullable|string|max:20',
            'gst_number'    => 'nullable|string|max:20',
        ]);

        DB::beginTransaction();

        try {
            /* ================= FRANCHISE CREATE ================= */

            $franchise = Franchise::create([
                'name'          => $validated['society_name'],
                'email'         => $validated['email'],
                'mobile'        => $validated['mobile'],
                'password'      => Hash::make($validated['mobile']), // default password
                'address'       => $validated['address'],
                'city'          => $validated['city'],
                'state'         => $validated['state'],
                'pincode'       => $validated['pincode'],
                'status'        => 'pending',
            ]);

            // ✅ Assign Role
            if (class_exists(Role::class)) {
                $franchise->assignRole('franchise');
            }

            /* ================= KYC SAVE ================= */

            $kyc = new FranchiseKyc();
            $kyc->franchise_id  = $franchise->id;
            $kyc->father_name   = $validated['father_name'];
            $kyc->district      = $validated['district'];
            $kyc->natality      = $validated['natality'];
            $kyc->age           = $validated['age'];
            $kyc->gender        = $validated['gender'];
            $kyc->adhar_card    = $validated['adhar_card'] ?? null;
            $kyc->pan_card      = $validated['pan_card'] ?? null;
            $kyc->ifsc_code     = $validated['ifsc_code'] ?? null;
            $kyc->bank_name     = $request->bank_name;
            $kyc->branch_name   = $request->branch_name;
            $kyc->account_number= $validated['account_number'] ?? null;
            $kyc->gst_number    = $validated['gst_number'] ?? null;
            $kyc->save();

            /* ================= MULTI DOCUMENT UPLOAD ================= */

            $this->uploadDocuments($request, $franchise->id);

            DB::commit();

            return redirect()->back()->with('success', 'Franchise registered successfully. Awaiting approval.');

        } catch (\Exception $e) {

            DB::rollBack();

            Log::error('Franchise Register Error', [
                'msg' => $e->getMessage(),
                'line'=> $e->getLine()
            ]);

            return response()->json([
    'status' => true,
    'message' => 'Franchise registered successfully',
    'data' => [
        'mobile' => $franchise->mobile,
        'email'  => $franchise->email,
        'id'     => $franchise->id
    ]
], 200);

        }
    }

    /* ================= DOCUMENT UPLOAD HANDLER ================= */

    private function uploadDocuments(Request $request, $franchiseId)
    {
        $docFields = [
            'adhar_front_img',
            'adhar_back_img',
            'pan_img',
            'cheque_img',
            'photo',
            'other_document'
        ];

        foreach ($docFields as $field) {
            if ($request->hasFile($field)) {
                foreach ($request->file($field) as $file) {
                    if ($file) {
                        $file->store("franchise/{$franchiseId}", 'public');
                    }
                }
            }
        }
    }
}
