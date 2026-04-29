<?php

namespace App\Core\Application\DTOs\Machine;

readonly class CreateMachineDTO
{
    public function __construct(
        public string $companyId,
        public string $furnaceId,
        public ?string $currentArticleId,
        public string $name,
        public string $status = 'operational'
    ) {}

    public static function fromRequest(array $data): self
    {
        return new self(
            $data['company_id'],
            $data['furnace_id'],
            $data['current_article_id'] ?? null,
            $data['name'],
            $data['status'] ?? $data['current_status'] ?? 'operational'
        );
    }
}
