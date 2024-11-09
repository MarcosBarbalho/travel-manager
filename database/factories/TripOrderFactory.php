<?php

namespace Database\Factories;

use App\Enums\TripOrder\Status;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Arr;

class TripOrderFactory extends Factory
{
    public function definition(): array
    {
        return [
            'departure_at' => now()->addDay(),
            'destination' => fake()->country(),
            'requester_name' => fake()->name(),
            'return_at' => now()->addMonth(),
            'status' => Arr::random(Status::cases()),
        ];
    }
}
