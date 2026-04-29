<?php

namespace App\Core\Infrastructure\Auth;

use App\Core\Domain\Entities\User as DomainUser;
use App\Core\Domain\Services\TokenServiceInterface;
use App\Models\User as EloquentUser;

class SanctumTokenService implements TokenServiceInterface
{
    public function generate(DomainUser $user, string $deviceName): string
    {
        $eloquentUser = EloquentUser::find($user->getId());
        
        return $eloquentUser->createToken($deviceName)->plainTextToken;
    }

    public function revoke(DomainUser $user): void
    {
        $eloquentUser = EloquentUser::find($user->getId());
        $eloquentUser->tokens()->delete();
    }
}
