<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Core\Application\UseCases\Furnace\UpdateFurnace;
use App\Core\Application\UseCases\Furnace\CreateFurnace;
use App\Core\Application\UseCases\Furnace\ListFurnacesByCompany;
use App\Core\Application\DTOs\Furnace\CreateFurnaceDTO;
use App\Http\Requests\V1\Furnace\CreateFurnaceRequest;
use App\Http\Resources\V1\FurnaceResource;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class FurnaceController extends Controller
{
    public function __construct(
        private ListFurnacesByCompany $listFurnacesUseCase,
        private CreateFurnace $createFurnaceUseCase,
        private UpdateFurnace $updateFurnaceUseCase
    ) {}

    public function index(Request $request): JsonResponse
    {
        $companyId = $this->activeCompanyId();

        if (!$companyId) {
            return response()->json(['message' => 'Active company context is required'], 400);
        }

        $furnaces = $this->listFurnacesUseCase->execute($companyId);

        return response()->json([
            'data' => FurnaceResource::collection($furnaces)
        ]);
    }

    public function store(CreateFurnaceRequest $request): JsonResponse
    {
        $companyId = $this->activeCompanyId();

        if (!$companyId) {
            return response()->json(['message' => 'Active company context is required'], 400);
        }

        $data = $request->validated();
        $data['company_id'] = $companyId;

        $dto = CreateFurnaceDTO::fromRequest($data);
        $furnace = $this->createFurnaceUseCase->execute($dto);

        return response()->json([
            'message' => 'Horno creado correctamente',
            'data' => new FurnaceResource($furnace)
        ], 201);
    }

    public function update(Request $request, string $id): JsonResponse
    {
        $furnace = $this->updateFurnaceUseCase->execute($id, $request->all());

        return response()->json([
            'message' => 'Horno actualizado correctamente',
            'data' => new FurnaceResource($furnace)
        ]);
    }
}
