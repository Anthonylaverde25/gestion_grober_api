<?php

namespace App\Core\Domain\Repositories;

use App\Core\Domain\Entities\Article;

interface ArticleRepositoryInterface
{
    public function findById(string $id): ?Article;
    public function findByCompanyId(string $companyId): array;
    public function save(Article $article): void;
    public function delete(string $id): void;
}
