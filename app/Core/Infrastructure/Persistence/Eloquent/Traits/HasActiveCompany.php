<?php

namespace App\Core\Infrastructure\Persistence\Eloquent\Traits;

use App\Core\Context\TenantContext;
use Illuminate\Database\Eloquent\Builder;

trait HasActiveCompany
{
    public static function bootHasActiveCompany(): void
    {
        $companyId = TenantContext::getCompanyId();

        // Aplicamos el filtro si hay un companyId en el contexto, 
        // independientemente de si el rol es global o no. 
        // Esto permite que los Admins puedan "enfocarse" en una empresa usando el Switcher.
        if ($companyId) {
            static::addGlobalScope('company_context', function (Builder $builder) use ($companyId) {
                $builder->where($builder->getQuery()->from . '.company_id', $companyId);
            });
        }

        // Asignación automática al crear el registro
        static::creating(function ($model) use ($companyId) {
            if ($companyId && !$model->company_id) {
                $model->company_id = $companyId;
            }
        });
    }
}
