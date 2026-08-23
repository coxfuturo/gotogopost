<?php

namespace App\Http\Controllers\franchise;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\FranchiseRoleUser;
use App\Models\Admin;
use Illuminate\Support\Facades\Auth;
use App\Models\Franchise;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;



class FranchiseRoleController extends Controller
{



    public function __construct()
    {
        $this->middleware(function ($request, $next) {

            if (Auth::guard('franchise')->check()) {
                $user = Auth::guard('franchise')->user();
            } elseif (Auth::guard('franchiseRoleUser')->check()) {
                $user = Auth::guard('franchiseRoleUser')->user();
            } else {
                return abort(403, 'Unauthorized.');
            }

            $action = $request->route()->getActionMethod();

            $actionToPermissionMap = [
                'index' => 'Role-view',
                'role_create' => 'Role-create',
                'role_update' => 'Role-edit',
                'role_delete' => 'Role-delete',
                'role_user' =>    'Role User-view',
                'new_role_user' => 'Role User-create',
                'update_role_user' => 'Role User-edit',
                'delete_role_user' => 'Role User-delete',
                'status_role_user' => 'Role User-edit',
            ];

            if (!array_key_exists($action, $actionToPermissionMap)) {
                return $next($request);
            }

            if (array_key_exists($action, $actionToPermissionMap)) {
                $requiredPermission = $actionToPermissionMap[$action];
                if (!$user->hasPermissionTo($requiredPermission, 'franchise')) {
                    abort(403, 'You do not have permission to perform this action.');
                }
            }
            return $next($request);
        });
    }






    public function index($id)
    {
        $permission = Permission::where('guard_name', 'franchise')->get();

        // return $permission;
        $permissions = $permission->groupBy(function ($permission) {
            return preg_replace("/-(view|create|edit|delete)$/", '', $permission->name);
        });


        // return  $permissions;

        $roles = Role::where('guard_name', 'franchise')
            ->orderBy('name', 'asc')
            ->get();

        $rolePermissions = DB::table("role_has_permissions")->where("role_has_permissions.role_id", $id)
            ->pluck('role_has_permissions.permission_id', 'role_has_permissions.permission_id')
            ->all();
        return view('franchise.roles.index', compact('permissions', 'roles', 'rolePermissions'));
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
            $firstRole = Role::where('guard_name', 'franchise')->first();
            return redirect()->route('franchise.role.index', $firstRole->id)->with('success', 'Roles Deleted Successfully');
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

        //         DB::statement('
        //     ALTER TABLE `india_post_business_parcels` 
        //     DROP FOREIGN KEY `india_post_business_parcels_franchise_role_user_id_foreign`
        // ');

        // DB::statement('
        //     ALTER TABLE `india_post_business_parcels` 
        //     ADD CONSTRAINT `india_post_business_parcels_franchise_role_user_id_foreign`
        //     FOREIGN KEY (`franchise_role_users_id`) 
        //     REFERENCES `franchise_role_users`(`id`) 
        //     ON DELETE CASCADE
        // ');


        $roleUsers = FranchiseRoleUser::orderBy('created_at', 'desc')->get();
        $roles = Role::where('name', '!=', 'admin')
            ->where('guard_name', '=', 'franchise')
            ->pluck('name', 'name')
            ->all();
        return view('franchise.roles.users.index', compact('roleUsers', 'roles'));
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

        try {
            $image = NULL;
            if ($request->hasFile('image')) {
                $file = $request->file('image');
                $extension = $file->getClientOriginalName();
                $img = time() . '_' . $extension;
                $file->move('tenancy/assets/franchise/RoleUser/', $img);
                $image = $img;
            }

            $franchiseRoleUser = FranchiseRoleUser::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'phone' => $request->phone,
                'image' => $image,
                'franchise_id' => Franchise::getFranchiseId(),
            ]);
            $role = Role::where('name', $request->role)
                ->where('guard_name', '=', 'franchise')
                ->first();
            $franchiseRoleUser->roles()->attach($role);
            return back()->with('success', 'New Francise User Added');
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
            $admin = FranchiseRoleUser::findorfail($request->id);
            $admin->name = $request->name;
            $admin->phone = $request->phone;
            $admin->email = $request->email;
            $role = Role::where('name', $request->role)->where('guard_name', 'franchise')->first();
            $admin->roles()->sync([$role->id]);
            $admin->save();
            return back()->with('success', 'User Updated Successfully');
        } catch (\Throwable $th) {
            return response()->json(['success' => false]);
        }
    }

    public function delete_role_user(Request $request, $id)
    {

        try {
            FranchiseRoleUser::findorfail($id)->delete();
            return back()->with('success', 'User Deleted Successfully');
        } catch (\Throwable $th) {
            return back()->with('error', $th->getMessage());
        }
    }


    public function status_role_user(Request $request)
    {
        try {
            $admin = FranchiseRoleUser::findorfail($request->id);
            $status = $request->status;
            $admin->status = $status;
            $admin->save();
            return response()->json(['success' => true, 'status' => $admin->status]);
        } catch (\Throwable $th) {
            return response()->json(['success' => false]);
        }
    }
}
