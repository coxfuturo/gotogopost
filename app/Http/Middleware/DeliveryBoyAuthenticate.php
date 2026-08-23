<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class DeliveryBoyAuthenticate
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::guard('delboy')->user()) {

            if (Auth::guard('delboy')->user()->status == 0) {
                Auth::guard('delboy')->logout();
                return redirect()->route('deliveryBoy.login')->with('error', 'Your account is inactive.');
            }
            return $next($request);
        }
        if (!Auth::guard('delboy')->check()) {
            return redirect()->route('deliveryBoy.login');
        } else {
            return $next($request);
        }
    }
}
