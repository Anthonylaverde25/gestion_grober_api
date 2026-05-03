<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Core\Application\UseCases\LineYield\RecordLineYield;
use App\Core\Application\UseCases\LineYield\RecordLineYieldBatch;
use App\Core\Application\UseCases\LineYield\GetMachineYieldHistory;
use App\Core\Application\DTOs\LineYield\RecordLineYieldDTO;
use App\Core\Application\DTOs\LineYield\RecordLineYieldBatchDTO;
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
        private RecordLineYieldBatch $recordLineYieldBatch,
        private GetMachineYieldHistory $getMachineYieldHistory
    ) {}

    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'campaign_id' => 'required|uuid',
            'forming_yield' => 'required|numeric|min:0|max:100',
            'packing_yield' => 'required|numeric|min:0|max:100',
            'notes' => 'nullable|string',
            'user_alias_id' => 'nullable|uuid|exists:user_aliases,id',
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

    public function storeBatch(Request $request): JsonResponse
    {
        $request->validate([
            'campaign_id' => 'required|uuid',
            'items' => 'required|array|min:1',
            'items.*.forming_yield' => 'required|numeric|min:0|max:100',
            'items.*.packing_yield' => 'required|numeric|min:0|max:100',
            'items.*.recorded_at' => 'nullable|date',
            'items.*.notes' => 'nullable|string',
            'items.*.user_alias_id' => 'nullable|uuid|exists:user_aliases,id',
        ]);

        try {
            $companyId = $this->activeCompanyId();

            if (!$companyId) {
                return response()->json([
                    'message' => 'No active company context found.'
                ], 422);
            }

            $yields = $this->recordLineYieldBatch->execute(
                RecordLineYieldBatchDTO::fromRequest($request->all(), $companyId)
            );

            return response()->json([
                'message' => 'Rendimientos en serie registrados correctamente',
                'data' => LineYieldResource::collection($yields)
            ], 201);
        } catch (DomainException $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error al procesar el lote: ' . $e->getMessage()], 500);
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
