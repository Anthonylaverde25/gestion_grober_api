<?php

namespace App\Core\Application\UseCases\Article;

use App\Core\Domain\Repositories\ArticleRepositoryInterface;
use App\Core\Domain\Entities\Article;

class GetArticleByIdUseCase
{
    public function __construct(
        private ArticleRepositoryInterface $articleRepository
    ) {}

    public function execute(string $id): ?Article
    {
        return $this->articleRepository->findById($id);
    }
}
