<?php

namespace App\Core\Infrastructure\Persistence\Eloquent;

use App\Core\Domain\Entities\Article as DomainArticle;
use App\Core\Domain\Repositories\ArticleRepositoryInterface;
use App\Core\Infrastructure\Persistence\Eloquent\Mappers\ArticleMapper;
use App\Models\Article as EloquentArticle;

class EloquentArticleRepository implements ArticleRepositoryInterface
{
    public function findById(string $id): ?DomainArticle
    {
        $eloquent = EloquentArticle::with('client')->find($id);
        return $eloquent ? ArticleMapper::toDomain($eloquent) : null;
    }

    public function findByCompanyId(string $companyId): array
    {
        $articles = EloquentArticle::with('client')->where('company_id', $companyId)->get();
        return $articles->map(fn($article) => ArticleMapper::toDomain($article))->toArray();
    }

    public function save(DomainArticle $article): void
    {
        EloquentArticle::updateOrCreate(
            ['id' => $article->getId()],
            ArticleMapper::toEloquent($article)
        );
    }

    public function delete(string $id): void
    {
        EloquentArticle::destroy($id);
    }
}
