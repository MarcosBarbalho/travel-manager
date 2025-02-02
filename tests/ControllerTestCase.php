<?php

namespace Tests;

use Illuminate\Testing\TestResponse;

class ControllerTestCase extends TestCase
{
    public function assertSimplePaginatedIndexStructure(TestResponse $response, array $itemStructure): void
    {
        $response->assertOk();
        $response->assertJsonStructure([
            'data' => [
                '*' => $itemStructure,
            ],
            'links' => [
                'first',
                'last',
                'prev',
                'next',
            ],
            'meta' => [
                'current_page',
                'from',
                'to',
                'path',
                'per_page',
            ],
        ]);
    }

    public function assertShowStructure(TestResponse $response, array $itemStructure): void
    {
        $response->assertOk();
        $response->assertJsonStructure([
            'data' => $itemStructure,
        ]);
    }

    public function assertStoreStructure(TestResponse $response, array $itemStructure): void
    {
        $response->assertCreated();
        $response->assertJsonStructure([
            'data' => $itemStructure,
        ]);
    }

    public function assertUpdateStructure(TestResponse $response, array $itemStructure): void
    {
        $response->assertOk();
        $response->assertJsonStructure([
            'data' => $itemStructure,
        ]);
    }
}
