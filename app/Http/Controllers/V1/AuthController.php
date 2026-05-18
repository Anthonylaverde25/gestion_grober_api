<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\V1\Auth\SwitchContextRequest;
use App\Http\Resources\V1\LoginResource;
use App\Core\Application\Services\AuthService;
use App\Core\Application\DTOs\Auth\LoginInputDTO;
use App\Core\Infrastructure\Persistence\Eloquent\Mappers\UserMapper;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Exception;

class AuthController extends Controller
{
    public function __construct(
        private AuthService $authService
    ) {}

    public function login(LoginRequest $request): LoginResource|JsonResponse
    {
        try {
            $dto = LoginInputDTO::fromRequest($request->validated());
            $output = $this->authService->login($dto);

            return new LoginResource($output);
        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 401);
        }
    }

    public function logout(Request $request): JsonResponse
    {
        // Convertimos el modelo Eloquent autenticado a Entidad de Dominio
        $userDomain = UserMapper::toDomain($request->user());
        
        $this->authService->logout($userDomain);

        return response()->json([
            'message' => 'Sesión cerrada exitosamente'
        ]);
    }

    public function switchContext(SwitchContextRequest $request): JsonResponse
    {
        $validated = $request->validated();

        try {
            $this->authService->switchContext($request->user(), $validated['company_id']);

            return response()->json([
                'message' => 'Contexto actualizado exitosamente',
                'active_company_id' => $validated['company_id']
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 403);
        }
    }
}
