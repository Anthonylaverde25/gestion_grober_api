<?php

namespace App\Core\Application\UseCases\Machine;

use App\Core\Domain\Repositories\MachineRepositoryInterface;

class ListMachinesByCompany
{
    public function __construct(
        private MachineRepositoryInterface $machineRepository
    ) {}

    public function execute(string $companyId): array
    {
        return $this->machineRepository->findByCompany($companyId);
    }
}
