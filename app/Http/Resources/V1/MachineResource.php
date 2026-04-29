<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MachineResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->getId(),
            'company_id' => $this->getCompanyId(),
            'furnace_id' => $this->getFurnaceId(),
            'current_article' => [
                'id' => $this->getCurrentArticleId(),
                'name' => $this->getCurrentArticleName() ?? 'N/A',
            ],
            'name' => $this->getName(),
            'status' => $this->getStatus(),
        ];
    }
}
