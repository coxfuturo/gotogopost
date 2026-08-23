<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class PPHAuthenticate
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response

    {

        if (Auth::guard('pph')->user()) {

            if (Auth::guard('pph')->user()->status == 0) {

                Auth::guard('pph')->logout();
                return redirect()->route('pph.login')->with('error', 'Your account is inactive.');
            }

        }

        if (!Auth::guard('pph')->check()) {

            return redirect()->route('pph.login');
        } else {

            return $next($request);
        }
    }
}
