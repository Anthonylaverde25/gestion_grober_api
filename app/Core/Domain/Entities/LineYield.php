<?php

namespace App\Core\Domain\Entities;

use InvalidArgumentException;
use DateTimeImmutable;

class LineYield
{
    private function __construct(
        private string $id,
        private string $companyId,
        private string $campaignId,
        private float $formingYield,
        private float $packingYield,
        private DateTimeImmutable $recordedAt,
        private ?string $notes = null
    ) {}

    public static function create(
        string $id,
        string $companyId,
        string $campaignId,
        float $formingYield,
        float $packingYield,
        ?string $notes = null,
        ?DateTimeImmutable $recordedAt = null
    ): self {
        self::assertValidYield($formingYield, 'Forming Yield');
        self::assertValidYield($packingYield, 'Packing Yield');

        return new self(
            $id,
            $companyId,
            $campaignId,
            $formingYield,
            $packingYield,
            $recordedAt ?? new DateTimeImmutable(),
            $notes
        );
    }

    public static function reconstitute(
        string $id,
        string $companyId,
        string $campaignId,
        float $formingYield,
        float $packingYield,
        DateTimeImmutable $recordedAt,
        ?string $notes = null
    ): self {
        return new self($id, $companyId, $campaignId, $formingYield, $packingYield, $recordedAt, $notes);
    }

    // Getters
    public function getId(): string { return $this->id; }
    public function getCompanyId(): string { return $this->companyId; }
    public function getCampaignId(): string { return $this->campaignId; }
    public function getFormingYield(): float { return $this->formingYield; }
    public function getPackingYield(): float { return $this->packingYield; }
    public function getRecordedAt(): DateTimeImmutable { return $this->recordedAt; }
    public function getNotes(): ?string { return $this->notes; }

    private static function assertValidYield(float $yield, string $fieldName): void
    {
        if ($yield < 0 || $yield > 100) {
            throw new InvalidArgumentException("{$fieldName} debe estar entre 0 y 100%. Valor recibido: {$yield}");
        }
    }
}
