<?php

namespace App\Providers;

use App\Http\Resources\ApiResource;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        RateLimiter::for('limiter', function (Request $request) {
            return Limit::perSecond(5, 15)
                ->by($request->ip())
                ->response(function (Request $request, array $headers) {
                    return response()->json(new ApiResource(null, 429, 'To many request'), 429);
                });
        });
    }
}
