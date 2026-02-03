<?php

namespace App\Http\Controllers;

use App\Constants\Constants;
use App\Http\Requests\User\CreateUserRequest;
use App\Http\Requests\User\UpdateUserRequest;
use App\Http\Requests\User\UserPasswordUpdateRequest;
use App\Models\User;
use App\Services\RoleService;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class UserController extends Controller implements HasMiddleware
{
    protected UserService $userService;
    protected RoleService $roleService;

    public function __construct(UserService $userService, RoleService $roleService)
    {
        $this->userService = $userService;
        $this->roleService = $roleService;
    }

    public static function middleware(): array
    {
        return [
            new Middleware('permission:can-view-user', only: ['index']),
            new Middleware('permission:can-create-user', only: ['create', 'store']),
            new Middleware('permission:can-delete-user', only: ['destroy']),
            new Middleware('permission:can-view-details-user', only: ['show']),
            new Middleware('permission:can-edit-user', only: ['update', 'updatePassword', 'changeStatus']),
        ];
    }

    public function index()
    {
        $users = $this->userService->getUsers();
        $roles = $this->roleService->getRoles();

        $responseData = [
            'users' => $users,
            'roles' => $roles,
        ];

        return response()->json( $responseData);
    }

    public function create()
    {
        $roles = $this->roleService->getActiveRoles();

        $responseData = [
            'roles' => $roles,
        ];

        return response()->json( $responseData);
    }

    public function store(CreateUserRequest $request)
    {
        $validatedData = $request->validated();
        $user = $this->userService->createUser($validatedData);
        $status = $user ? Constants::SUCCESS : Constants::ERROR;
        $message = $user ? 'User created succesfully' : 'User could not be created';

        return response()->json([
            'status' => $status,
            'message' => $message,
            'user' => $user
        ]);
    }

    public function show(User $user)
    {
        $user = $this->userService->getUserDetails($user);
        $roles = $this->roleService->getActiveRoles();

        $responseData = [
            'user' => $user,
            'roles' => $roles,
        ];

        return response()->json( $responseData);
    }

    public function edit()
    {
        $roles = $this->roleService->getActiveRoles();

        $responseData = [
            'roles' => $roles,
        ];

        return response()->json( $responseData);
    }

    public function update(UpdateUserRequest $request, User $user)
    {
        $validatedData = $request->validated();
        $user = $this->userService->getUserDetails($user);
        $isUpdated = $this->userService->updateUser($user, $validatedData);
        $status = $isUpdated ? Constants::SUCCESS : Constants::ERROR;
        $message = $isUpdated ? 'User Updated succesfully' : 'User could not be updated';

        return response()->json([
            'status' => $status,
            'message' => $message,
            'user' => $isUpdated
        ]);
    }

    public function updateRoles(UpdateUserRequest $request, User $user)
    {
        $validatedData = $request->validated();
        $user = $this->userService->getUserDetails($user);
        $isUpdated = $this->userService->updateUser($user,  $validatedData);
        $status = $isUpdated ? Constants::SUCCESS : Constants::ERROR;
        $message = $isUpdated ? 'User role updated succefully' : 'User role could not be updated';

        return response()->json([
            'status' => $status,
            'message' => $message,
            'user' => $user
        ]);
    }

    public function updatePassword(UserPasswordUpdateRequest $request, User $user)
    {
        $validatedData = $request->validated();
        $user = $this->userService->getUserDetails($user);
        $isUpdated = $this->userService->updatePassword($user, $validatedData);
        $status = $isUpdated ? Constants::SUCCESS : Constants::ERROR;
        $message = $isUpdated ? 'User password updated succesfully' : 'User password could not be updated';

        return response()->json([
            'status' => $status,
            'message' => $message,
            'user' => $user
        ]);
    }

    public function destroy(User $user)
    {
        $user = $this->userService->getUserDetails($user);
        $isDeleted = $this->userService->deleteUser($user);
        $status = $isDeleted ? Constants::SUCCESS : Constants::ERROR;
        $message = $isDeleted ? 'User deleted successfully' : 'User could not be deleted';

        return response()->json([
            'status' => $status,
            'message' => $message,
            'user' => $user
        ]);
    }

    /**
     * Change User Status
    */
    public function changeStatus(Request $request, User $user)
    {
        $user = $this->userService->changeStatus($user, $request->is_active);
        $status = $user ? Constants::SUCCESS : Constants::ERROR;
        $message = $user ? ($user->is_active ? 'User status successfully activated' : 'User status successfully deactivated') : 'User status could not be changed';

        return response()->json([
            'status' => $status,
            'message' => $message,
            'user' => $user
        ]);
    }
}
