<?php

namespace App\Core\Domain\Entities;

class Article
{
    private function __construct(
        private string $id,
        private string $companyId,
        private string $name,
        private ?string $clientId = null,
        private ?Client $client = null
    ) {}

    /**
     * Factory Method para crear un nuevo artículo con validaciones de negocio.
     */
    public static function create(string $id, string $companyId, string $name, ?string $clientId = null, ?Client $client = null): self
    {
        if (empty(trim($name))) {
            throw new \InvalidArgumentException("El nombre del artículo no puede estar vacío.");
        }

        return new self($id, $companyId, $name, $clientId, $client);
    }

    /**
     * Método para reconstituir la entidad desde persistencia.
     */
    public static function reconstitute(string $id, string $companyId, string $name, ?string $clientId = null, ?Client $client = null): self
    {
        return new self($id, $companyId, $name, $clientId, $client);
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

    public function getClientId(): ?string
    {
        return $this->clientId;
    }

    public function getClient(): ?Client
    {
        return $this->client;
    }

    public function updateName(string $newName): void
    {
        if (empty(trim($newName))) {
            throw new \InvalidArgumentException("El nombre del artículo no puede estar vacío.");
        }
        $this->name = $newName;
    }
}
