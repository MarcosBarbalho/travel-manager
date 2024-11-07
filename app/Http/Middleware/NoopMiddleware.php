<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class NoopMiddleware
{
    public function handle(Request $request, Closure $next): JsonResponse
    {
        return $next($request);
    }
}
