<?php

namespace App\Core\Infrastructure\Persistence\Eloquent\Mappers;

use App\Models\Article as EloquentArticle;
use App\Core\Domain\Entities\Article as DomainArticle;

class ArticleMapper
{
    public static function toDomain(EloquentArticle $eloquent): DomainArticle
    {
        return DomainArticle::reconstitute(
            $eloquent->id,
            $eloquent->company_id,
            $eloquent->name,
            $eloquent->client_id,
            $eloquent->client ? ClientMapper::toDomain($eloquent->client) : null
        );
    }

    public static function toEloquent(DomainArticle $domain): array
    {
        return [
            'id' => $domain->getId(),
            'company_id' => $domain->getCompanyId(),
            'name' => $domain->getName(),
            'client_id' => $domain->getClientId(),
        ];
    }
}
