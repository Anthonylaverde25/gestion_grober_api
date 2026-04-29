<?php

namespace App\Http\Controllers\V1;

use App\Core\Application\DTOs\Machine\CreateMachineDTO;
use App\Core\Application\DTOs\Machine\ChangeMachineArticleDTO;
use App\Core\Application\UseCases\Machine\ChangeMachineCurrentArticle;
use App\Core\Application\UseCases\Machine\CreateMachine;
use App\Core\Application\UseCases\Machine\ListMachinesByCompany;
use App\Core\Application\UseCases\Machine\ListMachinesByFurnace;
use App\Http\Controllers\Controller;
use App\Http\Resources\V1\MachineResource;
use DomainException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MachineController extends Controller
{
    public function __construct(
        private ListMachinesByCompany $listMachinesByCompanyUseCase,
        private ListMachinesByFurnace $listMachinesByFurnaceUseCase,
        private CreateMachine $createMachineUseCase,
        private ChangeMachineCurrentArticle $changeMachineCurrentArticleUseCase
    ) {}

    public function index(Request $request): JsonResponse
    {
        $companyId = $request->query('company_id');
        $furnaceId = $request->query('furnace_id');

        if (!$companyId && !$furnaceId) {
            return response()->json([
                'message' => 'Debe enviar company_id o furnace_id',
            ], 400);
        }

        if ($companyId) {
            $request->validate([
                'company_id' => 'required|uuid|exists:companies,id',
            ]);

            $machines = $this->listMachinesByCompanyUseCase->execute($companyId);
        } else {
            $request->validate([
                'furnace_id' => 'required|uuid|exists:furnaces,id',
            ]);

            $machines = $this->listMachinesByFurnaceUseCase->execute($furnaceId);
        }

        return response()->json([
            'data' => MachineResource::collection($machines),
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'company_id' => 'required|uuid|exists:companies,id',
            'furnace_id' => 'required|uuid|exists:furnaces,id',
            'current_article_id' => 'nullable|uuid|exists:articles,id',
            'name' => 'required|string|max:100',
            'status' => 'nullable|string|in:operational,maintenance,shutdown',
        ]);

        $dto = CreateMachineDTO::fromRequest($request->all());

        try {
            $machine = $this->createMachineUseCase->execute($dto);
        } catch (DomainException $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
            ], 422);
        }

        return response()->json([
            'message' => 'Máquina creada correctamente',
            'data' => new MachineResource($machine),
        ], 201);
    }

    public function changeCurrentArticle(Request $request, string $machineId): JsonResponse
    {
        $request->validate([
            'article_id' => 'nullable|uuid|exists:articles,id',
        ]);

        $dto = ChangeMachineArticleDTO::fromRequest($machineId, $request->all());

        try {
            $machine = $this->changeMachineCurrentArticleUseCase->execute($dto);
        } catch (DomainException $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
            ], 422);
        }

        return response()->json([
            'message' => 'Artículo actual de la máquina actualizado correctamente',
            'data' => new MachineResource($machine),
        ]);
    }
}
