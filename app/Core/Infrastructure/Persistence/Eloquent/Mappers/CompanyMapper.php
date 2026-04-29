<?php

namespace App\Core\Infrastructure\Persistence\Eloquent\Mappers;

use App\Models\Company as EloquentCompany;
use App\Core\Domain\Entities\Company as DomainCompany;

class CompanyMapper
{
    public static function toDomain(EloquentCompany $eloquent): DomainCompany
    {
        return new DomainCompany(
            $eloquent->id,
            $eloquent->consortium_id,
            $eloquent->name,
            $eloquent->manager_id,
            (bool) $eloquent->is_active
        );
    }

    public static function toEloquent(DomainCompany $domain): array
    {
        return [
            'id' => $domain->getId(),
            'consortium_id' => $domain->getConsortiumId(),
            'name' => $domain->getName(),
            'manager_id' => $domain->getManagerId(),
            'is_active' => $domain->isActive(),
        ];
    }
}
