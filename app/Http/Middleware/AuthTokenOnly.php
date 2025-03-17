<?php

namespace App\Http\Middleware;

use App\Http\Resources\ApiResource;
use App\Traits\HandleExceptions;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AuthTokenOnly
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
            return response()->json(new ApiResource(null, 401, 'Unauthorized'), 401);
        }

        try {
            list($prefix, $token) = explode(' ', $accessToken);

            if ($prefix !== 'Bearer' && !$token) {
                return response()->json(new ApiResource(null, 401, 'Unauthorized'), 401);
            }
        } catch (\Exception $e) {
            $error = $this->handleException($e);
            return response()->json(new ApiResource(null, $error['status'], $error['message']), $error['status']);
        }

        return $next($request);
    }
}
