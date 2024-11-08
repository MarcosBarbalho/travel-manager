<?php

namespace App\Http\Controllers;

use App\Http\Requests\Authentication\LoginRequest;
use App\Http\Requests\Authentication\RegisterRequest;
use App\Models\User;
use App\Services\Authentication\AuthenticationService;
use Illuminate\Contracts\Auth\Factory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;
use Namshi\JOSE\SimpleJWS;
use Symfony\Component\HttpFoundation\Response;

class AuthenticationController extends Controller
{
    public function __construct(private readonly Factory $auth, public readonly User $user)
    {
    }

    public function login(LoginRequest $request): JsonResource|JsonResponse
    {
        $input = $request->validated();

        $credentials = [
            'email' => $input['email'],
            'password' => $input['password'],
        ];

        $user = $this->user->where('email', $credentials['email'])->first();

        if (! is_null($user) && $this->auth->validate($credentials)) {
            return new JsonResource($this->authenticate($user));
        }

        return response()->json(status: Response::HTTP_NOT_FOUND);
    }

    public function logout(): JsonResponse
    {
        $this->auth->logout();

        return response()->json(status: Response::HTTP_NO_CONTENT);
    }

    public function register(RegisterRequest $request): JsonResource
    {
        return new JsonResource($this->authenticate(
            $this->user->create($request->validated()),
        ));
    }

    private function authenticate(User $user): array
    {
        $service = new AuthenticationService($user);

        $token = $service->token();
        $payload = SimpleJWS::load($token)->getPayload();

        return [
            'id' => $payload['jti'],
            'expires_at' => $payload['exp'],
            'token' => $token,
        ];
    }
}
