<?php

namespace App\Core\Domain\Repositories;

use App\Core\Domain\Entities\Machine;

interface MachineRepositoryInterface
{
    public function findById(string $id): ?Machine;
    public function save(Machine $machine): void;
    public function findByCompany(string $companyId): array;
    public function findByFurnace(string $furnaceId): array;
}
