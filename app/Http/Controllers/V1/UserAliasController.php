<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Core\Application\UseCases\UserAlias\SearchAliasByLegajo;
use App\Core\Application\UseCases\UserAlias\CreateUserAlias;
use App\Core\Application\UseCases\UserAlias\ToggleUserAliasStatus;
use App\Http\Resources\V1\UserAliasResource;
use DomainException;

class UserAliasController extends Controller
{
    public function __construct(
        private SearchAliasByLegajo $searchAliasByLegajo,
        private CreateUserAlias $createUserAlias,
        private ToggleUserAliasStatus $toggleUserAliasStatus,
        private \App\Core\Domain\Repositories\UserAliasRepositoryInterface $repository
    ) {}

    public function search(Request $request): JsonResponse|UserAliasResource
    {
        $validated = $request->validate([
            'legajo' => 'required|string|max:50'
        ]);

        $alias = $this->searchAliasByLegajo->execute($validated['legajo']);

        if (!$alias) {
            return response()->json([
                'message' => 'Legajo no encontrado.'
            ], 404);
        }

        if (!$alias->isActive()) {
            return response()->json([
                'message' => 'Este alias ha sido desactivado y no puede operar.'
            ], 403);
        }

        return new UserAliasResource($alias);
    }

    public function index(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'user_id' => 'required|integer|exists:users,id'
        ]);

        $aliases = $this->repository->findByUser($validated['user_id']);

        return response()->json([
            'data' => UserAliasResource::collection($aliases)
        ]);
    }

    public function store(Request $request): JsonResponse|UserAliasResource
    {
        $validated = $request->validate([
            'user_id' => 'required|integer|exists:users,id',
            'name' => 'required|string|max:255',
            'legajo' => 'required|string|max:50|unique:user_aliases,legajo'
        ]);

        try {
            $alias = $this->createUserAlias->execute(
                $validated['user_id'],
                $validated['name'],
                $validated['legajo']
            );

            return new UserAliasResource($alias);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 422);
        }
    }

    public function toggleStatus(Request $request, string $id): JsonResponse|UserAliasResource
    {
        try {
            $alias = $this->toggleUserAliasStatus->execute($id);
            return new UserAliasResource($alias);
        } catch (DomainException $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 422);
        }
    }
}
