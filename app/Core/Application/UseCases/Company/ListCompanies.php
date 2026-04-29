<?php

namespace App\Core\Application\UseCases\Company;

use App\Core\Domain\Repositories\CompanyRepositoryInterface;

class ListCompanies
{
    public function __construct(
        private CompanyRepositoryInterface $repository
    ) {}

    public function execute(): array
    {
        return $this->repository->all();
    }
}
