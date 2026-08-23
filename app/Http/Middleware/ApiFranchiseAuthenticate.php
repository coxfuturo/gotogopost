<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Auth;

class ApiFranchiseAuthenticate
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  \Illuminate\Http\Request  $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next)
    {

        // Auth::shouldUse('apifranchise');
        // return response()->json([
        //     'success' => JWTAuth::parseToken()->authenticate(),

        // ]);


        try {
            Auth::shouldUse('apifranchise');
            if (!$user = JWTAuth::parseToken()->authenticate()) {
                // Token is invalid or user not found
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized'
                ], 401);
            }

            // Check user status
            if ($user->status == 0) {
                // Logout the user and return a response indicating the account is inactive
                JWTAuth::invalidate(JWTAuth::getToken());

                return response()->json([
                    'success' => false,
                    'message' => 'Your account is inactive.'
                ], 403);
            }

            // If all checks pass, continue to the next request
            return $next($request);
        } catch (JWTException $e) {
            // Handle token parsing or authentication errors
            return response()->json([
                'success' => false,
                'message' => 'Token is invalid or expired',
                'error' => $e->getMessage()
            ], 401);
        }
    }
}
