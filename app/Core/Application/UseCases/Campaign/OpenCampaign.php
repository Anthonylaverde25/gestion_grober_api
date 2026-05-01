<?php

namespace App\Core\Application\UseCases\Campaign;

use App\Core\Application\DTOs\Campaign\OpenCampaignDTO;
use App\Core\Domain\Entities\Campaign;
use App\Core\Domain\Repositories\CampaignRepositoryInterface;
use App\Core\Domain\Repositories\MachineRepositoryInterface;
use App\Core\Domain\Repositories\ArticleRepositoryInterface;
use App\Core\Domain\Repositories\ClientRepositoryInterface;
use DomainException;
use Illuminate\Support\Str;

class OpenCampaign
{
    public function __construct(
        private CampaignRepositoryInterface $campaignRepository,
        private MachineRepositoryInterface $machineRepository,
        private ArticleRepositoryInterface $articleRepository,
        private ClientRepositoryInterface $clientRepository
    ) {}

    public function execute(OpenCampaignDTO $dto): Campaign
    {
        // 1. Validar Máquina
        $machine = $this->machineRepository->findById($dto->machineId);
        if (!$machine || $machine->getCompanyId() !== $dto->companyId) {
            throw new DomainException('La máquina seleccionada no existe o no pertenece a la empresa.');
        }

        // 2. Validar que la máquina no tenga una campaña activa
        if ($machine->getCurrentCampaignId()) {
            throw new DomainException("La máquina {$machine->getName()} ya tiene una campaña activa.");
        }

        // 3. Validar Artículo
        $article = $this->articleRepository->findById($dto->articleId);
        if (!$article || $article->getCompanyId() !== $dto->companyId) {
            throw new DomainException('El artículo seleccionado no existe o no pertenece a la empresa.');
        }

        // 4. Validar Cliente
        $client = $this->clientRepository->findById($dto->clientId);
        if (!$client || $client->getCompanyId() !== $dto->companyId) {
            throw new DomainException('El cliente seleccionado no existe o no pertenece a la empresa.');
        }

        // 5. Crear Entidad Campaign
        $campaign = Campaign::create(
            (string) Str::uuid(),
            $dto->companyId,
            $dto->codigo,
            $dto->machineId,
            $dto->articleId,
            $dto->clientId,
            $dto->operatorId
        );

        // 6. Persistir Campaign
        $this->campaignRepository->save($campaign);

        // 7. Bloquear Máquina, asignar Artículo y activar
        $machine->lockWithCampaign($campaign->getId());
        $machine->assignCurrentArticle($campaign->getArticleId());
        $machine->updateStatus('operational');
        $this->machineRepository->save($machine);

        return $campaign;
    }
}
