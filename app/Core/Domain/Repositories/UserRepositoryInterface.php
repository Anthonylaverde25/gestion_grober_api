<?php

namespace App\Core\Domain\Repositories;

use App\Core\Domain\Entities\User;

interface UserRepositoryInterface
{
    /**
     * Obtiene todos los usuarios asociados a una empresa específica.
     * 
     * @param string|null $companyId
     * @return User[]
     */
    public function findByCompany(?string $companyId = null): array;

    /**
     * Obtiene todos los usuarios del sistema.
     * 
     * @return User[]
     */
    public function findAll(): array;

    /**
     * Busca un usuario por su ID.
     */
    public function findById(int $id): ?User;

    /**
     * Crear un nuevo usuario en el sistema.
     */
    public function create(array $data): User;
}
