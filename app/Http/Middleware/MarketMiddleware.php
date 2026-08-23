<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class MarketMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
       if (Auth::guard('market')->user()) {
    if (Auth::guard('market')->user()->status == 0) {
        Auth::guard('market')->logout();
        return redirect()->route('market.login')->with('error', 'Your account is inactive.');
    }
    return $next($request);
}
if (!Auth::guard('market')->check()) {
    return redirect()->route('market.login');
} else {
    return $next($request);
}

    }
}
