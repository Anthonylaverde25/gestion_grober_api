<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Core\Application\UseCases\Furnace\CreateFurnace;
use App\Core\Application\UseCases\Furnace\ListFurnacesByCompany;
use App\Core\Application\DTOs\Furnace\CreateFurnaceDTO;
use App\Http\Resources\V1\FurnaceResource;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class FurnaceController extends Controller
{
    public function __construct(
        private ListFurnacesByCompany $listFurnacesUseCase,
        private CreateFurnace $createFurnaceUseCase
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

    public function store(Request $request): JsonResponse
    {
        $companyId = $this->activeCompanyId();

        if (!$companyId) {
            return response()->json(['message' => 'Active company context is required'], 400);
        }

        $request->validate([
            'name' => 'required|string|max:100',
            'glass_type_id' => 'required|integer',
            'max_capacity_tons' => 'required|numeric|min:0.1'
        ]);

        $data = $request->all();
        $data['company_id'] = $companyId;

        $dto = CreateFurnaceDTO::fromRequest($data);
        $furnace = $this->createFurnaceUseCase->execute($dto);

        return response()->json([
            'message' => 'Horno creado correctamente',
            'data' => new FurnaceResource($furnace)
        ], 201);
    }
}
