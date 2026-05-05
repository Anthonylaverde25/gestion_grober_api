<?php

namespace App\Core\Domain\Repositories;

use App\Core\Domain\Entities\Campaign;

interface CampaignRepositoryInterface
{
    public function findById(string $id): ?Campaign;
    public function save(Campaign $campaign): void;
    public function findActiveByMachine(string $machineId): ?Campaign;
    public function findByCompany(string $companyId): array;
    public function findAllActive(): array;
}
