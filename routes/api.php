<?php

use App\Http\Controllers\PingController;
use Illuminate\Routing\Router;

/* @var $router Router */

$loginLimiter = config('app.rate_limit.login');

$router->middleware('guest')->group(function (Router $router) use ($loginLimiter) {
    $router->get('/ping', [PingController::class, 'show'])->name('ping.show');

    $router->middleware(["throttle:{$loginLimiter},1"])->group(function (Router $router) {
    });
});
