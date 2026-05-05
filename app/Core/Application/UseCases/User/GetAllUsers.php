<?php

namespace App\Core\Application\UseCases\User;

use App\Core\Domain\Repositories\UserRepositoryInterface;

class GetAllUsers
{
    public function __construct(
        private UserRepositoryInterface $userRepository
    ) {}

    public function execute(): array
    {
        return $this->userRepository->findAll();
    }
}
