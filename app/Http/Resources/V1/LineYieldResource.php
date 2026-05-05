<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LineYieldResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->getId(),
            'company_id' => $this->getCompanyId(),
            'campaign_id' => $this->getCampaignId(),
            'forming_yield' => $this->getFormingYield(),
            'packing_yield' => $this->getPackingYield(),
            'recorded_at' => $this->getRecordedAt()
                ->setTimezone(new \DateTimeZone('America/Argentina/Buenos_Aires'))
                ->format('Y-m-d H:i:s'),
            'notes' => $this->getNotes(),
            'alias' => $this->getAlias() ? new UserAliasResource($this->getAlias()) : null,
        ];
    }
}
