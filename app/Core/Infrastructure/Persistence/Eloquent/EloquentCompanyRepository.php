<?php

namespace App\Core\Infrastructure\Persistence\Eloquent;

use App\Core\Domain\Entities\Company;
use App\Core\Domain\Repositories\CompanyRepositoryInterface;
use App\Models\Company as EloquentCompany;
use App\Core\Infrastructure\Persistence\Eloquent\Mappers\CompanyMapper;

class EloquentCompanyRepository implements CompanyRepositoryInterface
{
    public function findById(string $id): ?Company
    {
        $eloquent = EloquentCompany::find($id);
        return $eloquent ? CompanyMapper::toDomain($eloquent) : null;
    }

    public function save(Company $company): void
    {
        EloquentCompany::updateOrCreate(
            ['id' => $company->getId()],
            CompanyMapper::toEloquent($company)
        );
    }

    public function findByConsortium(string $consortiumId): array
    {
        return EloquentCompany::where('consortium_id', $consortiumId)
            ->get()
            ->map(fn($item) => CompanyMapper::toDomain($item))
            ->toArray();
    }

    public function all(): array
    {
        return EloquentCompany::all()
            ->map(fn($item) => CompanyMapper::toDomain($item))
            ->toArray();
    }
}
