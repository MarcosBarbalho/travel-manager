<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::factory()->createMany([
            [
                'name' => 'Internal',
                'email' => 'a@a.com',
            ],
            [
                'name' => 'Internal 2',
                'email' => 'b@a.com',
            ],
        ]);
    }
}
