<?php

namespace App\Core\Infrastructure\Persistence\Eloquent;

use App\Core\Domain\Entities\Campaign as DomainCampaign;
use App\Core\Domain\Repositories\CampaignRepositoryInterface;
use App\Core\Infrastructure\Persistence\Eloquent\Mappers\CampaignMapper;
use App\Models\Campaign as EloquentCampaign;

class EloquentCampaignRepository implements CampaignRepositoryInterface
{
    public function findById(string $id): ?DomainCampaign
    {
        $eloquent = EloquentCampaign::with(['client', 'article', 'machine'])->find($id);
        return $eloquent ? CampaignMapper::toDomain($eloquent) : null;
    }

    public function save(DomainCampaign $campaign): void
    {
        EloquentCampaign::updateOrCreate(
            ['id' => $campaign->getId()],
            CampaignMapper::toEloquent($campaign)
        );
    }

    public function findActiveByMachine(string $machineId): ?DomainCampaign
    {
        $eloquent = EloquentCampaign::with(['client', 'article', 'machine'])
            ->where('machine_id', $machineId)
            ->where('status', 'ACTIVE')
            ->first();

        return $eloquent ? CampaignMapper::toDomain($eloquent) : null;
    }

    public function findByCompany(string $companyId): array
    {
        return EloquentCampaign::with(['client', 'article', 'machine'])
            ->where('company_id', $companyId)
            ->get()
            ->map(fn($item) => CampaignMapper::toDomain($item))
            ->toArray();
    }
}
