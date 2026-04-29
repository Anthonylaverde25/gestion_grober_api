<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Core\Application\UseCases\Extraction\RegisterExtraction;
use App\Core\Application\UseCases\Extraction\GetMachineExtractionHistory;
use App\Core\Application\DTOs\Extraction\RegisterExtractionDTO;
use App\Http\Resources\V1\ExtractionResource;
use DomainException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ExtractionController extends Controller
{
    public function __construct(
        private RegisterExtraction $registerExtractionUseCase,
        private GetMachineExtractionHistory $getMachineExtractionHistoryUseCase
    ) {}

    /**
     * Registra una nueva extracción.
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'machine_id' => 'required|uuid|exists:machines,id',
            'article_id' => 'required|uuid|exists:articles,id',
            'percentage' => 'required|numeric|min:0|max:100',
            'measured_at' => 'nullable|date',
        ]);

        $dto = RegisterExtractionDTO::fromRequest($request->all());

        try {
            $extraction = $this->registerExtractionUseCase->execute($dto);
        } catch (DomainException $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
            ], 422);
        }

        return response()->json([
            'message' => 'Extracción registrada correctamente',
            'data' => new ExtractionResource($extraction),
        ], 201);
    }

    /**
     * Recupera el historial de extracciones de una máquina.
     */
    public function history(Request $request, string $machineId): JsonResponse
    {
        $limit = $request->query('limit', 50);

        try {
            $history = $this->getMachineExtractionHistoryUseCase->execute($machineId, (int) $limit);
        } catch (DomainException $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
            ], 422);
        }

        return response()->json([
            'data' => ExtractionResource::collection($history),
        ]);
    }
}
