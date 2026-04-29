<?php

namespace App\Core\Infrastructure\Persistence\Eloquent\Mappers;

use App\Models\Extraction as EloquentExtraction;
use App\Core\Domain\Entities\Extraction as DomainExtraction;

class ExtractionMapper
{
    public static function toDomain(EloquentExtraction $eloquent): DomainExtraction
    {
        return DomainExtraction::reconstitute(
            $eloquent->id,
            $eloquent->machine_id,
            $eloquent->article_id,
            (float) $eloquent->percentage,
            $eloquent->measured_at,
            $eloquent->is_active && is_null($eloquent->deleted_at),
            $eloquent->article?->name
        );
    }

    public static function toEloquent(DomainExtraction $domain): array
    {
        return [
            'id' => $domain->getId(),
            'machine_id' => $domain->getMachineId(),
            'article_id' => $domain->getArticleId(),
            'percentage' => $domain->getPercentage(),
            'measured_at' => $domain->getMeasuredAt(),
            'is_active' => $domain->isActive(),
        ];
    }
}
