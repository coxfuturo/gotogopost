<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class CheckFranchisePermissions
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, Closure $next, $guard = null)
    {
        
        if (Auth::guard('franchise')->check()) {
            $user = Auth::guard('franchise')->user();
        } elseif (Auth::guard('franchiseRoleUser')->check()) {
            $user = Auth::guard('franchiseRoleUser')->user();
        } else {
            return abort(403, 'Unauthorized.');
        }

        $action = $request->route()->getActionMethod();

        // Define a mapping between action methods and permissions
        $actionToPermissionMap = [
            'index' => 'Dashboard-view',
            'create' => 'Dashboard-create',
            'edit' => 'Dashboard-edit',
            'update' => 'Dashboard-edit',
            'destroy' => 'Dashboard-delete',
            'adminRolesIndex' => 'Admin Role-view',
            'adminRolesCreate' => 'Admin Role-create',
            'adminRolesStore' => 'Admin Role-create',
            'adminRolesEdit' => 'Admin Role-edit',
            'adminRolesUpdate' => 'Admin Role-edit',
            'adminRolesDestroy' => 'Admin Role-delete',
            'franchiseIndex' => 'Franchise-view',
            'franchiseCreate' => 'Franchise-create',
            'franchiseStore' => 'Franchise-create',
            'franchiseEdit' => 'Franchise-edit',
            'franchiseUpdate' => 'Franchise-edit',
            'franchiseDestroy' => 'Franchise-delete',
            'usersIndex' => 'Users-view',
            'usersCreate' => 'Users-create',
            'usersStore' => 'Users-create',
            'usersEdit' => 'Users-edit',
            'usersUpdate' => 'Users-edit',
            'usersDestroy' => 'Users-delete',
            'cmsIndex' => 'cms-view',
            'cmsCreate' => 'cms-create',
            'cmsStore' => 'cms-create',
            'cmsEdit' => 'cms-edit',
            'cmsUpdate' => 'cms-edit',
            'cmsDestroy' => 'cms-delete',
            'kycsIndex' => 'KYCS-view',
            'kycsCreate' => 'KYCS-create',
            'kycsStore' => 'KYCS-create',
            'kycsEdit' => 'KYCS-edit',
            'kycsUpdate' => 'KYCS-edit',
            'kycsDestroy' => 'KYCS-delete',
            'reportsIndex' => 'Reports-view',
            'reportsCreate' => 'Reports-create',
            'reportsStore' => 'Reports-create',
            'reportsEdit' => 'Reports-edit',
            'reportsUpdate' => 'Reports-edit',
            'reportsDestroy' => 'Reports-delete',
            'exportsIndex' => 'Exports-view',
            'exportsCreate' => 'Exports-create',
            'exportsStore' => 'Exports-create',
            'exportsEdit' => 'Exports-edit',
            'exportsUpdate' => 'Exports-edit',
            'exportsDestroy' => 'Exports-delete',
            'importsIndex' => 'Imports-view',
            'importsCreate' => 'Imports-create',
            'importsStore' => 'Imports-create',
            'importsEdit' => 'Imports-edit',
            'importsUpdate' => 'Imports-edit',
            'importsDestroy' => 'Imports-delete',
            'supportTicketsIndex' => 'Support Ticket-view',
            'supportTicketsCreate' => 'Support Ticket-create',
            'supportTicketsStore' => 'Support Ticket-create',
            'supportTicketsEdit' => 'Support Ticket-edit',
            'supportTicketsUpdate' => 'Support Ticket-edit',
            'supportTicketsDestroy' => 'Support Ticket-delete',
            'webSettingsIndex' => 'Web Settings-view',
            'webSettingsCreate' => 'Web Settings-create',
            'webSettingsStore' => 'Web Settings-create',
            'webSettingsEdit' => 'Web Settings-edit',
            'webSettingsUpdate' => 'Web Settings-edit',
            'webSettingsDestroy' => 'Web Settings-delete',
        ];

        // Get the required permission for the current action
        $requiredPermission = $actionToPermissionMap[$action] ?? null;

        if ($requiredPermission && $user->can($requiredPermission)) {
            // Allow access if the user has permission for this action
            return $next($request);
        } else {
            // Abort with 403 Forbidden if user does not have permission
            abort(403, 'You do not have permission to perform this action.');
        }
    }
}
