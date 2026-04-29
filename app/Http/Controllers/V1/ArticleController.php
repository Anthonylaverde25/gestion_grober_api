<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Core\Application\UseCases\Article\CreateArticleUseCase;
use App\Core\Application\UseCases\Article\GetArticlesByCompanyUseCase;
use App\Core\Application\UseCases\Article\DTOs\CreateArticleRequest;
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
        $companyId = $request->query('company_id');

        if (!$companyId) {
            return response()->json(['message' => 'Company ID is required'], 400);
        }

        $articles = $this->getArticlesByCompanyUseCase->execute($companyId);

        return response()->json([
            'data' => array_map(fn($article) => [
                'id' => $article->getId(),
                'company_id' => $article->getCompanyId(),
                'name' => $article->getName(),
            ], $articles)
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'company_id' => 'required|uuid|exists:companies,id',
            'name' => 'required|string|max:255',
        ]);

        $dto = new CreateArticleRequest(
            $validated['company_id'],
            $validated['name']
        );

        $article = $this->createArticleUseCase->execute($dto);

        return response()->json([
            'message' => 'Article created successfully',
            'data' => [
                'id' => $article->getId(),
                'company_id' => $article->getCompanyId(),
                'name' => $article->getName(),
            ]
        ], 201);
    }
}
