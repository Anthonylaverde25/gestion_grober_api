<?php

namespace App\Core\Application\UseCases\Auth;

use App\Core\Domain\Entities\User;
use App\Core\Domain\Repositories\AuthRepositoryInterface;
use App\Core\Domain\ValueObjects\Email;
use InvalidArgumentException;

class AuthenticateUser
{
    public function __construct(
        private AuthRepositoryInterface $authRepository
    ) {}

    public function execute(string $email, string $password): User
    {
        $user = $this->authRepository->validateCredentials(new Email($email), $password);

        if (!$user) {
            throw new InvalidArgumentException("Credenciales inválidas.");
        }

        if (!$user->isActive()) {
            throw new InvalidArgumentException("La cuenta de usuario está desactivada.");
        }

        return $user;
    }
}
