<?php

namespace App\Core\Application\DTOs\Campaign;

readonly class OpenCampaignDTO
{
    public function __construct(
        public string $companyId,
        public string $codigo,
        public string $machineId,
        public string $articleId,
        public string $clientId,
        public ?string $operatorId = null
    ) {}

    public static function fromRequest(array $data): self
    {
        return new self(
            $data['company_id'],
            $data['codigo'],
            $data['machine_id'],
            $data['article_id'],
            $data['client_id'],
            $data['operator_id'] ?? null
        );
    }
}
