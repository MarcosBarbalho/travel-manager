<?php

namespace App\Http\Controllers;

use Illuminate\Http\Resources\Json\JsonResource;

class PingController extends Controller
{
    public function show(): JsonResource
    {
        return new JsonResource(['pong']);
    }
}
