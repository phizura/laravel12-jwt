<?php

namespace App\Http\Middleware;

use App\Http\Resources\ApiResource;
use App\Traits\HandleExceptions;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PHPOpenSourceSaver\JWTAuth\Exceptions\JWTException;
use PHPOpenSourceSaver\JWTAuth\Exceptions\TokenExpiredException;
use PHPOpenSourceSaver\JWTAuth\Exceptions\TokenInvalidException;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;
use Symfony\Component\HttpFoundation\Response;

class Authenticate
{

    use HandleExceptions;
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $accessToken = $request->header('Authorization');

        if (!$accessToken) {
            return response()->json(new ApiResource(null, 403, 'Unauthorized'), 403);
        }

        try {
            list($prefix, $token) = explode(' ', $accessToken);

            if ($prefix !== 'Bearer' && !$token) {
                return response()->json(new ApiResource(null, 403, 'Unauthorized'), 403);
            }

            if (!$user = JWTAuth::parseToken()->authenticate()) {
                return response()->json(new ApiResource(null, 403, 'User not found or token invalid'), 403);
            }
        } catch (\Exception $e) {
            $error = $this->handleException($e);
            return response()->json(new ApiResource(null, $error['status'], $error['message']), $error['status']);
        }

        return $next($request);
    }
}
