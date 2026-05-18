<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Core\Application\UseCases\Client\CreateClient;
use App\Core\Application\DTOs\Client\CreateClientDTO;
use App\Core\Domain\Repositories\ClientRepositoryInterface;
use App\Http\Requests\V1\Client\CreateClientRequest;
use App\Http\Resources\V1\ClientResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    public function __construct(
        private ClientRepositoryInterface $clientRepository,
        private CreateClient $createClient
    ) {}

    public function index(Request $request): JsonResponse
    {
        $companyId = $this->activeCompanyId();

        if (!$companyId) {
            return response()->json([
                'data' => [],
                'message' => 'No active company context found.'
            ]);
        }

        $clients = $this->clientRepository->findByCompany($companyId);

        return response()->json([
            'data' => ClientResource::collection($clients)
        ]);
    }

    public function store(CreateClientRequest $request): JsonResponse
    {
        $data = $request->validated();
        $data['company_id'] = $this->activeCompanyId();

        if (!$data['company_id']) {
            return response()->json([
                'message' => 'No active company context found.'
            ], 422);
        }

        $client = $this->createClient->execute(
            CreateClientDTO::fromRequest($data)
        );

        return response()->json([
            'message' => 'Client created successfully',
            'data' => new ClientResource($client)
        ], 201);
    }
}
