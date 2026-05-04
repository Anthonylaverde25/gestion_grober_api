<?php

namespace App\Core\Infrastructure\Persistence\Eloquent;

use App\Core\Domain\Entities\LineYield as DomainLineYield;
use App\Core\Domain\Repositories\LineYieldRepositoryInterface;
use App\Core\Infrastructure\Persistence\Eloquent\Mappers\LineYieldMapper;
use App\Models\LineYield as EloquentLineYield;

class EloquentLineYieldRepository implements LineYieldRepositoryInterface
{
    public function findById(string $id): ?DomainLineYield
    {
        $eloquent = EloquentLineYield::find($id);
        return $eloquent ? LineYieldMapper::toDomain($eloquent) : null;
    }

    public function save(DomainLineYield $lineYield): void
    {
        EloquentLineYield::updateOrCreate(
            ['id' => $lineYield->getId()],
            LineYieldMapper::toEloquent($lineYield)
        );
    }

    public function findByCampaign(string $campaignId): array
    {
        return EloquentLineYield::with('userAlias')
            ->where('campaign_id', $campaignId)
            ->orderBy('recorded_at', 'desc')
            ->get()
            ->map(fn($item) => LineYieldMapper::toDomain($item))
            ->toArray();
    }

    public function findByMachine(string $machineId, ?int $limit = null): array
    {
        $query = EloquentLineYield::with('userAlias')
            ->select('line_yields.*')
            ->join('campaigns', 'line_yields.campaign_id', '=', 'campaigns.id')
            ->where('campaigns.machine_id', $machineId)
            ->whereIn('campaigns.status', ['ACTIVE', 'PAUSED'])
            ->orderBy('line_yields.recorded_at', 'desc');

        if ($limit) {
            $query->limit($limit);
        }

        return $query->get()
            ->map(fn($item) => LineYieldMapper::toDomain($item))
            ->toArray();
    }
}
