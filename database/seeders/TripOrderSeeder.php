<?php

namespace Database\Seeders;

use App\Models\TripOrder;
use App\Models\User;
use Illuminate\Database\Seeder;

class TripOrderSeeder extends Seeder
{
    public function run(): void
    {
        User::all()->each(fn (User $user) => TripOrder::factory(random_int(1, 9))->for($user)->create());
    }
}
