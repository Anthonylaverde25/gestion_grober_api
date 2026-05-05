<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Core\Application\UseCases\User\GetAllUsers;
use App\Core\Application\UseCases\User\CreateUser;
use App\Http\Resources\V1\UserResource;
use App\Http\Requests\V1\CreateUserRequest;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class UserController extends Controller
{
    public function __construct(
        private GetAllUsers $getAllUsers,
        private CreateUser $createUser
    ) {}

    /**
     * Lista todos los usuarios del sistema.
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $users = $this->getAllUsers->execute();
        
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
