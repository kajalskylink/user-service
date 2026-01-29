<?php

namespace App\Http\Controllers;

use App\Constants\Constants;
use App\Http\Requests\Permission\CreatePermissionRequest;
use App\Http\Requests\Permission\UpdatePermissionRequest;
use App\Services\PermissionService;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use PHPUnit\TextUI\Configuration\Constant;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller implements HasMiddleware
{
    protected PermissionService $permissionService;

    public function __construct(PermissionService $permissionService)
    {
        $this->permissionService = $permissionService;
    }

    public static function middleware(): array
    {
        return [
            new Middleware('permission:can-create-permission', only: ['create', 'store']),
            new Middleware('permission:can-edit-permission', only: ['edit', 'update', 'changeStatus']),
            new Middleware('permission:can-view-permission', only: ['index']),
            new Middleware('permission:can-delete-permission', only: ['destroy']),
        ];
    }

    public function index()
    {
        $permissions = $this->permissionService->getAllPermissions();

        $responseData = [
            'permissions' => $permissions,
        ];

        return response()->json($responseData);
    }

    public function create()
    {
        $groups = $this->permissionService->getGroups();

        $responseData = [
            'groups' => $groups,
        ];

        return response()->json($responseData);
    }

    public function store(CreatePermissionRequest $request)
    {
        $validatedData = $request->validated();
        $permission = $this->permissionService->createPermission($validatedData);
        $status = $permission ? Constants::SUCCESS : Constants::ERROR;
        $message = $permission ? 'Permission created successfully ' : 'Permission could not be created';

        return response()->json([
            'permission' => $permission,
            'status' => $status,
            'message' => $message,
        ]);
    }

    public function edit(Permission $permission)
    {
        $groups = $this->permissionService->getGroups();
        $responseData = [
            'permission' => $permission,
            'groups' => $groups,
        ];

        return response()->json($responseData);
    }

    public function update(UpdatePermissionRequest $request, Permission $permission)
    {
        $validatedData = $request->validated();
        $permission = $this->permissionService->updatePermission($permission, $validatedData);
        $status = $permission ? Constants::SUCCESS : Constants::ERROR;
        $message = $permission ? 'Permission updated successfully' : 'Permission could not be updated';

        return response()->json([
            'permission' => $permission,
            'status' => $status,
            'message' => $message,
        ]);
    }

    public function changeStatus(Request $request, Permission $permission)
    {
        $result = $this->permissionService->changeStatus($permission, $request->is_active);
        $status = Constants::SUCCESS;
        $message = $permission->is_active ? 'Permission status is activated' : 'Permission status is deactivated';

        return response()->json([
            'status' => $status,
            'message' => $message,
        ]);
    }

    public function destroy(Permission $permission)
    {
        $isDeleted = $this->permissionService->delete($permission->id);
        $status = $isDeleted ? 'success': 'error';
        $message = $isDeleted ? 'Permission deleted successfully': 'Permission could not be deleted';

        return response()->json([
            'status' => $status,
            'message' => $message,
        ]);
    }
}
