<?php

namespace App\Http\Middleware;

use App\Http\Resources\ApiResource;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;
use Symfony\Component\HttpFoundation\Response;

class Authenticate
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $token  = $request->bearerToken();
        if (!$token) {
            return response()->json(new ApiResource(null, 401, 'Unauthorized'), 401);
        }

        try {
            $user = JWTAuth::parseToken()->authenticate();
            if (!$user) {
                return response()->json(new ApiResource(null, 401, 'Unauthorized'), 401);
            }

            $request->merge(['user' => $user]);
        } catch (\Exception $e) {
            return response()->json(new ApiResource(null, 401, 'Unauthorized'), 401);
        }

        return $next($request);
    }
}
