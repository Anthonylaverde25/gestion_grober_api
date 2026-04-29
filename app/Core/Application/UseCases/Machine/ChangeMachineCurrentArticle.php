<?php

namespace App\Core\Application\UseCases\Machine;

use App\Core\Application\DTOs\Machine\ChangeMachineArticleDTO;
use App\Core\Domain\Entities\Machine;
use App\Core\Domain\Repositories\ArticleRepositoryInterface;
use App\Core\Domain\Repositories\MachineRepositoryInterface;
use DomainException;

class ChangeMachineCurrentArticle
{
    public function __construct(
        private MachineRepositoryInterface $machineRepository,
        private ArticleRepositoryInterface $articleRepository
    ) {}

    public function execute(ChangeMachineArticleDTO $dto): Machine
    {
        $machine = $this->machineRepository->findById($dto->machineId);

        if (!$machine) {
            throw new DomainException('La máquina seleccionada no existe.');
        }

        if ($dto->articleId === null) {
            $machine->clearCurrentArticle();
            $this->machineRepository->save($machine);

            return $machine;
        }

        $article = $this->articleRepository->findById($dto->articleId);

        if (!$article) {
            throw new DomainException('El artículo seleccionado no existe.');
        }

        if ($article->getCompanyId() !== $machine->getCompanyId()) {
            throw new DomainException('La máquina y el artículo deben pertenecer a la misma empresa.');
        }

        $machine->assignCurrentArticle($dto->articleId);
        $this->machineRepository->save($machine);

        return $machine;
    }
}
