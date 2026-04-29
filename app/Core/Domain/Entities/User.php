<?php

namespace App\Core\Domain\Entities;

use App\Core\Domain\ValueObjects\Email;

class User
{
    public function __construct(
        private int $id,
        private string $name,
        private Email $email,
        private array $roles = [],
        private array $companies = [],
        private ?string $lastActiveCompanyId = null,
        private bool $isActive = true
    ) {}

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getEmail(): Email
    {
        return $this->email;
    }

    public function getRoles(): array
    {
        return $this->roles;
    }

    public function getCompanies(): array
    {
        return $this->companies;
    }

    public function getLastActiveCompanyId(): ?string
    {
        return $this->lastActiveCompanyId;
    }

    public function isActive(): bool
    {
        return $this->isActive;
    }
}
