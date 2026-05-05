<?php

namespace App\Core\Infrastructure\Persistence\Eloquent;

use App\Core\Domain\Entities\User as DomainUser;
use App\Core\Domain\Repositories\UserRepositoryInterface;
use App\Core\Infrastructure\Persistence\Eloquent\Mappers\UserMapper;
use App\Models\User as EloquentUser;

class EloquentUserRepository implements UserRepositoryInterface
{
    public function findByCompany(?string $companyId = null): array
    {
        // Traemos usuarios que pertenecen a la empresa a través de la tabla pivote
        $users = EloquentUser::whereHas('companies', function ($query) use ($companyId) {
            $query->where('companies.id', $companyId);
        })->with(['roles' => function ($query) use ($companyId) {
            // Solo los roles que tiene en ESTA empresa
            $query->wherePivot('company_id', $companyId);
        }])->get();

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
