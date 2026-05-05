<?php

namespace App\Core\Application\UseCases\User;

use App\Core\Domain\Repositories\UserRepositoryInterface;

class GetCompanyUsers
{
    public function __construct(
        private UserRepositoryInterface $userRepository
    ) {}

    public function execute(string $companyId): array
    {
        return $this->userRepository.findByCompany($companyId);
    }
}
