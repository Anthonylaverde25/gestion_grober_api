<?php

namespace App\Core\Application\UseCases\User;

use App\Core\Domain\Entities\User;
use App\Core\Domain\Repositories\UserRepositoryInterface;

class CreateUser
{
    private UserRepositoryInterface $repository;

    public function __construct(UserRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function execute(array $data): User
    {
        return $this->repository->create($data);
    }
}
