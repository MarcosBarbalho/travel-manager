<?php

namespace Tests;

use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Foundation\Testing\WithFaker;

abstract class TestCase extends BaseTestCase
{
    use LazilyRefreshDatabase;
    use WithFaker;

    public function actingAs($user, $guard = null)
    {
        $token = auth()->login($user);

        $this->withHeader('Authorization', "Bearer {$token}");

        parent::actingAs($user);

        return $this;
    }
}
