<?php

namespace App\Http\Controllers\CMS;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\CMSRoleUser;
use App\Models\Admin;

class cmsRoleController extends Controller
{
    public function index($id)
    {
        $permission = Permission::where('guard_name', 'cms')->get();

        $permissions = $permission->groupBy(function ($permission) {
            return preg_replace("/-(view|create|edit|delete)$/", '', $permission->name);
        });

        $roles = Role::where('guard_name', 'cms')
            ->orderBy('name', 'asc')
            ->get();

        $rolePermissions = DB::table("role_has_permissions")->where("role_has_permissions.role_id", $id)
            ->pluck('role_has_permissions.permission_id', 'role_has_permissions.permission_id')
            ->all();

        return view('cms.roles.index', compact('permissions', 'roles', 'rolePermissions'));
    }


    public function role_create(Request $request)
    {
        try {
            $this->validate($request, [
                'name' => 'required|unique:roles,name,NULL,id,guard_name,' . $request->guard_name,
            ], [
                'name.required' => 'The role name field is required.',
                'name.unique' => 'The role name already exists.',
            ]);
            Role::create([
                'name' => $request->name,
            ]);

            return back()->with('success', 'Role created successfully');
        } catch (\Exception $th) {
            return back()->with('error', $th->getMessage())->withInput();
        }
    }

    public function role_update(Request $request, $id)
    {
        try {
            $this->validate($request, [
                'name' => 'required',
            ]);

            $role = Role::findOrFail($id);
            $role->name = $request->name;
            $role->save();

            return back()->with('success', 'Role updated successfully');
        } catch (\Exception $th) {
            return back()->with('error', $th->getMessage())->withInput();
        }
    }

    public function role_delete($id)
    {
        try {
            Role::findorfail($id)->delete();
            return back()->with('success', 'Roles Deleted Successfully');
        } catch (\Throwable $th) {
            return back()->with('error', $th->getMessage());
        }
    }

    /****Permission Assign****/

    public function store_permission(Request $request, $id)
    {
        try {
            $role = Role::findorfail($id);
            $role->syncPermissions($request->permissions);
            return back()->with('success', 'Permission assign successfully');
        } catch (\Exception $th) {
            return response()->json(['success' => false, 'message' => $th->getMessage()]);
        }
    }

    //Role users

    public function role_user()
    {
        $roleUsers = CMSRoleUser::orderBy('created_at', 'desc')->get();
        $roles = Role::where('name', '!=', 'admin')
            ->where('guard_name', '=', 'cms')
            ->pluck('name', 'name')
            ->all();
        return view('cms.roles.users.index', compact('roleUsers', 'roles'));
    }

    public function new_role_user(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string|max:191',
            'email' => 'required|email|max:191',
            'role' => 'required|string|max:191',
            'password' => 'required|min:3|confirmed',
            'phone' => 'required|numeric',
        ]);
        // dd($request->phone);
        try {
            $image = Null;
            if ($request->hasFile('image')) {
                $file = $request->file('image');
                $extension = $file->getClientOriginalName();
                $img = time() . '_' . $extension;
                $file->move('tenancy/assets/cms/RoleUser/', $img);
                $image = $img;
            }

            $cmsRoleUser = CMSRoleUser::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'phone' => $request->phone,
                'image' => $image,
            ]);

            $role = Role::where('name', $request->role)->first();

            $cmsRoleUser->roles()->attach($role);

            return back()->with('success', 'New CMS User Added');
        } catch (\Exception $th) {
            return back()->with('error', $th->getMessage());
        }
    }

    public function update_role_user(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string|max:191',
            'email' => 'required|email|max:191',
            'role' => 'required|string|max:191',
            'phone' => 'required|numeric',
        ]);

        try {
            $admin = CMSRoleUser::findorfail($request->id);
            $admin->name = $request->name;
            $admin->phone = $request->phone;
            $admin->email = $request->email;
            $role = Role::where('name', $request->role)->where('guard_name', 'cms')->first();
            $admin->roles()->sync([$role->id]);
            $admin->save();
            return back()->with('success', 'User Updated Successfully');
        } catch (\Throwable $th) {
            return response()->json(['success' => false]);
        }
    }

    public function delete_role_user($id)
    {
        try {
            CMSRoleUser::findorfail($id)->delete();
            return back()->with('success', 'User Deleted Successfully');
        } catch (\Throwable $th) {
            return back()->with('error', $th->getMessage());
        }
    }

    public function status_role_user(Request $request)
    {
        try {
            $admin = CMSRoleUser::findorfail($request->id);
            $status = $request->status;
            $admin->status = $status;
            $admin->save();
            return response()->json(['success' => true, 'status' => $admin->status]);
        } catch (\Throwable $th) {
            return response()->json(['success' => false]);
        }
    }
}
