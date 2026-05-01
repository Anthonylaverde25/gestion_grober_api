<?php

namespace App\Core\Application\UseCases\LineYield;

use App\Core\Domain\Repositories\LineYieldRepositoryInterface;

class GetMachineYieldHistory
{
    public function __construct(
        private LineYieldRepositoryInterface $lineYieldRepository
    ) {}

    public function execute(string $machineId, int $limit = 50): array
    {
        return $this->lineYieldRepository->findByMachine($machineId, $limit);
    }
}
