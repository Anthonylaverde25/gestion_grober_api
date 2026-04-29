<?php

namespace App\Core\Application\UseCases\Machine;

use App\Core\Application\DTOs\Machine\CreateMachineDTO;
use App\Core\Domain\Entities\Machine;
use App\Core\Domain\Repositories\ArticleRepositoryInterface;
use App\Core\Domain\Repositories\FurnaceRepositoryInterface;
use App\Core\Domain\Repositories\MachineRepositoryInterface;
use DomainException;
use Illuminate\Support\Str;

class CreateMachine
{
    public function __construct(
        private MachineRepositoryInterface $machineRepository,
        private FurnaceRepositoryInterface $furnaceRepository,
        private ArticleRepositoryInterface $articleRepository
    ) {}

    public function execute(CreateMachineDTO $dto): Machine
    {
        $furnace = $this->furnaceRepository->findById($dto->furnaceId);

        if (!$furnace) {
            throw new DomainException('El horno seleccionado no existe.');
        }

        if ($furnace->getCompanyId() !== $dto->companyId) {
            throw new DomainException('La máquina debe pertenecer a la misma empresa que el horno.');
        }

        if ($dto->currentArticleId !== null) {
            $article = $this->articleRepository->findById($dto->currentArticleId);

            if (!$article) {
                throw new DomainException('El artículo seleccionado no existe.');
            }

            if ($article->getCompanyId() !== $dto->companyId) {
                throw new DomainException('La máquina y el artículo deben pertenecer a la misma empresa.');
            }
        }

        $machine = Machine::create(
            (string) Str::uuid(),
            $dto->companyId,
            $dto->furnaceId,
            $dto->currentArticleId,
            $dto->name,
            $dto->status
        );

        $this->machineRepository->save($machine);

        return $machine;
    }
}
