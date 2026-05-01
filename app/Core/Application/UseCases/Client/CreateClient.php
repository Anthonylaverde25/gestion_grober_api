<?php

namespace App\Core\Application\UseCases\Client;

use App\Core\Application\DTOs\Client\CreateClientDTO;
use App\Core\Domain\Entities\Client;
use App\Core\Domain\Repositories\ClientRepositoryInterface;
use Illuminate\Support\Str;

class CreateClient
{
    public function __construct(
        private ClientRepositoryInterface $clientRepository
    ) {}

    public function execute(CreateClientDTO $dto): Client
    {
        $client = Client::create(
            (string) Str::uuid(),
            $dto->companyId,
            $dto->commercialName,
            $dto->businessName,
            $dto->taxId,
            $dto->technicalContact,
            $dto->email
        );

        $this->clientRepository->save($client);

        return $client;
    }
}
