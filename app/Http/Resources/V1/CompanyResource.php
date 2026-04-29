<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CompanyResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        /** @var \App\Core\Domain\Entities\Company $this */
        return [
            'id' => $this->getId(),
            'consortium_id' => $this->getConsortiumId(),
            'manager_id' => $this->getManagerId(),
            'name' => $this->getName(),
            'is_active' => $this->isActive(),
        ];
    }
}
