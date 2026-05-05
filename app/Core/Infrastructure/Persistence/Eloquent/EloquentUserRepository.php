<?php

namespace App\Core\Infrastructure\Persistence\Eloquent;

use App\Core\Domain\Entities\User as DomainUser;
use App\Core\Domain\Repositories\UserRepositoryInterface;
use App\Core\Infrastructure\Persistence\Eloquent\Mappers\UserMapper;
use App\Models\User as EloquentUser;
use App\Core\Context\TenantContext;

class EloquentUserRepository implements UserRepositoryInterface
{
    public function findByCompany(?string $companyId = null): array
    {
        $companyId = $companyId ?: TenantContext::getCompanyId();

        if (!$companyId && !TenantContext::isGlobalContext()) {
            return [];
        }

        $query = EloquentUser::query();

        if ($companyId) {
            // Usuarios que pertenecen a la empresa O que son Administradores Globales
            $query->where(function($q) use ($companyId) {
                $q->whereHas('companies', function ($inner) use ($companyId) {
                    $inner->where('companies.id', $companyId);
                })
                ->orWhereExists(function ($inner) {
                    $inner->select(\DB::raw(1))
                        ->from('company_user')
                        ->join('roles', 'company_user.role_id', '=', 'roles.id')
                        ->whereColumn('company_user.user_id', 'users.id')
                        ->whereNull('company_user.company_id')
                        ->whereIn('roles.slug', ['admin', 'superadmin', 'owner']);
                });
            });

            // Cargamos los roles correspondientes a la empresa O los roles globales
            $query->with(['roles' => function ($q) use ($companyId) {
                $q->where(function($sub) use ($companyId) {
                    $sub->where('company_user.company_id', $companyId)
                        ->orWhereNull('company_user.company_id');
                });
            }]);
        } else if (TenantContext::isGlobalContext()) {
            $query->with(['roles', 'companies']);
        }

        $users = $query->get();

        return $users->map(fn (EloquentUser $user) => UserMapper::toDomain($user))->toArray();
    }

    public function findAll(): array
    {
        $users = EloquentUser::with(['roles', 'companies'])->get();

        return $users->map(fn (EloquentUser $user) => UserMapper::toDomain($user))->toArray();
    }

    public function findById(int $id): ?DomainUser
    {
        $user = EloquentUser::find($id);
        return $user ? UserMapper::toDomain($user) : null;
    }

    public function create(array $data): DomainUser
    {
        return \DB::transaction(function () use ($data) {
            $user = EloquentUser::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => \Hash::make($data['password']),
            ]);

            if (isset($data['company_id']) && isset($data['role'])) {
                // El rol viene como slug (ADMIN, SUPERVISOR, OPERATOR)
                $role = \App\Models\Role::where('slug', strtolower($data['role']))->first();
                
                if ($role) {
                    $user->companies()->attach($data['company_id'], ['role_id' => $role->id]);
                }
            }

            return UserMapper::toDomain($user->load(['roles', 'companies']));
        });
    }
}
