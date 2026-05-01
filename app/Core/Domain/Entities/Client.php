<?php

namespace App\Core\Domain\Entities;

use InvalidArgumentException;

class Client
{
    private function __construct(
        private string $id,
        private string $companyId,
        private string $commercialName,
        private string $businessName,
        private string $taxId,
        private ?string $technicalContact = null,
        private ?string $email = null
    ) {}

    public static function create(
        string $id,
        string $companyId,
        string $commercialName,
        string $businessName,
        string $taxId,
        ?string $technicalContact = null,
        ?string $email = null
    ): self {
        if (empty(trim($commercialName))) {
            throw new InvalidArgumentException('Commercial name cannot be empty.');
        }

        if (empty(trim($businessName))) {
            throw new InvalidArgumentException('Business name cannot be empty.');
        }

        self::assertValidTaxId($taxId);

        if ($email && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new InvalidArgumentException("Invalid email format: {$email}");
        }

        return new self($id, $companyId, $commercialName, $businessName, $taxId, $technicalContact, $email);
    }

    public static function reconstitute(
        string $id,
        string $companyId,
        string $commercialName,
        string $businessName,
        string $taxId,
        ?string $technicalContact = null,
        ?string $email = null
    ): self {
        return new self($id, $companyId, $commercialName, $businessName, $taxId, $technicalContact, $email);
    }

    // Getters
    public function getId(): string { return $this->id; }
    public function getCompanyId(): string { return $this->companyId; }
    public function getCommercialName(): string { return $this->commercialName; }
    public function getBusinessName(): string { return $this->businessName; }
    public function getTaxId(): string { return $this->taxId; }
    public function getTechnicalContact(): ?string { return $this->technicalContact; }
    public function getEmail(): ?string { return $this->email; }

    // Behavior
    public function updateContactInfo(?string $contact, ?string $email): void
    {
        if ($email && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new InvalidArgumentException("Invalid email format: {$email}");
        }
        $this->technicalContact = $contact;
        $this->email = $email;
    }

    private static function assertValidTaxId(string $taxId): void
    {
        // Basic validation for Argentinian CUIT (11 digits)
        $cleanTaxId = preg_replace('/[^0-9]/', '', $taxId);
        if (strlen($cleanTaxId) !== 11) {
            throw new InvalidArgumentException("Tax ID (CUIT) must have 11 numerical digits: {$taxId}");
        }
    }
}
