<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Core\Application\UseCases\UserAlias\SearchAliasByLegajo;
use App\Http\Resources\V1\UserAliasResource;

class UserAliasController extends Controller
{
    public function __construct(
        private SearchAliasByLegajo $searchAliasByLegajo
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

        return new UserAliasResource($alias);
    }
}
