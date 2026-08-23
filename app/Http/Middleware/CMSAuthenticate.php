<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CMSAuthenticate
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::guard('cms')->user()) {

            if (Auth::guard('cms')->user()->status == 0) {
                Auth::guard('cms')->logout();
                return redirect()->route('cms.login')->with('error', 'Your account is inactive.');
            }
        }
        if (!Auth::guard('cms')->check()) {
            return redirect()->route('cms.login');
        } else {
            return $next($request);
        }
    }
}
