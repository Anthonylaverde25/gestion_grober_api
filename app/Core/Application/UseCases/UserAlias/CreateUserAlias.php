<?php

namespace App\Core\Application\UseCases\UserAlias;

use App\Core\Domain\Entities\UserAlias;
use App\Core\Domain\Repositories\UserAliasRepositoryInterface;
use Illuminate\Support\Str;

class CreateUserAlias
{
    public function __construct(
        private UserAliasRepositoryInterface $repository
    ) {}

    public function execute(int $userId, string $name, string $legajo): UserAlias
    {
        // Verificar si ya existe el legajo
        $existing = $this->repository->findByLegajo($legajo);
        if ($existing) {
            throw new \Exception("El legajo {$legajo} ya está registrado.");
        }

        $alias = UserAlias::create(
            (string) Str::uuid(),
            $userId,
            $name,
            $legajo
        );

        $this->repository->save($alias);

        return $alias;
    }
}
