<?php

namespace App\Core\Domain\Repositories;

use App\Core\Domain\Entities\UserAlias;

interface UserAliasRepositoryInterface
{
    public function findById(string $id): ?UserAlias;

    public function findByLegajo(string $legajo): ?UserAlias;

    /**
     * @return UserAlias[]
     */
    public function findByUser(int $userId): array;

    public function save(UserAlias $userAlias): void;

    public function delete(string $id): void;
}
