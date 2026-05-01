<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Core\Application\UseCases\LineYield\RecordLineYield;
use App\Core\Application\UseCases\LineYield\GetMachineYieldHistory;
use App\Core\Application\DTOs\LineYield\RecordLineYieldDTO;
use App\Core\Domain\Repositories\LineYieldRepositoryInterface;
use App\Http\Resources\V1\LineYieldResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use DomainException;

class LineYieldController extends Controller
{
    public function __construct(
        private LineYieldRepositoryInterface $lineYieldRepository,
        private RecordLineYield $recordLineYield,
        private GetMachineYieldHistory $getMachineYieldHistory
    ) {}

    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'campaign_id' => 'required|uuid',
            'forming_yield' => 'required|numeric|min:0|max:100',
            'packing_yield' => 'required|numeric|min:0|max:100',
            'notes' => 'nullable|string',
        ]);

        try {
            $data = $request->all();
            $data['company_id'] = $this->activeCompanyId();

            if (!$data['company_id']) {
                return response()->json([
                    'message' => 'No active company context found.'
                ], 422);
            }

            $lineYield = $this->recordLineYield->execute(
                RecordLineYieldDTO::fromRequest($data)
            );

            return response()->json([
                'message' => 'Rendimiento registrado correctamente',
                'data' => new LineYieldResource($lineYield)
            ], 201);
        } catch (DomainException $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        }
    }

    public function history(Request $request, string $campaignId): JsonResponse
    {
        // En un caso ideal, validaríamos que la campaña pertenece a la empresa aquí también
        $yields = $this->lineYieldRepository->findByCampaign($campaignId);

        return response()->json([
            'data' => LineYieldResource::collection($yields)
        ]);
    }

    public function machineHistory(Request $request, string $machineId): JsonResponse
    {
        $limit = $request->query('limit', 50);
        $yields = $this->getMachineYieldHistory->execute($machineId, (int) $limit);

        return response()->json([
            'data' => LineYieldResource::collection($yields)
        ]);
    }
}
