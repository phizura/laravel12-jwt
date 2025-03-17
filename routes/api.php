<?php

use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::prefix('/auth')->middleware(['throttle:limiter'])->controller(AuthController::class)->group(function () {
    Route::post('/register', 'register');
    Route::post('/login', 'login');

//    Route::middleware('auth.cookie')->group(function () {
        Route::post('/refresh', 'refresh')->middleware('auth.token-only');
        Route::middleware(['AuthMiddleware'])->group(function () {
            Route::get('/me', 'me');
            Route::post('/logout', 'logout');
        });
//    });
});
