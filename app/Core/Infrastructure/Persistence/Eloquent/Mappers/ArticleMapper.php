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
            $eloquent->name
        );
    }

    public static function toEloquent(DomainArticle $domain): array
    {
        return [
            'id' => $domain->getId(),
            'company_id' => $domain->getCompanyId(),
            'name' => $domain->getName(),
        ];
    }
}
