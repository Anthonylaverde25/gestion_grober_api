<?php

namespace App\Core\Application\UseCases\UserAlias;

use App\Core\Domain\Entities\UserAlias;
use App\Core\Domain\Repositories\UserAliasRepositoryInterface;
use DomainException;

class ToggleUserAliasStatus
{
    public function __construct(
        private UserAliasRepositoryInterface $userAliasRepository
    ) {}

    public function execute(string $id): UserAlias
    {
        $alias = $this->userAliasRepository->findById($id);

        if (!$alias) {
            throw new DomainException('Alias no encontrado.');
        }

        $alias->toggleActive();
        
        $this->userAliasRepository->save($alias);

        return $alias;
    }
}
