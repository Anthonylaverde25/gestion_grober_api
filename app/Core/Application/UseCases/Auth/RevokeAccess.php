<?php

namespace App\Core\Application\UseCases\Auth;

use App\Core\Domain\Entities\User;
use App\Core\Domain\Services\TokenServiceInterface;

class RevokeAccess
{
    public function __construct(
        private TokenServiceInterface $tokenService
    ) {}

    public function execute(User $user): void
    {
        $this->tokenService->revoke($user);
    }
}
