<?php

namespace Tests\Feature\Http\Controllers\V1;

use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Support\Facades\Event;
use Tests\ControllerTestCase;

class PingControllerTest extends ControllerTestCase
{
    public function setUp(): void
    {
        parent::setUp();

        Event::fake();
    }

    public function test_ping_endpoint_structure(): void
    {
        $response = $this->get(route('ping'));

        $this->assertShowStructure($response, []);
    }
}