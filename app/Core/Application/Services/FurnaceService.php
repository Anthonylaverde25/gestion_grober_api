<?php

namespace App\Core\Application\Services;

use App\Core\Application\UseCases\Furnace\CreateFurnace;
use App\Core\Application\UseCases\Furnace\ListFurnacesByCompany;
use App\Core\Application\DTOs\Furnace\CreateFurnaceDTO;

class FurnaceService
{
    public function __construct(
        private CreateFurnace $createFurnace,
        private ListFurnacesByCompany $listFurnaces
    ) {}

    public function create(array $data)
    {
        $dto = CreateFurnaceDTO::fromRequest($data);
        return $this->createFurnace->execute($dto);
    }

    public function getByCompany(string $companyId)
    {
        return $this->listFurnaces->execute($companyId);
    }
}
