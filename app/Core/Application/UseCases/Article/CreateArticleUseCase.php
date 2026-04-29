<?php

namespace App\Core\Application\UseCases\Article;

use App\Core\Application\UseCases\Article\DTOs\CreateArticleRequest;
use App\Core\Domain\Entities\Article;
use App\Core\Domain\Repositories\ArticleRepositoryInterface;
use Illuminate\Support\Str;

class CreateArticleUseCase
{
    public function __construct(
        private ArticleRepositoryInterface $articleRepository
    ) {}

    public function execute(CreateArticleRequest $request): Article
    {
        $article = Article::create(
            (string) Str::uuid(),
            $request->companyId,
            $request->name
        );

        $this->articleRepository->save($article);

        return $article;
    }
}
