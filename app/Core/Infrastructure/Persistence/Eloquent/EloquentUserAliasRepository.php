<?php

namespace App\Core\Infrastructure\Persistence\Eloquent;

use App\Core\Domain\Entities\UserAlias as DomainUserAlias;
use App\Core\Domain\Repositories\UserAliasRepositoryInterface;
use App\Core\Infrastructure\Persistence\Eloquent\Mappers\UserAliasMapper;
use App\Models\UserAlias as EloquentUserAlias;

class EloquentUserAliasRepository implements UserAliasRepositoryInterface
{
    public function findById(string $id): ?DomainUserAlias
    {
        $eloquent = EloquentUserAlias::find($id);
        return $eloquent ? UserAliasMapper::toDomain($eloquent) : null;
    }

    public function findByLegajo(string $legajo): ?DomainUserAlias
    {
        $eloquent = EloquentUserAlias::where('legajo', $legajo)->first();
        return $eloquent ? UserAliasMapper::toDomain($eloquent) : null;
    }

    /**
     * @return DomainUserAlias[]
     */
    public function findByUser(int $userId): array
    {
        $aliases = EloquentUserAlias::where('user_id', $userId)
            ->where('is_active', true)
            ->get();

        return $aliases->map(fn($item) => UserAliasMapper::toDomain($item))->toArray();
    }

    public function save(DomainUserAlias $userAlias): void
    {
        EloquentUserAlias::updateOrCreate(
            ['id' => $userAlias->getId()],
            UserAliasMapper::toEloquent($userAlias)
        );
    }

    public function delete(string $id): void
    {
        EloquentUserAlias::destroy($id);
    }
}
