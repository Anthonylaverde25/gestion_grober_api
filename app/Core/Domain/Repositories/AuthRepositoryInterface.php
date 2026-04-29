<?php

namespace App\Core\Domain\Repositories;

use App\Core\Domain\Entities\User;
use App\Core\Domain\ValueObjects\Email;

interface AuthRepositoryInterface
{
    /**
     * Valida las credenciales y retorna la entidad User si son correctas.
     */
    public function validateCredentials(Email $email, string $password): ?User;
    
    /**
     * Busca un usuario por su email.
     */
    public function findByEmail(Email $email): ?User;
}
