<?php

namespace App\Core\Infrastructure\Persistence\Eloquent\Mappers;

use App\Models\User as EloquentUser;
use App\Core\Domain\Entities\User as DomainUser;
use App\Core\Domain\ValueObjects\Email;

class UserMapper
{
    public static function toDomain(EloquentUser $eloquent): DomainUser
    {
        $roleSlugs = $eloquent->roles ? $eloquent->roles->pluck('slug')->toArray() : ['admin'];
        
        // Obtener slugs de módulos permitidos a través de los roles
        $moduleSlugs = $eloquent->roles 
            ? $eloquent->roles->flatMap->modules->pluck('slug')->unique()->toArray() 
            : [];

        $globalRoles = ['admin', 'superadmin', 'owner'];
        
        $hasGlobalAccess = !empty(array_intersect($roleSlugs, $globalRoles));

        $companiesCollection = $hasGlobalAccess 
            ? \App\Models\Company::all() 
            : $eloquent->companies;

        $companies = $companiesCollection->map(function ($company) {
            return CompanyMapper::toDomain($company);
        })->toArray();

        return new DomainUser(
            $eloquent->id,
            $eloquent->name,
            new Email($eloquent->email),
            $roleSlugs,
            $companies,
            $eloquent->last_active_company_id,
            true,
            $moduleSlugs
        );
    }
}
