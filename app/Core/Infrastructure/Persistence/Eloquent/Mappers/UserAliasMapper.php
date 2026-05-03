<?php

namespace App\Core\Infrastructure\Persistence\Eloquent\Mappers;

use App\Models\UserAlias as EloquentUserAlias;
use App\Core\Domain\Entities\UserAlias as DomainUserAlias;

class UserAliasMapper
{
    public static function toDomain(EloquentUserAlias $eloquent): DomainUserAlias
    {
        return DomainUserAlias::reconstitute(
            $eloquent->id,
            $eloquent->user_id,
            $eloquent->name,
            $eloquent->legajo,
            $eloquent->is_active && is_null($eloquent->deleted_at)
        );
    }

    public static function toEloquent(DomainUserAlias $domain): array
    {
        return [
            'id' => $domain->getId(),
            'user_id' => $domain->getUserId(),
            'name' => $domain->getName(),
            'legajo' => $domain->getLegajo(),
            'is_active' => $domain->isActive(),
        ];
    }
}
