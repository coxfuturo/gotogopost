<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    public function __construct()
    {

        $this->middleware(function ($request, $next) {
            if (Auth::guard('admin')->check()) {
                return redirect()->route('admin.dashboard');
            }

            return $next($request);
        })->only('login');
    }
    public function login(Request $request)
    {
        if ($request->method() == 'POST') {

            $this->validate($request, [
                'email' => 'required|email:rfc',
                'password' => 'required'
            ], [
                'email.required' => 'The email address field is required.',
                'email.email' => 'The email must be a valid format.'
            ]);

            if ($admin = Admin::whereemail($request->get('email'))->first()) {
                if (Hash::check($request->get('password'), trim($admin->password))) {
    Auth::guard('admin')->login($admin, $request->get('remember'));
    return redirect()->route('admin.dashboard');
}

            }

            return redirect()->back()->with(['error' => 'Invalid Email Address or Password'])->withInput();
        }
        return view('admin.auth.login');
    }

    public function logout()
    {
        Auth::guard('admin')->logout();

        return redirect()->route('admin.login');
    }

    public function profile(Request $request)
    {
        $data['data'] = Auth::guard('admin')->user();

        if ($request->method() == 'POST') {
            // Get authenticated admin ID
            $id = Auth::guard('admin')->id();

            // Find the admin user
            $admin = Admin::findOrFail($id);

            // Update fields if present in the request
            $admin->name = $request->input('name', $admin->name);
            $admin->phone = $request->input('mobile', $admin->phone);
            $admin->email = $request->input('email', $admin->email);

            // Update password if a new one is provided
            if ($request->filled('password')) {
                $admin->password = bcrypt($request->input('password'));
            }

            // Handle image upload if provided
            if ($request->hasFile('photo')) {
                $image = $request->file('photo');
                $imageName = time() . '_' . $image->getClientOriginalName();
                $imagePath = 'admin/profileImage';

                // Store the image in the specified path
                $image->move(public_path($imagePath), $imageName);
                // Set image path in the database
                $admin->image = $imageName;
            }

            // Save changes to the database
            $admin->save();

            // Return success message or redirect as needed
            return redirect()->back()->with('success', 'Profile updated successfully');
        }

        // Load the profile view with the current data
        return view('admin.auth.profile', $data);
    }
}
