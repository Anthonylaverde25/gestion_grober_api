<?php

namespace App\Core\Infrastructure\Persistence\Eloquent;

use App\Core\Domain\Entities\Machine as DomainMachine;
use App\Core\Domain\Repositories\MachineRepositoryInterface;
use App\Core\Infrastructure\Persistence\Eloquent\Mappers\MachineMapper;
use App\Models\Machine as EloquentMachine;

class EloquentMachineRepository implements MachineRepositoryInterface
{
    public function findById(string $id): ?DomainMachine
    {
        $eloquent = EloquentMachine::with('currentArticle')->find($id);

        return $eloquent ? MachineMapper::toDomain($eloquent) : null;
    }

    public function save(DomainMachine $machine): void
    {
        EloquentMachine::updateOrCreate(
            ['id' => $machine->getId()],
            MachineMapper::toEloquent($machine)
        );
    }

    public function findByCompany(string $companyId): array
    {
        return EloquentMachine::with('currentArticle')->where('company_id', $companyId)
            ->get()
            ->map(fn($item) => MachineMapper::toDomain($item))
            ->toArray();
    }

    public function findByFurnace(string $furnaceId): array
    {
        return EloquentMachine::with('currentArticle')->where('furnace_id', $furnaceId)
            ->get()
            ->map(fn($item) => MachineMapper::toDomain($item))
            ->toArray();
    }
}
