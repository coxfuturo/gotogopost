<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use App\Models\User;

class ApiUserAuthenticate
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  \Illuminate\Http\Request  $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle($request, Closure $next)
    {

        try {
            Auth::shouldUse('apiuser');
            if (!$user = JWTAuth::parseToken()->authenticate()) {
                if (!$user || !User::where('id', $user->id)->exists()) {
                    return response()->json([
                        'success' => false,
                        "deleted" => true,
                        'message' => 'User Not Found',
                        'showMessage' => 1
                    ], 401);
                }
            }

            // Check user status (assuming you have a 'status' field in your user model)
            if ($user->status == 0) {
                return response()->json([
                    'success' => false,
                    "active" => false,
                    'message' => 'Your account is inactive.',
                    'showMessage' => 1
                ], 403);
            }

            if ($user->status == 2) {
                return response()->json([
                    'success' => false,
                    "deleted" => true,
                    'message' => 'User Not Found',
                    'showMessage' => 1
                ], 403);
            }


            // If all checks pass, continue to the next request
            return $next($request);
        } catch (JWTException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Token is invalid or expired',
                'showMessage' => 1,
                'error' => $e->getMessage()
            ], 401);
        }
    }
}
