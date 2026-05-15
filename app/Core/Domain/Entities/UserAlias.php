<?php

namespace App\Core\Domain\Entities;

class UserAlias
{
    private function __construct(
        private string $id,
        private int $userId,
        private string $name,
        private string $legajo,
        private bool $isActive = true
    ) {}

    /**
     * Factory Method para registrar un nuevo operario.
     */
    public static function create(
        string $id,
        int $userId,
        string $name,
        string $legajo
    ): self {
        return new self($id, $userId, $name, $legajo, true);
    }

    /**
     * Reconstitución desde la capa de persistencia.
     */
    public static function reconstitute(
        string $id,
        int $userId,
        string $name,
        string $legajo,
        bool $isActive
    ): self {
        return new self($id, $userId, $name, $legajo, $isActive);
    }

    public function getId(): string { return $this->id; }
    public function getUserId(): int { return $this->userId; }
    public function getName(): string { return $this->name; }
    public function getLegajo(): string { return $this->legajo; }
    public function isActive(): bool { return $this->isActive; }

    public function toggleActive(): void
    {
        $this->isActive = !$this->isActive;
    }
}
