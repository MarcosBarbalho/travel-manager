<?php

namespace App\Policies;

use App\Models\TripOrder;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TripOrderPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, TripOrder $tripOrder): bool
    {
        return $tripOrder->user()->is($user);
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, TripOrder $tripOrder): bool
    {
        return $tripOrder->user()->is($user);
    }

    public function delete(User $user, TripOrder $tripOrder): bool
    {
        return $tripOrder->user()->is($user);
    }
}
