<?php

namespace App\Core\Application\UseCases\Extraction;

use App\Core\Domain\Repositories\ExtractionRepositoryInterface;
use App\Core\Domain\Repositories\MachineRepositoryInterface;
use DomainException;

class GetMachineExtractionHistory
{
    public function __construct(
        private ExtractionRepositoryInterface $extractionRepository,
        private MachineRepositoryInterface $machineRepository
    ) {}

    public function execute(string $machineId, int $limit = 50): array
    {
        $machine = $this->machineRepository->findById($machineId);
        
        if (!$machine) {
            throw new DomainException('La máquina seleccionada no existe.');
        }

        return $this->extractionRepository->findByMachineHistory($machineId, $limit);
    }
}
