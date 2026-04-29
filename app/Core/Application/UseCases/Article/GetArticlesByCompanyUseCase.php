<?php

namespace App\Core\Application\UseCases\Article;

use App\Core\Domain\Repositories\ArticleRepositoryInterface;

class GetArticlesByCompanyUseCase
{
    public function __construct(
        private ArticleRepositoryInterface $articleRepository
    ) {}

    public function execute(string $companyId): array
    {
        return $this->articleRepository->findByCompanyId($companyId);
    }
}
