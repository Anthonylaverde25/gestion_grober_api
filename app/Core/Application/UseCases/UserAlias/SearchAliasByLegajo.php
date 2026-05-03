<?php

namespace App\Core\Application\UseCases\UserAlias;

use App\Core\Domain\Entities\UserAlias;
use App\Core\Domain\Repositories\UserAliasRepositoryInterface;

class SearchAliasByLegajo
{
    public function __construct(
        private UserAliasRepositoryInterface $userAliasRepository
    ) {}

    public function execute(string $legajo): ?UserAlias
    {
        return $this->userAliasRepository->findByLegajo($legajo);
    }
}
