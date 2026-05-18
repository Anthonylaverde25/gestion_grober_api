<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Core\Application\UseCases\User\GetAllUsers;
use App\Core\Application\UseCases\User\GetCompanyUsers;
use App\Core\Application\UseCases\User\CreateUser;
use App\Http\Resources\V1\UserResource;
use App\Http\Requests\V1\User\CreateUserRequest;
use App\Core\Context\TenantContext;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class UserController extends Controller
{
    public function __construct(
        private GetAllUsers $getAllUsers,
        private GetCompanyUsers $getCompanyUsers,
        private CreateUser $createUser
    ) {}

    /**
     * Lista los usuarios según el contexto de la empresa.
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $users = $this->getCompanyUsers->execute();
        
        return UserResource::collection($users);
    }

    /**
     * Crea un nuevo usuario y lo vincula a una empresa.
     */
    public function store(CreateUserRequest $request): UserResource
    {
        $user = $this->createUser->execute($request->validated());

        return new UserResource($user);
    }
}
