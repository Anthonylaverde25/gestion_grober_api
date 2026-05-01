<?php

namespace App\Core\Infrastructure\Persistence\Eloquent;

use App\Core\Domain\Entities\Client as DomainClient;
use App\Core\Domain\Repositories\ClientRepositoryInterface;
use App\Core\Infrastructure\Persistence\Eloquent\Mappers\ClientMapper;
use App\Models\Client as EloquentClient;

class EloquentClientRepository implements ClientRepositoryInterface
{
    public function findById(string $id): ?DomainClient
    {
        $eloquent = EloquentClient::find($id);
        return $eloquent ? ClientMapper::toDomain($eloquent) : null;
    }

    public function save(DomainClient $client): void
    {
        EloquentClient::updateOrCreate(
            ['id' => $client->getId()],
            ClientMapper::toEloquent($client)
        );
    }

    public function findByCompany(string $companyId): array
    {
        return EloquentClient::where('company_id', $companyId)
            ->get()
            ->map(fn($item) => ClientMapper::toDomain($item))
            ->toArray();
    }
}
