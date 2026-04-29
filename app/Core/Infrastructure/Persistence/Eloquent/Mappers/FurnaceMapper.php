<?php

namespace App\Core\Infrastructure\Persistence\Eloquent\Mappers;

use App\Models\Furnace as EloquentFurnace;
use App\Core\Domain\Entities\Furnace as DomainFurnace;

class FurnaceMapper
{
    public static function toDomain(EloquentFurnace $eloquent): DomainFurnace
    {
        $machines = [];
        if ($eloquent->relationLoaded('machines')) {
            $machines = $eloquent->machines->map(fn($machine) => MachineMapper::toDomain($machine))->toArray();
        }

        return DomainFurnace::reconstitute(
            $eloquent->id,
            $eloquent->company_id,
            $eloquent->glass_type_id,
            $eloquent->name,
            (float) $eloquent->max_capacity_tons,
            $eloquent->current_status,
            $machines
        );
    }


    public static function toEloquent(DomainFurnace $domain): array
    {
        return [
            'id' => $domain->getId(),
            'company_id' => $domain->getCompanyId(),
            'glass_type_id' => 1, // Nota: En una implementación real, esto vendría de la entidad GlassType
            'name' => $domain->getName(),
            'max_capacity_tons' => $domain->getMaxCapacity(),
            'current_status' => $domain->getStatus(),
        ];
    }
}
