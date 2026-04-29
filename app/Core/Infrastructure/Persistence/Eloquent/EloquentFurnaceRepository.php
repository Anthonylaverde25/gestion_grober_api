<?php

namespace App\Core\Infrastructure\Persistence\Eloquent;

use App\Core\Domain\Entities\Furnace;
use App\Core\Domain\Repositories\FurnaceRepositoryInterface;
use App\Models\Furnace as EloquentFurnace;
use App\Core\Infrastructure\Persistence\Eloquent\Mappers\FurnaceMapper;

class EloquentFurnaceRepository implements FurnaceRepositoryInterface
{
    public function findById(string $id): ?Furnace
    {
        $eloquent = EloquentFurnace::find($id);
        return $eloquent ? FurnaceMapper::toDomain($eloquent) : null;
    }

    public function save(Furnace $furnace): void
    {
        EloquentFurnace::updateOrCreate(
            ['id' => $furnace->getId()],
            FurnaceMapper::toEloquent($furnace)
        );
    }

    public function findByCompany(string $companyId): array
    {
        return EloquentFurnace::where('company_id', $companyId)
            ->with('machines')
            ->get()
            ->map(fn($item) => FurnaceMapper::toDomain($item))
            ->toArray();
    }
}
