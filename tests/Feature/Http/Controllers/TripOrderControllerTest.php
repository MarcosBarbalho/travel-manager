<?php

namespace Tests\Feature\Http\Controllers;

use App\Enums\TripOrder\Status;
use App\Models\TripOrder;
use App\Models\User;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Str;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\ControllerTestCase;

class TripOrderControllerTest extends ControllerTestCase
{
    private User $user;

    public function setUp(): void
    {
        parent::setUp();

        Event::fake();

        $this->travelTo(now()->startOfDay());

        $this->user = User::factory()->create();
    }

    public function test_index_endpoint(): void
    {
        TripOrder::factory(random_int(1, 9))->for($this->user)->create();

        $response = $this->actingAs($this->user)->get(route('trip_orders.index'));

        $this->assertSimplePaginatedIndexStructure($response, $this->expectedStructure());
    }

    public function test_show_endpoint(): void
    {
        $response = $this->actingAs($this->user)->get(route('trip_orders.show', [
            'tripOrder' => TripOrder::factory()->for($this->user)->create(),
        ]));

        $this->assertShowStructure($response, $this->expectedStructure());
    }

    public function test_store_endpoint(): void
    {
        $response = $this->actingAs($this->user)->post(route('trip_orders.store'), [
            'departure_at' => now()->format('Y-m-d H:i:s'),
            'return_at' => now()->addDay()->format('Y-m-d H:i:s'),
            'destination' => $this->faker->country(),
            'requester_name' => $this->faker->name(),
            'status' => $this->faker->randomElement(Status::values()),
        ]);

        $this->assertDatabaseCount('trip_orders', 1);
        $this->assertStoreStructure($response, $this->expectedStructure());
    }

    #[DataProvider('storeValidationDataProvider')]
    public function test_store_endpoint_validation(array $messages, array $params): void
    {
        $response = $this->actingAs($this->user)
            ->post(route('trip_orders.store'), $params)
            ->assertUnprocessable();

        foreach ($messages as $field => $errors) {
            $this->assertEqualsCanonicalizing($errors, $response['errors'][$field]);
        }
    }

    public static function storeValidationDataProvider(): array
    {
        return [
            [
                'messages' => [
                    'departure_at' => ['The departure at field is required.'],
                    'destination' => ['The destination field is required.'],
                    'requester_name' => ['The requester name field is required.'],
                ],
                'params' => [
                    'departure_at' => null,
                    'return_at' => null,
                    'destination' => null,
                    'requester_name' => null,
                    'status' => null,
                ],
            ],
            [
                'messages' => [
                    'departure_at' => [
                        'The departure at field must match the format Y-m-d H:i:s.',
                        'The departure at field must be a date after or equal to now.',
                        'The departure at field must be a date before return at.',
                    ],
                    'return_at' => [
                        'The return at field must match the format Y-m-d H:i:s.',
                        'The return at field must be a date after departure at.',
                    ],
                    'destination' => ['The destination field must not be greater than 128 characters.'],
                    'requester_name' => ['The requester name field must not be greater than 128 characters.'],
                    'status' => ['The selected status is invalid.'],
                ],
                'params' => [
                    'departure_at' => Str::random(),
                    'return_at' => Str::random(),
                    'destination' => Str::random(129),
                    'requester_name' => Str::random(129),
                    'status' => Str::random(),
                ],
            ],
            [
                'messages' => [
                    'departure_at' => [
                        'The departure at field must be a date after or equal to now.',
                        'The departure at field must be a date before return at.',
                    ],
                    'return_at' => [
                        'The return at field must be a date after departure at.',
                    ],
                    'destination' => ['The destination field must not be greater than 128 characters.'],
                    'requester_name' => ['The requester name field must not be greater than 128 characters.'],
                    'status' => ['The selected status is invalid.'],
                ],
                'params' => [
                    'departure_at' => now()->subWeek()->format('Y-m-d H:i:s'),
                    'return_at' => now()->subMonth()->format('Y-m-d H:i:s'),
                    'destination' => Str::random(129),
                    'requester_name' => Str::random(129),
                    'status' => Str::random(),
                ],
            ],
        ];
    }

    public function test_update_endpoint(): void
    {
        $tripOrder = TripOrder::factory()->for($this->user)->create();

        $params = [
            'departure_at' => now()->format('Y-m-d H:i:s'),
            'return_at' => now()->addDay()->format('Y-m-d H:i:s'),
            'destination' => $this->faker->country(),
            'requester_name' => $this->faker->name(),
            'status' => $this->faker->randomElement([
                Status::APPROVED->value,
                Status::CANCELED->value,
            ]),
        ];

        $response = $this->actingAs($this->user)->put(route('trip_orders.update', [
            'tripOrder' => $tripOrder,
            ...$params,
        ]));

        $this->assertUpdateStructure($response, $this->expectedStructure());
        $this->assertDatabaseCount('trip_orders', 1);
        $this->assertDatabaseHas('trip_orders', [
            'id' => $tripOrder->id,
            ...$params,
        ]);
    }

    #[DataProvider('updateValidationDataProvider')]
    public function test_update_endpoint_validation(array $messages, array $params): void
    {
        $response = $this->actingAs($this->user)
            ->put(route('trip_orders.update', [
                'tripOrder' => TripOrder::factory()->for($this->user)->create(),
                ...$params,
            ]))
            ->assertUnprocessable();

        foreach ($messages as $field => $errors) {
            $this->assertEqualsCanonicalizing($errors, $response['errors'][$field]);
        }
    }

    public static function updateValidationDataProvider(): array
    {
        return [
            [
                'messages' => [
                    'departure_at' => [
                        'The departure at field must match the format Y-m-d H:i:s.',
                        'The departure at field must be a date after or equal to now.',
                        'The departure at field must be a date before return at.',
                    ],
                    'return_at' => [
                        'The return at field must match the format Y-m-d H:i:s.',
                        'The return at field must be a date after departure at.',
                    ],
                    'destination' => ['The destination field must not be greater than 128 characters.'],
                    'requester_name' => ['The requester name field must not be greater than 128 characters.'],
                    'status' => ['The selected status is invalid.'],
                ],
                'params' => [
                    'departure_at' => Str::random(),
                    'return_at' => Str::random(),
                    'destination' => Str::random(129),
                    'requester_name' => Str::random(129),
                    'status' => Str::random(),
                ],
            ],
            [
                'messages' => [
                    'departure_at' => [
                        'The departure at field must be a date after or equal to now.',
                        'The departure at field must be a date before return at.',
                    ],
                    'return_at' => [
                        'The return at field must be a date after departure at.',
                    ],
                    'destination' => ['The destination field must not be greater than 128 characters.'],
                    'requester_name' => ['The requester name field must not be greater than 128 characters.'],
                    'status' => ['The selected status is invalid.'],
                ],
                'params' => [
                    'departure_at' => now()->subWeek()->format('Y-m-d H:i:s'),
                    'return_at' => now()->subMonth()->format('Y-m-d H:i:s'),
                    'destination' => Str::random(129),
                    'requester_name' => Str::random(129),
                    'status' => Status::REQUESTED->value,
                ],
            ],
        ];
    }

    public function test_delete_endpoint(): void
    {
        $tripOrder = TripOrder::factory()->for($this->user)->create();

        $this->actingAs($this->user)
            ->delete(route('trip_orders.delete', ['tripOrder' => $tripOrder]))
            ->assertNoContent();

        $this->assertSoftDeleted($tripOrder);
    }

    public function test_approve_endpoint(): void
    {
        $tripOrder = TripOrder::factory()
            ->for($this->user)
            ->create(['status' => $this->faker->randomElement([
                Status::REQUESTED,
                Status::CANCELED,
            ])]);

        $response = $this->actingAs($this->user)->patch(route('trip_orders.approve', ['tripOrder' => $tripOrder]));

        $this->assertUpdateStructure($response, $this->expectedStructure());
        $this->assertDatabaseHas('trip_orders', [
            'id' => $tripOrder->id,
            'status' => Status::APPROVED->value,
        ]);
    }

    public function test_cancel_endpoint(): void
    {
        $tripOrder = TripOrder::factory()
            ->for($this->user)
            ->create(['status' => $this->faker->randomElement([
                Status::REQUESTED,
                Status::APPROVED,
            ])]);

        $response = $this->actingAs($this->user)->patch(route('trip_orders.cancel', ['tripOrder' => $tripOrder]));

        $this->assertUpdateStructure($response, $this->expectedStructure());
        $this->assertDatabaseHas('trip_orders', [
            'id' => $tripOrder->id,
            'status' => Status::CANCELED->value,
        ]);
    }

    #[DataProvider('permissionDataProvider')]
    public function test_endpoints_when_attempt_to_access_a_model_from_another_user(
        string $method,
        string $route,
    ): void {
        $this->actingAs($this->user)
            ->{$method}(route($route, [
                'tripOrder' => TripOrder::factory()->for(User::factory()->create())->create(),
            ]))
            ->assertUnauthorized();
    }

    public static function permissionDataProvider(): array
    {
        return [
            ['get', 'trip_orders.show'],
            ['delete', 'trip_orders.delete'],
            ['put', 'trip_orders.update'],
            ['patch', 'trip_orders.approve'],
            ['patch', 'trip_orders.cancel'],
        ];
    }

    private function expectedStructure(): array
    {
        return [
            'created_at',
            'departure_at',
            'destination',
            'id',
            'requester_name',
            'return_at',
            'status',
            'updated_at',
        ];
    }
}
