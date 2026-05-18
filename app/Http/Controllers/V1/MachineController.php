<?php

namespace App\Http\Controllers\V1;

use App\Core\Application\DTOs\Machine\CreateMachineDTO;
use App\Core\Application\DTOs\Machine\ChangeMachineArticleDTO;
use App\Core\Application\UseCases\Machine\UpdateMachine;
use App\Core\Application\UseCases\Machine\ChangeMachineCurrentArticle;
use App\Core\Application\UseCases\Machine\CreateMachine;
use App\Core\Application\UseCases\Machine\ListMachinesByCompany;
use App\Core\Application\UseCases\Machine\ListMachinesByFurnace;
use App\Http\Controllers\Controller;
use App\Http\Resources\V1\MachineResource;
use App\Http\Requests\V1\Machine\ListMachinesRequest;
use App\Http\Requests\V1\Machine\CreateMachineRequest;
use App\Http\Requests\V1\Machine\ChangeMachineArticleRequest;
use DomainException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MachineController extends Controller
{
    public function __construct(
        private ListMachinesByCompany $listMachinesByCompanyUseCase,
        private ListMachinesByFurnace $listMachinesByFurnaceUseCase,
        private CreateMachine $createMachineUseCase,
        private ChangeMachineCurrentArticle $changeMachineCurrentArticleUseCase,
        private UpdateMachine $updateMachineUseCase
    ) {}

    public function index(ListMachinesRequest $request): JsonResponse
    {
        $companyId = $request->input('company_id');
        $furnaceId = $request->input('furnace_id');

        if ($companyId) {
            $machines = $this->listMachinesByCompanyUseCase->execute($companyId);
        } else {
            $machines = $this->listMachinesByFurnaceUseCase->execute($furnaceId);
        }

        return response()->json([
            'data' => MachineResource::collection($machines),
        ]);
    }

    public function store(CreateMachineRequest $request): JsonResponse
    {
        $dto = CreateMachineDTO::fromRequest($request->validated());

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

    public function changeCurrentArticle(ChangeMachineArticleRequest $request, string $machineId): JsonResponse
    {
        $dto = ChangeMachineArticleDTO::fromRequest($machineId, $request->validated());

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

    public function update(Request $request, string $id): JsonResponse
    {
        $machine = $this->updateMachineUseCase->execute($id, $request->all());

        return response()->json([
            'message' => 'Máquina actualizada correctamente',
            'data' => new MachineResource($machine),
        ]);
    }
}
