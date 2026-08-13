<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Gate::define('administrador', function ($user) {
            return $user->rol === 'Administrador';
        });

        RateLimiter::for('login', function (Request $request) {
            $key = strtolower($request->input('email')).'|'.$request->ip();

            return Limit::perMinute(5)->by($key);
        });

        RateLimiter::for('global', function (Request $request) {
            return Limit::perMinute(30)->by($request->ip());
        });
    }
}
