<?php

use App\Http\Middleware\ForceJsonResponse;
use App\Http\Middleware\NoopMiddleware;
use Illuminate\Auth\Middleware\AuthenticateWithBasicAuth;
use Illuminate\Auth\Middleware\Authorize;
use Illuminate\Auth\Middleware\RequirePassword;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull;
use Illuminate\Foundation\Http\Middleware\TrimStrings;
use Illuminate\Http\Middleware\SetCacheHeaders;
use Illuminate\Http\Request;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Routing\Middleware\ThrottleRequests;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->booting(function () {
        RateLimiter::for(
            'api',
            fn(Request $request) => Limit::perMinute(config('app.rate_limit.default'))
                ->by($request->user()?->id ?: $request->ip())
        );

        Route::middleware('api')->group(base_path('routes/api.php'));
    })
    ->withEvents(false)
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->use([
            ForceJsonResponse::class,
            TrimStrings::class,
            ConvertEmptyStringsToNull::class,
        ]);

        $middleware->group('api', [
            'throttle:api',
            SubstituteBindings::class,
        ]);

        $middleware->alias([
            'auth.basic' => AuthenticateWithBasicAuth::class,
            'cache.headers' => SetCacheHeaders::class,
            'can' => Authorize::class,
            'guest' => NoopMiddleware::class,
            'password.confirm' => RequirePassword::class,
            'throttle' => ThrottleRequests::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {})
    ->create();
