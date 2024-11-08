<?php

use App\Http\Controllers\AuthenticationController;
use App\Http\Controllers\PingController;
use App\Http\Controllers\UserController;
use Illuminate\Routing\Router;

/* @var $router Router */

$loginLimiter = config('app.rate_limit.login');

$router->get('/ping', [PingController::class, 'show'])->name('ping');

$router->middleware('guest')->group(function (Router $router) use ($loginLimiter) {
    $router->middleware(["throttle:{$loginLimiter},1"])->group(function (Router $router) {
        $router->post('/login', [AuthenticationController::class, 'login'])->name('login');

        $router->post('/register', [AuthenticationController::class, 'register'])->name('register');
    });
});

$router->middleware('auth:jwt')->group(function (Router $router) {
    $router->get('/logout', [AuthenticationController::class, 'logout'])->name('logout');

    $router->group(['prefix' => '/me'], function (Router $router) {
        $router->get('/', [UserController::class, 'show'])->name('me.user');
    });
});
