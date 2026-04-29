<?php

namespace App\Core\Application\UseCases\Furnace;

use App\Core\Domain\Repositories\FurnaceRepositoryInterface;

class ListFurnacesByCompany
{
    public function __construct(
        private FurnaceRepositoryInterface $repository
    ) {}

    public function execute(string $companyId): array
    {
        return $this->repository->findByCompany($companyId);
    }
}
