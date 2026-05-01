<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ClientResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->getId(),
            'company_id' => $this->getCompanyId(),
            'commercial_name' => $this->getCommercialName(),
            'business_name' => $this->getBusinessName(),
            'tax_id' => $this->getTaxId(),
            'technical_contact' => $this->getTechnicalContact(),
            'email' => $this->getEmail(),
        ];
    }
}
