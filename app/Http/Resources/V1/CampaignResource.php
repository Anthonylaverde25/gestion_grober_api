<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CampaignResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->getId(),
            'company_id' => $this->getCompanyId(),
            'codigo' => $this->getCodigo(),
            'status' => $this->getStatus(),
            'company_name' => $this->getCompanyName() ?? 'N/A',
            'started_at' => $this->getStartedAt()
                ->setTimezone(new \DateTimeZone('America/Argentina/Buenos_Aires'))
                ->format('Y-m-d H:i:s'),
            'finished_at' => $this->getFinishedAt()
                ?->setTimezone(new \DateTimeZone('America/Argentina/Buenos_Aires'))
                ->format('Y-m-d H:i:s'),
            'total_yield_records' => $this->getTotalYieldRecords(),
            'machine' => [
                'id' => $this->getMachineId(),
                'name' => $this->getMachine()?->getName() ?? 'N/A'
            ],
            'client' => [
                'id' => $this->getClientId(),
                'name' => $this->getClientName() ?? 'N/A'
            ],
            'article' => [
                'id' => $this->getArticleId(),
                'name' => $this->getArticleName() ?? 'N/A'
            ],
        ];
    }
}
