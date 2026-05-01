<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ArticleResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->getId(),
            'company_id' => $this->getCompanyId(),
            'client_id' => $this->getClientId(),
            'client' => $this->getClient() ? [
                'id' => $this->getClient()->getId(),
                'name' => $this->getClient()->getCommercialName(),
            ] : null,
            'name' => $this->getName(),
        ];
    }
}
