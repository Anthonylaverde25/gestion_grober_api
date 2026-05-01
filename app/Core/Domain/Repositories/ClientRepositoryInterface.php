<?php

namespace App\Core\Domain\Repositories;

use App\Core\Domain\Entities\Client;

interface ClientRepositoryInterface
{
    public function findById(string $id): ?Client;
    public function save(Client $client): void;
    public function findByCompany(string $companyId): array;
}
