<?php

namespace Tests\Feature\Http\Controllers\V1;

use App\Actions\Tenants\CreateAndInitializeTenant;
use App\Models\Tenant;
use App\Models\User;
use Database\Factories\ClassFactories\UserClassFactory;
use Database\Factories\UserFactory;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Support\Facades\Event;
use Tests\ControllerTestCase;

class UserControllerTest extends ControllerTestCase
{
    public function setUp(): void
    {
        parent::setUp();

        Event::fake();
    }

    public function test_show_endpoint_structure(): void
    {
        $response = $this->actingAs(User::factory()->create())->get(route('me.user'));

        $this->assertShowStructure($response, [
            'created_at',
            'email',
            'email_verified_at',
            'id',
            'name',
            'updated_at',
        ]);
    }
}