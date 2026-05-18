<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Core\Application\UseCases\LineYield\RecordLineYield;
use App\Core\Application\UseCases\LineYield\RecordLineYieldBatch;
use App\Core\Application\UseCases\LineYield\GetMachineYieldHistory;
use App\Core\Application\DTOs\LineYield\RecordLineYieldDTO;
use App\Core\Application\DTOs\LineYield\RecordLineYieldBatchDTO;
use App\Core\Domain\Repositories\LineYieldRepositoryInterface;
use App\Http\Requests\V1\LineYield\RecordLineYieldRequest;
use App\Http\Requests\V1\LineYield\RecordLineYieldBatchRequest;
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
        private GetMachineYieldHistory $getMachineYieldHistory,
        private \App\Core\Domain\Repositories\UserAliasRepositoryInterface $userAliasRepository
    ) {}

    public function store(RecordLineYieldRequest $request): JsonResponse
    {
        try {
            $data = $request->validated();
            $data['company_id'] = $this->activeCompanyId();

            if (!$data['company_id']) {
                return response()->json([
                    'message' => 'No active company context found.'
                ], 422);
            }

            if (!empty($data['user_alias_id'])) {
                $alias = $this->userAliasRepository->findById($data['user_alias_id']);
                if ($alias && !$alias->isActive()) {
                    return response()->json(['message' => 'El alias especificado está desactivado y no puede operar.'], 403);
                }
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

    public function storeBatch(RecordLineYieldBatchRequest $request): JsonResponse
    {
        try {
            $companyId = $this->activeCompanyId();

            if (!$companyId) {
                return response()->json([
                    'message' => 'No active company context found.'
                ], 422);
            }

            $data = $request->validated();
            foreach ($data['items'] as $item) {
                if (!empty($item['user_alias_id'])) {
                    $alias = $this->userAliasRepository->findById($item['user_alias_id']);
                    if ($alias && !$alias->isActive()) {
                        return response()->json(['message' => 'Un alias especificado en el lote está desactivado y no puede operar.'], 403);
                    }
                }
            }

            $yields = $this->recordLineYieldBatch->execute(
                RecordLineYieldBatchDTO::fromRequest($data, $companyId)
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
        $limit = $request->query('limit');
        $yields = $this->getMachineYieldHistory->execute($machineId, $limit ? (int) $limit : null);

        return response()->json([
            'data' => LineYieldResource::collection($yields)
        ]);
    }
}
