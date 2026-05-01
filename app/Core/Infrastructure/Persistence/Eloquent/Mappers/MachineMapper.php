<?php

namespace App\Core\Infrastructure\Persistence\Eloquent\Mappers;

use App\Core\Domain\Entities\Machine as DomainMachine;
use App\Models\Machine as EloquentMachine;

class MachineMapper
{
    public static function toDomain(EloquentMachine $eloquent): DomainMachine
    {
        // El nombre del artículo puede venir directamente de la máquina o de la campaña activa
        $articleName = $eloquent->currentArticle?->name ?? $eloquent->currentCampaign?->article?->name;

        return DomainMachine::reconstitute(
            $eloquent->id,
            $eloquent->company_id,
            $eloquent->furnace_id,
            $eloquent->current_article_id,
            $eloquent->name,
            $eloquent->current_status,
            $articleName,
            $eloquent->current_campaign_id,
            $eloquent->currentCampaign?->client?->commercial_name
        );
    }

    public static function toEloquent(DomainMachine $domain): array
    {
        return [
            'id' => $domain->getId(),
            'company_id' => $domain->getCompanyId(),
            'furnace_id' => $domain->getFurnaceId(),
            'current_article_id' => $domain->getCurrentArticleId(),
            'name' => $domain->getName(),
            'current_status' => $domain->getStatus(),
            'current_campaign_id' => $domain->getCurrentCampaignId(),
        ];
    }
}
