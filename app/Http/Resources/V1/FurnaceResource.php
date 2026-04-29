<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FurnaceResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->getId(),
            'company_id' => $this->getCompanyId(),
            'name' => $this->getName(),
            'max_capacity_tons' => $this->getMaxCapacity(),
            'status' => $this->getStatus(),
            'machines' => MachineResource::collection($this->getMachines()),
        ];
    }
}
