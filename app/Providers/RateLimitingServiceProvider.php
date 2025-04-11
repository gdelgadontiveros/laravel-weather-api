<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Support\Facades\RateLimiter;

class RateLimitingServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services. config('quotes.request_max')
     */
    public function boot(): void
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(config('ratelimiting.api.limit'))->by($request->user()?->id ?: $request->ip());
        });
    
        // Límite más estricto para endpoints de autenticación
        RateLimiter::for('auth', function (Request $request) {
            return Limit::perMinute(config('ratelimiting.auth.limit'))->by($request->ip());
        });
    
        // Límite especial para consultas climáticas
        RateLimiter::for('weather', function (Request $request) {
            return Limit::perMinute(config('ratelimiting.weather.limit'))->by($request->user()?->id ?: $request->ip());
        });
    }
}
