<?php

namespace App\Core\Application\UseCases\LineYield;

use App\Core\Application\DTOs\LineYield\RecordLineYieldDTO;
use App\Core\Domain\Entities\LineYield;
use App\Core\Domain\Repositories\LineYieldRepositoryInterface;
use App\Core\Domain\Repositories\CampaignRepositoryInterface;
use DomainException;
use Illuminate\Support\Str;

class RecordLineYield
{
    public function __construct(
        private LineYieldRepositoryInterface $lineYieldRepository,
        private CampaignRepositoryInterface $campaignRepository
    ) {}

    public function execute(RecordLineYieldDTO $dto): LineYield
    {
        $campaign = $this->campaignRepository->findById($dto->campaignId);

        if (!$campaign || $campaign->getCompanyId() !== $dto->companyId) {
            throw new DomainException('La campaña no existe o no pertenece a la empresa.');
        }

        if (!$campaign->isActive()) {
            throw new DomainException('No se pueden registrar rendimientos en una campaña que no esté activa.');
        }

        $lineYield = LineYield::create(
            (string) Str::uuid(),
            $dto->companyId,
            $dto->campaignId,
            $dto->formingYield,
            $dto->packingYield,
            $dto->notes,
            $dto->recordedAt ? new \DateTimeImmutable($dto->recordedAt) : null,
            $dto->userAliasId
        );

        $this->lineYieldRepository->save($lineYield);

        $campaign->incrementYieldRecords();
        $this->campaignRepository->save($campaign);

        return $lineYield;
    }
}
