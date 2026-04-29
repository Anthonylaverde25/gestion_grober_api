<?php

namespace App\Core\Domain\Entities;

use InvalidArgumentException;

class Furnace
{
    /**
     * @var Machine[]
     */
    private array $machines = [];

    private function __construct(
        private string $id,
        private string $companyId,
        private int $glassTypeId,
        private string $name,
        private float $maxCapacityTons,
        private string $status = 'operational'
    ) {}

    /**
     * Factory Method para creación con validación de dominio.
     */
    public static function create(
        string $id,
        string $companyId,
        int $glassTypeId,
        string $name,
        float $maxCapacityTons,
        string $status = 'operational'
    ): self {
        if ($maxCapacityTons <= 0) {
            throw new \InvalidArgumentException("La capacidad del horno debe ser mayor a cero.");
        }

        if (empty(trim($name))) {
            throw new \InvalidArgumentException("El nombre del horno no puede estar vacío.");
        }

        return new self($id, $companyId, $glassTypeId, $name, $maxCapacityTons, $status);
    }

    /**
     * Factory Method para reconstitución desde persistencia.
     */
    public static function reconstitute(
        string $id,
        string $companyId,
        int $glassTypeId,
        string $name,
        float $maxCapacityTons,
        string $status = 'operational',
        array $machines = []
    ): self {
        $furnace = new self($id, $companyId, $glassTypeId, $name, $maxCapacityTons, $status);
        $furnace->machines = $machines;
        return $furnace;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getCompanyId(): string
    {
        return $this->companyId;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getMaxCapacity(): float
    {
        return $this->maxCapacityTons;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    /**
     * @return Machine[]
     */
    public function getMachines(): array
    {
        return $this->machines;
    }

    public function setMachines(array $machines): void
    {
        $this->machines = $machines;
    }

    public function updateStatus(string $newStatus): void
    {
        $validStatuses = ['operational', 'maintenance', 'shutdown'];
        if (!in_array($newStatus, $validStatuses)) {
            throw new InvalidArgumentException("Estado de horno inválido: {$newStatus}");
        }
        $this->status = $newStatus;
    }
}
