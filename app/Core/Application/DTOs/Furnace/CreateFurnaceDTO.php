<?php

namespace App\Core\Application\DTOs\Furnace;

readonly class CreateFurnaceDTO
{
    public function __construct(
        public string $companyId,
        public int $glassTypeId,
        public string $name,
        public float $maxCapacityTons
    ) {}

    public static function fromRequest(array $data): self
    {
        return new self(
            $data['company_id'],
            $data['glass_type_id'],
            $data['name'],
            (float) $data['max_capacity_tons']
        );
    }
}
