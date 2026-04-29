<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\V1\CompanyController;
use App\Http\Controllers\V1\ArticleController;
use App\Http\Controllers\V1\FurnaceController;
use App\Http\Controllers\V1\MachineController;
use App\Http\Controllers\V1\ExtractionController;
use App\Http\Controllers\V1\AuthController;

use App\Http\Resources\V1\UserResource;
use App\Core\Infrastructure\Persistence\Eloquent\Mappers\UserMapper;

Route::prefix('v1/auth')->group(function () {
    Route::post('/login', [AuthController::class, 'login']);
});

Route::middleware('auth:sanctum')->group(function () {
    Route::post('v1/auth/logout', [AuthController::class, 'logout']);
    Route::post('v1/auth/switch-context', [AuthController::class, 'switchContext']);
    
    Route::get('/user', function (Request $request) {
        $userDomain = UserMapper::toDomain($request->user());
        return new UserResource($userDomain);
    });

    Route::prefix('v1')->group(function () {
        Route::apiResource('companies', CompanyController::class)->only(['index', 'show']);
        Route::apiResource('articles', ArticleController::class)->only(['index', 'store']);
        Route::apiResource('furnaces', FurnaceController::class)->only(['index', 'store']);
        Route::apiResource('machines', MachineController::class)->only(['index', 'store']);
        Route::patch('machines/{machineId}/current-article', [MachineController::class, 'changeCurrentArticle']);
        Route::post('extractions', [ExtractionController::class, 'store']);
        Route::get('machines/{machineId}/extractions/history', [ExtractionController::class, 'history']);
    });
});
