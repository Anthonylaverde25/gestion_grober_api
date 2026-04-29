<?php

namespace App\Core\Domain\Entities;

use InvalidArgumentException;

class Machine
{
    private function __construct(
        private string $id,
        private string $companyId,
        private string $furnaceId,
        private ?string $currentArticleId,
        private ?string $currentArticleName,
        private string $name,
        private string $status = 'operational'
    ) {}

    public static function create(
        string $id,
        string $companyId,
        string $furnaceId,
        ?string $currentArticleId,
        string $name,
        string $status = 'operational',
        ?string $currentArticleName = null
    ): self {
        if (empty(trim($name))) {
            throw new InvalidArgumentException('El nombre de la máquina no puede estar vacío.');
        }

        self::assertValidStatus($status);

        return new self($id, $companyId, $furnaceId, $currentArticleId, $currentArticleName, $name, $status);
    }

    public static function reconstitute(
        string $id,
        string $companyId,
        string $furnaceId,
        ?string $currentArticleId,
        string $name,
        string $status = 'operational',
        ?string $currentArticleName = null
    ): self {
        return new self($id, $companyId, $furnaceId, $currentArticleId, $currentArticleName, $name, $status);
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getCompanyId(): string
    {
        return $this->companyId;
    }

    public function getFurnaceId(): string
    {
        return $this->furnaceId;
    }

    public function getCurrentArticleId(): ?string
    {
        return $this->currentArticleId;
    }

    public function getCurrentArticleName(): ?string
    {
        return $this->currentArticleName;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function updateStatus(string $newStatus): void
    {
        self::assertValidStatus($newStatus);
        $this->status = $newStatus;
    }

    public function assignCurrentArticle(string $articleId): void
    {
        $this->currentArticleId = $articleId;
    }

    public function clearCurrentArticle(): void
    {
        $this->currentArticleId = null;
    }

    private static function assertValidStatus(string $status): void
    {
        $validStatuses = ['operational', 'maintenance', 'shutdown'];

        if (!in_array($status, $validStatuses, true)) {
            throw new InvalidArgumentException("Estado de máquina inválido: {$status}");
        }
    }
}
