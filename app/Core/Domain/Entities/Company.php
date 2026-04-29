<?php

namespace App\Core\Domain\Entities;

use InvalidArgumentException;

class Company
{
    private array $users = [];

    public function __construct(
        private string $id,
        private string $consortiumId,
        private string $name,
        private ?string $managerId = null,
        private bool $isActive = true
    ) {}

    public function getId(): string
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getConsortiumId(): string
    {
        return $this->consortiumId;
    }

    public function getManagerId(): ?string
    {
        return $this->managerId;
    }

    public function assignManager(string $managerId): void
    {
        $this->managerId = $managerId;
    }

    public function isActive(): bool
    {
        return $this->isActive;
    }
}
