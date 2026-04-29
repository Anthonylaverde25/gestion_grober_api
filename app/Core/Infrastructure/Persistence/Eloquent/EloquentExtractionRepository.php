<?php

namespace App\Core\Infrastructure\Persistence\Eloquent;

use App\Core\Domain\Entities\Extraction as DomainExtraction;
use App\Core\Domain\Repositories\ExtractionRepositoryInterface;
use App\Core\Infrastructure\Persistence\Eloquent\Mappers\ExtractionMapper;
use App\Models\Extraction as EloquentExtraction;

class EloquentExtractionRepository implements ExtractionRepositoryInterface
{
    public function findById(string $id): ?DomainExtraction
    {
        $eloquent = EloquentExtraction::with('article')->find($id);
        return $eloquent ? ExtractionMapper::toDomain($eloquent) : null;
    }

    public function findByMachineHistory(string $machineId, int $limit = 50): array
    {
        $extractions = EloquentExtraction::with('article')->where('machine_id', $machineId)
            ->orderBy('measured_at', 'desc')
            ->limit($limit)
            ->get();

        return $extractions->map(fn($item) => ExtractionMapper::toDomain($item))->toArray();
    }

    public function findLatestByMachine(string $machineId): ?DomainExtraction
    {
        $latest = EloquentExtraction::with('article')->where('machine_id', $machineId)
            ->orderBy('measured_at', 'desc')
            ->first();

        return $latest ? ExtractionMapper::toDomain($latest) : null;
    }

    public function save(DomainExtraction $extraction): void
    {
        EloquentExtraction::updateOrCreate(
            ['id' => $extraction->getId()],
            ExtractionMapper::toEloquent($extraction)
        );
    }

    public function delete(string $id): void
    {
        EloquentExtraction::destroy($id);
    }
}
