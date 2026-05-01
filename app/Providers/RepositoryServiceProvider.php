<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Core\Domain\Repositories\CompanyRepositoryInterface;
use App\Core\Infrastructure\Persistence\Eloquent\EloquentCompanyRepository;
use App\Core\Domain\Repositories\FurnaceRepositoryInterface;
use App\Core\Infrastructure\Persistence\Eloquent\EloquentFurnaceRepository;
use App\Core\Domain\Repositories\MachineRepositoryInterface;
use App\Core\Infrastructure\Persistence\Eloquent\EloquentMachineRepository;
use App\Core\Domain\Repositories\ArticleRepositoryInterface;
use App\Core\Infrastructure\Persistence\Eloquent\EloquentArticleRepository;
use App\Core\Domain\Repositories\ExtractionRepositoryInterface;
use App\Core\Infrastructure\Persistence\Eloquent\EloquentExtractionRepository;
use App\Core\Domain\Repositories\AuthRepositoryInterface;
use App\Core\Infrastructure\Persistence\Eloquent\EloquentAuthRepository;
use App\Core\Domain\Repositories\ClientRepositoryInterface;
use App\Core\Infrastructure\Persistence\Eloquent\EloquentClientRepository;
use App\Core\Domain\Repositories\CampaignRepositoryInterface;
use App\Core\Infrastructure\Persistence\Eloquent\EloquentCampaignRepository;
use App\Core\Domain\Repositories\LineYieldRepositoryInterface;
use App\Core\Infrastructure\Persistence\Eloquent\EloquentLineYieldRepository;
use App\Core\Domain\Services\TokenServiceInterface;
use App\Core\Infrastructure\Auth\SanctumTokenService;

class RepositoryServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(CompanyRepositoryInterface::class, EloquentCompanyRepository::class);
        $this->app->bind(FurnaceRepositoryInterface::class, EloquentFurnaceRepository::class);
        $this->app->bind(MachineRepositoryInterface::class, EloquentMachineRepository::class);
        $this->app->bind(ArticleRepositoryInterface::class, EloquentArticleRepository::class);
        $this->app->bind(ExtractionRepositoryInterface::class, EloquentExtractionRepository::class);
        $this->app->bind(AuthRepositoryInterface::class, EloquentAuthRepository::class);
        $this->app->bind(ClientRepositoryInterface::class, EloquentClientRepository::class);
        $this->app->bind(CampaignRepositoryInterface::class, EloquentCampaignRepository::class);
        $this->app->bind(LineYieldRepositoryInterface::class, EloquentLineYieldRepository::class);
        $this->app->bind(TokenServiceInterface::class, SanctumTokenService::class);
    }
}
