<?php

use App\Http\Controllers\AuthenticationController;
use App\Http\Controllers\PingController;
use App\Http\Controllers\TripOrderController;
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

    $router->group(['prefix' => '/trip_orders'], function (Router $router) {
        $router->get('/', [TripOrderController::class, 'index'])->name('trip_orders.index');
        $router->post('/', [TripOrderController::class, 'store'])->name('trip_orders.store');
        $router->group(['prefix' => '/{tripOrder}'], function (Router $router) {
            $router->get('/', [TripOrderController::class, 'show'])->name('trip_orders.show');
            $router->put('/', [TripOrderController::class, 'update'])->name('trip_orders.update');
            $router->delete('/', [TripOrderController::class, 'delete'])->name('trip_orders.delete');
            $router->patch('/approve', [TripOrderController::class, 'approve'])->name('trip_orders.approve');
            $router->patch('/cancel', [TripOrderController::class, 'cancel'])->name('trip_orders.cancel');
        });
    });
});
