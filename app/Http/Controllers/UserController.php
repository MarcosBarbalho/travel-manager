<?php

namespace App\Http\Controllers;

use App\Http\Queries\UserQuery;
use App\Http\Resources\UserResource;
use Illuminate\Contracts\Auth\Factory;
use Illuminate\Http\Resources\Json\JsonResource;

class UserController extends Controller
{
    public function __construct(private readonly Factory $auth)
    {
    }

    public function show(UserQuery $query): JsonResource
    {
        return new UserResource(
            $query
                ->where('id', $this->auth->user()->id)
                ->firstOrFail(),
        );
    }
}
