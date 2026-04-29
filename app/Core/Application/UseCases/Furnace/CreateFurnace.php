<?php

namespace App\Core\Application\UseCases\Furnace;

use App\Core\Domain\Entities\Furnace;
use App\Core\Domain\Repositories\FurnaceRepositoryInterface;
use App\Core\Application\DTOs\Furnace\CreateFurnaceDTO;
use Illuminate\Support\Str;

class CreateFurnace
{
    public function __construct(
        private FurnaceRepositoryInterface $repository
    ) {}

    public function execute(CreateFurnaceDTO $dto): Furnace
    {
        $furnace = Furnace::create(
            (string) Str::uuid(), // Generamos identidad en el dominio
            $dto->companyId,
            $dto->glassTypeId,
            $dto->name,
            $dto->maxCapacityTons
        );

        $this->repository->save($furnace);

        return $furnace;
    }
}
