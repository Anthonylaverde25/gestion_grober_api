<?php

namespace App\Core\Infrastructure\Persistence\Eloquent\Mappers;

use App\Core\Domain\Entities\Client as DomainClient;
use App\Models\Client as EloquentClient;

class ClientMapper
{
    public static function toDomain(EloquentClient $eloquent): DomainClient
    {
        return DomainClient::reconstitute(
            $eloquent->id,
            $eloquent->company_id,
            $eloquent->commercial_name,
            $eloquent->business_name,
            $eloquent->tax_id,
            $eloquent->technical_contact,
            $eloquent->email
        );
    }

    public static function toEloquent(DomainClient $domain): array
    {
        return [
            'id' => $domain->getId(),
            'company_id' => $domain->getCompanyId(),
            'commercial_name' => $domain->getCommercialName(),
            'business_name' => $domain->getBusinessName(),
            'tax_id' => $domain->getTaxId(),
            'technical_contact' => $domain->getTechnicalContact(),
            'email' => $domain->getEmail(),
        ];
    }
}
