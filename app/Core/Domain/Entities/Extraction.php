<?php

namespace App\Core\Domain\Entities;

use DateTimeInterface;
use InvalidArgumentException;

class Extraction
{
    private function __construct(
        private string $id,
        private string $machineId,
        private string $articleId,
        private ?string $articleName,
        private float $percentage,
        private DateTimeInterface $measuredAt,
        private bool $isActive = true
    ) {}

    /**
     * Factory Method para registrar una nueva extracción.
     */
    public static function create(
        string $id,
        string $machineId,
        string $articleId,
        float $percentage,
        DateTimeInterface $measuredAt,
        ?string $articleName = null
    ): self {
        if ($percentage < 0 || $percentage > 100) {
            throw new InvalidArgumentException("El porcentaje de extracción debe estar entre 0 y 100.");
        }

        return new self($id, $machineId, $articleId, $articleName, $percentage, $measuredAt, true);
    }

    /**
     * Reconstitución desde la capa de persistencia.
     */
    public static function reconstitute(
        string $id,
        string $machineId,
        string $articleId,
        float $percentage,
        DateTimeInterface $measuredAt,
        bool $isActive,
        ?string $articleName = null
    ): self {
        return new self($id, $machineId, $articleId, $articleName, $percentage, $measuredAt, $isActive);
    }

    public function getId(): string { return $this->id; }
    public function getMachineId(): string { return $this->machineId; }
    public function getArticleId(): string { return $this->articleId; }
    public function getArticleName(): ?string { return $this->articleName; }
    public function getPercentage(): float { return $this->percentage; }
    public function getMeasuredAt(): DateTimeInterface { return $this->measuredAt; }
    public function isActive(): bool { return $this->isActive; }

    /**
     * Permite desactivar el registro lógicamente sin afectar la integridad del objeto.
     */
    public function deactivate(): void
    {
        $this->isActive = false;
    }
}
