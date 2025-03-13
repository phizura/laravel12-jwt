<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Resources\ApiResource;
use App\Models\User;
use App\Traits\HandleExceptions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    use HandleExceptions;

    public function register(RegisterRequest $request)
    {
        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => bcrypt($request->password),
            ]);

            return response()->json(new ApiResource($user, 201, 'Success registration'), 201);
        } catch (\Exception $e) {
            $error = $this->handleException($e);

            return response()->json(new ApiResource(null, $error['status'], $error['message']), $error['status']);
        }
    }

    public function login(LoginRequest $request)
    {
        $credentials = $request->only(['email', 'password']);

        $token = Auth::attempt($credentials);
        if (!$token) {
            return response()->json(new ApiResource(null, 401, 'Unauthorized'), 401);
        }

        return response()->json(new ApiResource($token, 200, 'Login success'));
    }

    public function logout()
    {
        Auth::logout();
        return response()->json(new ApiResource(null, 200, 'Logout success'));
    }

    public function me(Request $request)
    {
        try {
            $user = $request->input('user');
            return response()->json(new ApiResource($user, 200, 'Success get user profile'));
        } catch (\Exception $e) {
            $error = $this->handleException($e);
            return response()->json(new ApiResource(null, $error['status'], $error['message']), $error['status']);
        }
    }

    public function refresh()
    {
        try {
            $token = JWTAuth::parseToken()->refresh();
            return response()->json(new ApiResource($token, 200, 'Login success'));
        } catch (\Exception $e) {
            return response()->json(new ApiResource(null, 401, 'Token expired and cannot be refreshed anymore'), 401);
        }
    }
}
