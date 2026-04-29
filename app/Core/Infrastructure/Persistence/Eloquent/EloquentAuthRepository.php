<?php

namespace App\Core\Infrastructure\Persistence\Eloquent;

use App\Core\Domain\Entities\User;
use App\Core\Domain\Repositories\AuthRepositoryInterface;
use App\Core\Domain\ValueObjects\Email;
use App\Core\Infrastructure\Persistence\Eloquent\Mappers\UserMapper;
use App\Models\User as EloquentUser;
use Illuminate\Support\Facades\Hash;

class EloquentAuthRepository implements AuthRepositoryInterface
{
    public function validateCredentials(Email $email, string $password): ?User
    {
        $eloquentUser = EloquentUser::where('email', $email->getValue())->first();

        if (!$eloquentUser || !Hash::check($password, $eloquentUser->password)) {
            return null;
        }

        return UserMapper::toDomain($eloquentUser);
    }

    public function findByEmail(Email $email): ?User
    {
        $eloquentUser = EloquentUser::where('email', $email->getValue())->first();
        
        return $eloquentUser ? UserMapper::toDomain($eloquentUser) : null;
    }
}
