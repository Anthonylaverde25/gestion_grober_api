<?php

namespace App\Core\Application\DTOs\Auth;

use App\Core\Domain\Entities\User;

readonly class AuthOutputDTO
{
    public function __construct(
        public User $user,
        public string $token,
        public string $tokenType = 'Bearer'
    ) {}
}
