<?php

namespace App\Core\Domain\Repositories;

use App\Core\Domain\Entities\Company;

interface CompanyRepositoryInterface
{
    public function findById(string $id): ?Company;
    public function save(Company $company): void;
    public function findByConsortium(string $consortiumId): array;
    public function all(): array;
}
