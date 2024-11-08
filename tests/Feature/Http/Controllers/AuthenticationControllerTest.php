<?php

namespace Tests\Feature\Http\Controllers\V1;

use App\Models\User;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Str;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\ControllerTestCase;

class AuthenticationControllerTest extends ControllerTestCase
{
    public function setUp(): void
    {
        parent::setUp();

        Event::fake();
    }

    public function test_login_endpoint_structure_for_valid_credentials(): void
    {
        $user = User::factory()->create(['password' => $password = Str::random()]);

        $response = $this->post(route('login'), [
            'email' => $user->email,
            'password' => $password,
        ]);

        $this->assertShowStructure($response, $this->expectedStructure());
    }

    #[DataProvider('loginValidationDataProvider')]
    public function test_login_endpoint_structure_for_invalid_request(array $messages, array $params): void
    {
        $response = $this->post(route('login'), $params)->assertUnprocessable();

        foreach ($messages as $field => $errors) {
            $this->assertEqualsCanonicalizing($errors, $response['errors'][$field]);
        }
    }

    public static function loginValidationDataProvider(): array
    {
        return [
            [
                'messages' => [
                    'email' => ['The email field is required.'],
                    'password' => ['The password field is required.'],
                ],
                'params' => [
                    'email' => null,
                    'password' => null,
                ],
            ],
            [
                'messages' => [
                    'email' => ['The email field must be a valid email address.'],
                ],
                'params' => [
                    'email' => Str::random(),
                    'password' => Str::random(),
                ],
            ],
        ];
    }

    public function test_login_endpoint_structure_for_invalid_credentials(): void
    {
        $this->post(route('login'), [
            'email' => $this->faker->safeEmail(),
            'password' => Str::random(),
        ])
            ->assertNotFound();
    }

    public function test_logout_endpoint(): void
    {
        $this->actingAs(User::factory()->create())->get(route('logout'))->assertNoContent();
    }

    public function test_register_endpoint_for_valid_credentials(): void
    {
        $response = $this->post(route('register'), [
            'name' => $this->faker->name(),
            'email' => $this->faker->safeEmail(),
            'password' => $password = Str::random(),
            'password_confirmation' => $password,
        ]);

        $this->assertShowStructure($response, $this->expectedStructure());
    }

    #[DataProvider('registerValidationDataProvider')]
    public function test_register_endpoint_structure_for_invalid_request(array $messages, array $params): void
    {
        $response = $this->post(route('register'), $params)->assertUnprocessable();

        foreach ($messages as $field => $errors) {
            $this->assertEqualsCanonicalizing($errors, $response['errors'][$field]);
        }
    }

    public static function registerValidationDataProvider(): array
    {
        return [
            [
                'messages' => [
                    'name' => ['The name field is required.'],
                    'email' => ['The email field is required.'],
                    'password' => ['The password field is required.'],
                ],
                'params' => [
                    'name' => null,
                    'email' => null,
                    'password' => null,
                    'password_confirmation' => null,
                ],
            ],
            [
                'messages' => [
                    'name' => ['The name field must not be greater than 128 characters.'],
                    'email' => ['The email field must be a valid email address.'],
                    'password' => [
                        'The password field confirmation does not match.',
                        'The password field must be at least 8 characters.',
                    ],
                ],
                'params' => [
                    'name' => Str::random(129),
                    'email' => Str::random(),
                    'password' => Str::random(7),
                    'password_confirmation' => Str::random(7),
                ],
            ],
        ];
    }

    private function expectedStructure(): array
    {
        return [
            'id',
            'expires_at',
            'token',
        ];
    }
}
