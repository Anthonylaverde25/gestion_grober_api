<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Core\Application\UseCases\Client\CreateClient;
use App\Core\Application\DTOs\Client\CreateClientDTO;
use App\Core\Domain\Repositories\ClientRepositoryInterface;
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

    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'commercial_name' => 'required|string|max:255',
            'business_name' => 'required|string|max:255',
            // 'tax_id' => 'required|string|max:20',
            'technical_contact' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
        ]);

        $data = $request->all();
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
