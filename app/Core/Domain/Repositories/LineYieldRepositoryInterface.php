<?php

namespace App\Core\Domain\Repositories;

use App\Core\Domain\Entities\LineYield;

interface LineYieldRepositoryInterface
{
    public function findById(string $id): ?LineYield;
    public function save(LineYield $lineYield): void;
    public function findByCampaign(string $campaignId): array;
    public function findByMachine(string $machineId, ?int $limit = null): array;
}
