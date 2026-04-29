<?php

namespace App\Core\Domain\Repositories;

use App\Core\Domain\Entities\GlassType;

interface GlassTypeRepositoryInterface
{
    public function findById(int $id): ?GlassType;
    public function all(): array;
    public function save(GlassType $glassType): void;
}
