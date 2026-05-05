<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\V1\CompanyResource;

class UserResource extends JsonResource
{
    /**
     * @param Request $request
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // El recurso puede recibir una Entidad de Dominio o un modelo Eloquent (vía Mapper)
        // Pero para consistencia con LoginResource, asumimos que es la Entidad de Dominio
        
        return [
            'id' => $this->resource->getId(),
            'name' => $this->resource->getName(),
            'email' => $this->resource->getEmail()->getValue(),
            'roles' => $this->resource->getRoles(),
            'modules' => $this->resource->getModules(),
            'last_active_company_id' => $this->resource->getLastActiveCompanyId(),
            'is_active' => $this->resource->isActive(),
            'companies' => CompanyResource::collection($this->resource->getCompanies()),
        ];
    }
}
