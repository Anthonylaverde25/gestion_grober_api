<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\V1\CompanyController;
use App\Http\Controllers\V1\ArticleController;
use App\Http\Controllers\V1\FurnaceController;
use App\Http\Controllers\V1\MachineController;
use App\Http\Controllers\V1\ExtractionController;
use App\Http\Controllers\V1\AuthController;
use App\Http\Controllers\V1\ClientController;
use App\Http\Controllers\V1\CampaignController;
use App\Http\Controllers\V1\LineYieldController;
use App\Http\Controllers\V1\SystemController;
use App\Http\Controllers\V1\UserAliasController;
use App\Http\Controllers\V1\UserController;
use App\Http\Controllers\V1\DashboardController;
use App\Http\Controllers\V1\Mobile\MobileDashboardController;

use App\Http\Resources\V1\UserResource;
use App\Core\Infrastructure\Persistence\Eloquent\Mappers\UserMapper;

Route::prefix('v1/auth')->group(function () {
    Route::post('/login', [AuthController::class, 'login']);
});

Route::get('/health', function () {
    return response()->json(['status' => 'ok', 'timestamp' => now()]);
});

Route::middleware('auth:sanctum')->group(function () {
    Route::post('v1/auth/logout', [AuthController::class, 'logout']);
    Route::post('v1/auth/switch-context', [AuthController::class, 'switchContext']);

    Route::get('/user', function (Request $request) {
        $userDomain = UserMapper::toDomain($request->user());
        return new UserResource($userDomain);
    });

    Route::prefix('v1')->group(function () {
        Route::get('dashboard/overview', [DashboardController::class, 'overview']);
        Route::get('dashboard/lines-performance/summary', [DashboardController::class, 'linesPerformanceSummary']);
        Route::get('system/server-time', [SystemController::class, 'getServerTime']);
        Route::get('user-aliases/search', [UserAliasController::class, 'search']);
        Route::get('user-aliases', [UserAliasController::class, 'index']);
        Route::post('user-aliases', [UserAliasController::class, 'store']);
        Route::patch('user-aliases/{id}/toggle-status', [UserAliasController::class, 'toggleStatus']);
        Route::apiResource('companies', CompanyController::class)->only(['index', 'show']);
        Route::apiResource('articles', ArticleController::class)->only(['index', 'store', 'show']);
        Route::apiResource('furnaces', FurnaceController::class)->only(['index', 'store', 'update']);
        Route::apiResource('machines', MachineController::class)->only(['index', 'store', 'update']);
        Route::patch('machines/{machineId}/current-article', [MachineController::class, 'changeCurrentArticle']);
        Route::post('extractions', [ExtractionController::class, 'store']);
        Route::get('machines/{machineId}/extractions/history', [ExtractionController::class, 'history']);

        Route::get('clients', [ClientController::class, 'index']);
        Route::post('clients', [ClientController::class, 'store']);
        Route::get('campaigns', [CampaignController::class, 'index']);
        Route::post('campaigns/start', [CampaignController::class, 'start']);
        Route::post('campaigns/{campaignId}/finish', [CampaignController::class, 'finish']);
        Route::get('campaigns/{id}', [CampaignController::class, 'show']);
        Route::post('line-yields/batch', [LineYieldController::class, 'storeBatch']);
        Route::post('line-yields', [LineYieldController::class, 'store']);
        Route::get('campaigns/{campaignId}/line-yields/history', [LineYieldController::class, 'history']);
        Route::get('machines/{machineId}/line-yields/history', [LineYieldController::class, 'machineHistory']);
        Route::get('users', [UserController::class, 'index']);
        Route::post('users', [UserController::class, 'store']);

        // Mobile specialized endpoints
        Route::get('mobile/active-campaigns', [MobileDashboardController::class, 'activeCampaigns']);
        Route::get('mobile/campaigns/{id}', [MobileDashboardController::class, 'campaignDetail']);
        Route::get('mobile/campaigns/{id}/yields/summary', [MobileDashboardController::class, 'campaignYieldSummary']);
        Route::get('mobile/campaigns/{id}/yields', [MobileDashboardController::class, 'campaignYields']);
    });
});
