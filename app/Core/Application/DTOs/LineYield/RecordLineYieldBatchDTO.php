<?php

namespace App\Core\Application\DTOs\LineYield;

readonly class RecordLineYieldBatchDTO
{
    /**
     * @param string $companyId
     * @param string $campaignId
     * @param RecordLineYieldDTO[] $items
     */
    public function __construct(
        public string $companyId,
        public string $campaignId,
        public array $items
    ) {}

    public static function fromRequest(array $data, string $companyId): self
    {
        $items = array_map(function ($item) use ($data, $companyId) {
            return new RecordLineYieldDTO(
                $companyId,
                $data['campaign_id'],
                (float) $item['forming_yield'],
                (float) $item['packing_yield'],
                $item['notes'] ?? null,
                $item['recorded_at'] ?? null,
                $item['user_alias_id'] ?? null
            );
        }, $data['items']);

        return new self($companyId, $data['campaign_id'], $items);
    }
}
