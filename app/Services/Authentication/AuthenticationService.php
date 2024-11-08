<?php

namespace App\Services\Authentication;

use App\Models\User;
use Carbon\Carbon;

class AuthenticationService
{
    public function __construct(
        private readonly User $user,
        private readonly ?int $expiresAtInMinutes = null,
        private readonly bool $refresh = false,
    ) {
    }

    public function token(): string
    {
        $expiresAtInMinutes = is_null($this->expiresAtInMinutes)
            ? config('jwt.ttl')
            : now()->diffInMinutes(Carbon::createFromTimestamp($this->expiresAtInMinutes));

        $token = auth()
            ->setTTL($expiresAtInMinutes)
            ->claims(['tenant_id' => $this->user->tenant_id]);

        if ($this->refresh) {
            return $token->refresh();
        }

        return $token->login($this->user);
    }
}
