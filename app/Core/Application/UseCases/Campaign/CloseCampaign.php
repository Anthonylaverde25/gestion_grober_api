<?php

namespace App\Core\Application\UseCases\Campaign;

use App\Core\Domain\Repositories\CampaignRepositoryInterface;
use App\Core\Domain\Repositories\MachineRepositoryInterface;
use DomainException;

class CloseCampaign
{
    public function __construct(
        private CampaignRepositoryInterface $campaignRepository,
        private MachineRepositoryInterface $machineRepository
    ) {}

    public function execute(string $campaignId, string $companyId): void
    {
        $campaign = $this->campaignRepository->findById($campaignId);

        if (!$campaign || $campaign->getCompanyId() !== $companyId) {
            throw new DomainException('La campaña no existe o no pertenece a la empresa.');
        }

        $campaign->finish();
        $this->campaignRepository->save($campaign);

        $machine = $this->machineRepository->findById($campaign->getMachineId());
        if ($machine) {
            $machine->releaseCampaign();
            $this->machineRepository->save($machine);
        }
    }
}
