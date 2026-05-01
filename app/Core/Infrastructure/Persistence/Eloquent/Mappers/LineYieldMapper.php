<?php

namespace App\Core\Infrastructure\Persistence\Eloquent\Mappers;

use App\Core\Domain\Entities\LineYield as DomainLineYield;
use App\Models\LineYield as EloquentLineYield;
use DateTimeImmutable;

class LineYieldMapper
{
    public static function toDomain(EloquentLineYield $eloquent): DomainLineYield
    {
        return DomainLineYield::reconstitute(
            $eloquent->id,
            $eloquent->company_id,
            $eloquent->campaign_id,
            (float) $eloquent->forming_yield,
            (float) $eloquent->packing_yield,
            DateTimeImmutable::createFromInterface($eloquent->recorded_at),
            $eloquent->notes
        );
    }

    public static function toEloquent(DomainLineYield $domain): array
    {
        return [
            'id' => $domain->getId(),
            'company_id' => $domain->getCompanyId(),
            'campaign_id' => $domain->getCampaignId(),
            'forming_yield' => $domain->getFormingYield(),
            'packing_yield' => $domain->getPackingYield(),
            'recorded_at' => $domain->getRecordedAt(),
            'notes' => $domain->getNotes(),
        ];
    }
}
