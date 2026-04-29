<?php

namespace App\Core\Application\DTOs\Extraction;

readonly class RegisterExtractionDTO
{
    public function __construct(
        public string $machineId,
        public string $articleId,
        public float $percentage,
        public ?string $measuredAt = null
    ) {}

    public static function fromRequest(array $data): self
    {
        return new self(
            $data['machine_id'],
            $data['article_id'],
            (float) $data['percentage'],
            $data['measured_at'] ?? null
        );
    }
}
