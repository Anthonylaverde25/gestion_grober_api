<?php

namespace App\Core\Application\DTOs\Client;

readonly class CreateClientDTO
{
    public function __construct(
        public string $companyId,
        public string $commercialName,
        public string $businessName,
        public string $taxId,
        public ?string $technicalContact = null,
        public ?string $email = null
    ) {}

    public static function fromRequest(array $data): self
    {
        return new self(
            $data['company_id'],
            $data['commercial_name'],
            $data['business_name'],
            $data['tax_id'],
            $data['technical_contact'] ?? null,
            $data['email'] ?? null
        );
    }
}
