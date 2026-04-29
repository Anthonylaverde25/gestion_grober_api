<?php

namespace App\Core\Application\DTOs\Machine;

readonly class ChangeMachineArticleDTO
{
    public function __construct(
        public string $machineId,
        public ?string $articleId
    ) {}

    public static function fromRequest(string $machineId, array $data): self
    {
        return new self(
            $machineId,
            $data['article_id'] ?? null
        );
    }
}
