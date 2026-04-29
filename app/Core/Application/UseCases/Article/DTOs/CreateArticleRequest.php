<?php

namespace App\Core\Application\UseCases\Article\DTOs;

class CreateArticleRequest
{
    public function __construct(
        public string $companyId,
        public string $name
    ) {}
}
