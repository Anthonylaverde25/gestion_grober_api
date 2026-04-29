<?php

namespace App\Core\Application\Services;

use App\Core\Application\DTOs\Auth\LoginInputDTO;
use App\Core\Application\DTOs\Auth\AuthOutputDTO;
use App\Core\Application\UseCases\Auth\AuthenticateUser;
use App\Core\Application\UseCases\Auth\RevokeAccess;
use App\Core\Domain\Entities\User;
use App\Core\Domain\Services\TokenServiceInterface;

class AuthService
{
    public function __construct(
        private AuthenticateUser $authenticateUser,
        private RevokeAccess $revokeAccess,
        private TokenServiceInterface $tokenService
    ) {}

    public function login(LoginInputDTO $dto): AuthOutputDTO
    {
        $user = $this->authenticateUser->execute($dto->email, $dto->password);
        
        $token = $this->tokenService->generate($user, $dto->deviceName);

        return new AuthOutputDTO($user, $token);
    }

    public function logout(User $user): void
    {
        $this->revokeAccess->execute($user);
    }

    public function switchContext(\App\Models\User $user, string $companyId): void
    {
        // 1. Verificar si el usuario tiene un rol global (vinculación con company_id NULL)
        $isGlobalAdmin = \Illuminate\Support\Facades\DB::table('company_user')
            ->join('roles', 'company_user.role_id', '=', 'roles.id')
            ->where('user_id', $user->id)
            ->whereNull('company_id')
            ->whereIn('roles.slug', ['admin', 'superadmin', 'owner'])
            ->exists();

        // 2. Si no es global admin, validar vinculación específica
        if (!$isGlobalAdmin) {
            $hasRelation = \Illuminate\Support\Facades\DB::table('company_user')
                ->where('user_id', $user->id)
                ->where('company_id', $companyId)
                ->exists();

            if (!$hasRelation) {
                throw new \Exception("No tienes vinculación con la empresa solicitada.");
            }
        }

        $user->update(['last_active_company_id' => $companyId]);
    }
}
