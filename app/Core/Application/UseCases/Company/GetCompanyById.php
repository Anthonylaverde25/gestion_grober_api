<?php

namespace App\Core\Application\UseCases\Company;

use App\Core\Domain\Entities\Company;
use App\Core\Domain\Repositories\CompanyRepositoryInterface;
use InvalidArgumentException;

class GetCompanyById
{
    public function __construct(
        private CompanyRepositoryInterface $repository
    ) {}

    public function execute(string $id): Company
    {
        $company = $this->repository->findById($id);

        if (!$company) {
            throw new InvalidArgumentException("Empresa no encontrada con el ID: {$id}");
        }

        return $company;
    }
}
