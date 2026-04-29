<?php

namespace App\Core\Domain\Repositories;

use App\Core\Domain\Entities\Furnace;

interface FurnaceRepositoryInterface
{
    public function findById(string $id): ?Furnace;
    public function save(Furnace $furnace): void;
    public function findByCompany(string $companyId): array;
}
