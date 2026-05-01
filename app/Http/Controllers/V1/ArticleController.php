<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Core\Application\UseCases\Article\CreateArticleUseCase;
use App\Core\Application\UseCases\Article\GetArticlesByCompanyUseCase;
use App\Core\Application\UseCases\Article\DTOs\CreateArticleRequest;
use App\Http\Resources\V1\ArticleResource;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ArticleController extends Controller
{
    public function __construct(
        private CreateArticleUseCase $createArticleUseCase,
        private GetArticlesByCompanyUseCase $getArticlesByCompanyUseCase
    ) {}

    public function index(Request $request): JsonResponse
    {
        $companyId = $this->activeCompanyId();

        if (!$companyId) {
            return response()->json(['message' => 'Active company context is required'], 400);
        }

        $articles = $this->getArticlesByCompanyUseCase->execute($companyId);

        return response()->json([
            'data' => ArticleResource::collection($articles)
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $companyId = $this->activeCompanyId();

        if (!$companyId) {
            return response()->json(['message' => 'Active company context is required'], 400);
        }

        $validated = $request->validate([
            'client_id' => 'nullable|uuid|exists:clients,id',
            'name' => 'required|string|max:255',
        ]);

        $dto = new CreateArticleRequest(
            $companyId,
            $validated['name'],
            $validated['client_id'] ?? null
        );

        $article = $this->createArticleUseCase->execute($dto);

        return response()->json([
            'message' => 'Article created successfully',
            'data' => new ArticleResource($article)
        ], 201);
    }
}
