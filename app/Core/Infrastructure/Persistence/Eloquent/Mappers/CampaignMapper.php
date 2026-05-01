<?php

namespace App\Core\Infrastructure\Persistence\Eloquent\Mappers;

use App\Core\Domain\Entities\Campaign as DomainCampaign;
use App\Models\Campaign as EloquentCampaign;
use DateTimeImmutable;

class CampaignMapper
{
    public static function toDomain(EloquentCampaign $eloquent): DomainCampaign
    {
        return DomainCampaign::reconstitute(
            $eloquent->id,
            $eloquent->company_id,
            $eloquent->codigo,
            $eloquent->machine_id,
            $eloquent->article_id,
            $eloquent->client_id,
            $eloquent->status,
            DateTimeImmutable::createFromInterface($eloquent->started_at),
            $eloquent->finished_at ? DateTimeImmutable::createFromInterface($eloquent->finished_at) : null,
            $eloquent->total_yield_records ?? 0,
            $eloquent->operator_id,
            $eloquent->client?->commercial_name,
            $eloquent->article?->name,
            $eloquent->relationLoaded('machine') && $eloquent->machine ? MachineMapper::toDomain($eloquent->machine) : null,
            $eloquent->relationLoaded('client') && $eloquent->client ? ClientMapper::toDomain($eloquent->client) : null,
            $eloquent->relationLoaded('article') && $eloquent->article ? ArticleMapper::toDomain($eloquent->article) : null
        );
    }

    public static function toEloquent(DomainCampaign $domain): array
    {
        return [
            'id' => $domain->getId(),
            'company_id' => $domain->getCompanyId(),
            'codigo' => $domain->getCodigo(),
            'machine_id' => $domain->getMachineId(),
            'article_id' => $domain->getArticleId(),
            'client_id' => $domain->getClientId(),
            'status' => $domain->getStatus(),
            'started_at' => $domain->getStartedAt(),
            'finished_at' => $domain->getFinishedAt(),
            'total_yield_records' => $domain->getTotalYieldRecords(),
        ];
    }
}
