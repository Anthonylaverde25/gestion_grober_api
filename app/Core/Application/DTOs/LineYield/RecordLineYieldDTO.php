<?php

namespace App\Core\Application\DTOs\LineYield;

readonly class RecordLineYieldDTO
{
    public function __construct(
        public string $companyId,
        public string $campaignId,
        public float $formingYield,
        public float $packingYield,
        public ?string $notes = null,
        public ?string $recordedAt = null
    ) {}

    public static function fromRequest(array $data): self
    {
        return new self(
            $data['company_id'],
            $data['campaign_id'],
            (float) $data['forming_yield'],
            (float) $data['packing_yield'],
            $data['notes'] ?? null,
            $data['recorded_at'] ?? null
        );
    }
}
