<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class FranchiseAuthenticate
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, Closure $next)
    {
        // Check if franchise user is authenticated
        if (Auth::guard('franchise')->check()) {
            $franchiseUser = Auth::guard('franchise')->user();

            // Check franchise user status
            if ($franchiseUser->status == 0) {
                Auth::guard('franchise')->logout();
                return redirect()->route('franchise.login')->with('error', 'Your account is inactive.');
            }

            return $next($request);
        }

        // Check if role user is authenticated
        if (Auth::guard('franchiseRoleUser')->check()) {

            $franchiseRoleUser = Auth::guard('franchiseRoleUser')->user();
            if ($franchiseRoleUser->status == 0) {
                Auth::guard('franchiseRoleUser')->logout();
                return redirect()->route('franchise.login')->with('error', 'Your account is inactive.');
            }
            return $next($request);
        }

        // Redirect to franchise login if no user is authenticated
        return redirect()->route('franchise.login')->with('error', 'You are not authenticated.');
    }
}
