<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ExtractionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->getId(),
            'machine_id' => $this->getMachineId(),
            'article_id' => $this->getArticleId(),
            'article_name' => $this->getArticleName() ?? 'N/A',
            'percentage' => (float) $this->getPercentage(),
            'measured_at' => $this->getMeasuredAt()->format('Y-m-d H:i:s'),
            'is_active' => $this->isActive(),
        ];
    }
}
