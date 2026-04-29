<?php

namespace App\Core\Application\UseCases\Machine;

use App\Core\Domain\Repositories\MachineRepositoryInterface;

class ListMachinesByFurnace
{
    public function __construct(
        private MachineRepositoryInterface $machineRepository
    ) {}

    public function execute(string $furnaceId): array
    {
        return $this->machineRepository->findByFurnace($furnaceId);
    }
}
