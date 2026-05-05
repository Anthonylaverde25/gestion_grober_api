<?php

namespace App\Core\Domain\Entities;

use InvalidArgumentException;
use DateTimeImmutable;

class Campaign
{
    private const STATUS_ACTIVE = 'ACTIVE';
    private const STATUS_PAUSED = 'PAUSED';
    private const STATUS_FINISHED = 'FINISHED';
    private const STATUS_CANCELLED = 'CANCELLED';

    private function __construct(
        private string $id,
        private string $companyId,
        private string $codigo,
        private string $machineId,
        private string $articleId,
        private string $clientId,
        private string $status,
        private DateTimeImmutable $startedAt,
        private ?DateTimeImmutable $finishedAt = null,
        private int $totalYieldRecords = 0,
        private ?string $operatorId = null,
        private ?string $clientName = null,
        private ?string $articleName = null,
        private ?string $companyName = null,
        private ?Machine $machine = null,
        private ?Client $client = null,
        private ?Article $article = null
    ) {}

    public static function create(
        string $id,
        string $companyId,
        string $codigo,
        string $machineId,
        string $articleId,
        string $clientId,
        ?string $operatorId = null
    ): self {
        if (empty(trim($codigo))) {
            throw new InvalidArgumentException('El código de la campaña no puede estar vacío.');
        }

        return new self(
            $id,
            $companyId,
            $codigo,
            $machineId,
            $articleId,
            $clientId,
            self::STATUS_ACTIVE,
            new DateTimeImmutable(),
            null,
            0,
            $operatorId
        );
    }

    public static function reconstitute(
        string $id,
        string $companyId,
        string $codigo,
        string $machineId,
        string $articleId,
        string $clientId,
        string $status,
        DateTimeImmutable $startedAt,
        ?DateTimeImmutable $finishedAt = null,
        int $totalYieldRecords = 0,
        ?string $operatorId = null,
        ?string $clientName = null,
        ?string $articleName = null,
        ?string $companyName = null,
        ?Machine $machine = null,
        ?Client $client = null,
        ?Article $article = null
    ): self {
        return new self($id, $companyId, $codigo, $machineId, $articleId, $clientId, $status, $startedAt, $finishedAt, $totalYieldRecords, $operatorId, $clientName, $articleName, $companyName, $machine, $client, $article);
    }

    // Getters
    public function getId(): string { return $this->id; }
    public function getCompanyId(): string { return $this->companyId; }
    public function getCompanyName(): ?string { return $this->companyName; }
    public function getCodigo(): string { return $this->codigo; }
    public function getMachineId(): string { return $this->machineId; }
    public function getArticleId(): string { return $this->articleId; }
    public function getClientId(): string { return $this->clientId; }
    public function getStatus(): string { return $this->status; }
    public function getStartedAt(): DateTimeImmutable { return $this->startedAt; }
    public function getFinishedAt(): ?DateTimeImmutable { return $this->finishedAt; }
    public function getTotalYieldRecords(): int { return $this->totalYieldRecords; }
    public function getClientName(): ?string { return $this->clientName; }
    public function getArticleName(): ?string { return $this->articleName; }
    public function getMachine(): ?Machine { return $this->machine; }
    public function getClient(): ?Client { return $this->client; }
    public function getArticle(): ?Article { return $this->article; }

    // Comportamiento de Estado
    public function finish(): void
    {
        if ($this->status === self::STATUS_FINISHED || $this->status === self::STATUS_CANCELLED) {
            throw new InvalidArgumentException("No se puede finalizar una campaña en estado: {$this->status}");
        }

        $this->status = self::STATUS_FINISHED;
        $this->finishedAt = new DateTimeImmutable();
    }

    public function pause(): void
    {
        if ($this->status !== self::STATUS_ACTIVE) {
            throw new InvalidArgumentException("Solo se puede pausar una campaña activa.");
        }
        $this->status = self::STATUS_PAUSED;
    }

    public function resume(): void
    {
        if ($this->status !== self::STATUS_PAUSED) {
            throw new InvalidArgumentException("Solo se puede reanudar una campaña pausada.");
        }
        $this->status = self::STATUS_ACTIVE;
    }

    public function incrementYieldRecords(): void
    {
        if ($this->status !== self::STATUS_ACTIVE) {
            throw new InvalidArgumentException("No se pueden registrar rendimientos en una campaña que no esté activa.");
        }
        $this->totalYieldRecords++;
    }

    public function isActive(): bool
    {
        return $this->status === self::STATUS_ACTIVE;
    }
}
