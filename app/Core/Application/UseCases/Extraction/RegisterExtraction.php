<?php

namespace App\Core\Application\UseCases\Extraction;

use App\Core\Application\DTOs\Extraction\RegisterExtractionDTO;
use App\Core\Domain\Entities\Extraction;
use App\Core\Domain\Repositories\ExtractionRepositoryInterface;
use App\Core\Domain\Repositories\MachineRepositoryInterface;
use App\Core\Domain\Repositories\ArticleRepositoryInterface;
use DomainException;
use Illuminate\Support\Str;
use DateTimeImmutable;

class RegisterExtraction
{
    public function __construct(
        private ExtractionRepositoryInterface $extractionRepository,
        private MachineRepositoryInterface $machineRepository,
        private ArticleRepositoryInterface $articleRepository
    ) {}

    public function execute(RegisterExtractionDTO $dto): Extraction
    {
        $machine = $this->machineRepository->findById($dto->machineId);
        if (!$machine) {
            throw new DomainException('La máquina seleccionada no existe.');
        }

        $article = $this->articleRepository->findById($dto->articleId);
        if (!$article) {
            throw new DomainException('El artículo seleccionado no existe.');
        }

        // Generar UUID v7 para ordenamiento cronológico eficiente
        $id = (string) Str::uuid7();
        
        $measuredAt = $dto->measuredAt 
            ? new DateTimeImmutable($dto->measuredAt) 
            : new DateTimeImmutable();

        $extraction = Extraction::create(
            $id,
            $dto->machineId,
            $dto->articleId,
            $dto->percentage,
            $measuredAt
        );

        $this->extractionRepository->save($extraction);

        return $extraction;
    }
}
