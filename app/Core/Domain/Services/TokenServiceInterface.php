<?php

namespace App\Core\Domain\Services;

use App\Core\Domain\Entities\User;

interface TokenServiceInterface
{
    /**
     * Genera un token de acceso para el usuario.
     */
    public function generate(User $user, string $deviceName): string;

    /**
     * Revoca todos los tokens del usuario o el token actual.
     */
    public function revoke(User $user): void;
}
