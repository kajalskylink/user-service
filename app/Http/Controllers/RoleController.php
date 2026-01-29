<?php

namespace App\Http\Controllers;

use App\Constants\Constants;
use App\Http\Requests\Role\CreateRoleRequest;
use App\Http\Requests\Role\UpdateRoleRequest;
use App\Models\User;
use App\Services\PermissionService;
use App\Services\RoleService;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Spatie\Permission\Models\Role;

class RoleController extends Controller implements HasMiddleware
{
    protected RoleService $roleService;
    protected PermissionService $permissionService;

    public function __construct(RoleService $roleService, PermissionService $permissionService)
    {
        $this->roleService = $roleService;
        $this->permissionService = $permissionService;
    }

    public static function middleware(): array
    {
        return [
            new Middleware('role.isSuperAdmin', only: ['changeStatus']),
            new middleware('role.isDeletable', only: ['destroy']),
            new middleware('permission:can-create-role', only: ['create', 'store']),
            new Middleware('permission:can-edit-role', only: ['edit', 'update', 'changeStatus', 'removeUserFromRole']),
            new Middleware('permission:can-delete-role', only: ['destroy']),
            new Middleware('permission:can-view-role', only: ['index']),
            new Middleware('permission:can-view-details-role', only: ['show']),
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $roles = $this->roleService->getRolesWithPermissions();

        $responseData = [
            'roles' => $roles,
        ];

        return response()->json($responseData);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $permissions = $this->permissionService->getPermissions();

        $responseData = [
            'permissions' => $permissions,
        ];

        return response()->json($responseData);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateRoleRequest $request)
    {
        $validatedData = $request->validated();
        $role = $this->roleService->createRole($validatedData);
        $status = $role ? Constants::SUCCESS : Constants::ERROR;
        $message = $role ? 'Role created succesfully' : 'Role could not be created';

        return response()->json([
            'status' => $status,
            'message' => $message,
            'role' => $role,
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Role $role)
    {
        $role = $this->roleService->getRoleDetails($role);
        $users = $role->users;

        $responseData = [
            'role' => $role,
            'users' => $users,
        ];

        return response()->json($responseData);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Role $role)
    {
        $permissions = $this->permissionService->getPermissions();
        $currentRolePermissions = $role->permissions;

        $responseData = [
            'role' => $role,
            'permissions' => $permissions,
            'currentRolePermissions' => $currentRolePermissions,
        ];

        return response()->json($responseData);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRoleRequest $request, Role $role)
    {
        $validatedData = $request->validated();
        $role = $this->roleService->getRoleDetails($role);
        $role = $this->roleService->updateRole($role, $validatedData);
        $status = $role ? Constants::SUCCESS : Constants::ERROR;
        $message = $role ? 'Role updated successfully' : 'Role could not be updated';

        return response()->json([
            'status' => $status,
            'message' => $message,
            'role' => $role,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Role $role)
    {
        $role = $this->roleService->getRoleDetails($role);
        $isDeleted = $this->roleService->deleteRole($role);
        $status = $isDeleted ? Constants::SUCCESS : Constants::ERROR;
        $message = $isDeleted ? 'Role deleted succesfully' : 'Role could not be deleted';

        return response()->json([
            'status' => $status,
            'message' => $message,
        ]);
    }

    /**
     * Change Role Status
     */
    public function changeStatus(Request $request, Role $role)
    {
        $role = $this->roleService->getRoleDetails($role);
        $result = $this->roleService->changeStatus($role, $request->is_active);
        $status = $result? Constants::SUCCESS : Constants::ERROR;
        $message = $result->is_active ? 'Role status changes successfully activated' : 'Role status changes deactivated';

        return response()->json([
            'status' => $status,
            'message' => $message,
            'result' => $result,
            'role' => $role,
        ]);
    }

    public function removeUserFromRole(Role $role, User $user)
    {
        $role = $this->roleService->getRoleDetails($role);
        $isRemoved = $this->roleService->removeUserFromRole($role, $user);
        $status = $isRemoved ? Constants::SUCCESS : Constants::ERROR;
        $message = $isRemoved ? 'Remove user from role succesfully' : 'Remove user from role could not be possible';

        return response()->json([
            'status' => $status,
            'message' => $message,
            'role' => $role,
            'isRemoved' => $isRemoved,
        ]);
    }
}
