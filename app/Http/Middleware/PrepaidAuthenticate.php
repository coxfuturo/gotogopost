<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class PrepaidAuthenticate
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::guard('prepaid')->user()) {

            if (Auth::guard('prepaid')->user()->status == 0) {
                Auth::guard('prepaid')->logout();
                return redirect()->route('customer.login')->with('error', 'Your account is inactive.');
            }
            return $next($request);
        }
        if (!Auth::guard('prepaid')->check()) {
            return redirect()->route('customer.login');
        } else {
            return $next($request);
        }
    }
}
