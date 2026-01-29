<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleService extends BaseModelService
{
    public function model(): string
    {
        return Role::class;
    }
    public function getRoles()
    {
        $roles = $this->model()::all();
        return $roles;
    }
    public function getRolesWithPermissions()
    {
        $roles = $this->model()::with(['permissions', 'users'])->get();
        return $roles;
    }

    public function createRole($validatedData)
    {
        $result = DB::transaction(function () use ($validatedData) {
            $role = $this->create($validatedData);
            if ($role) {
                $role->syncPermissions($validatedData['permission_ids']);
            }
            return $role;
        });

        return $result;
    }

    public function updateRole(Role $role, $validatedData)
    {
        $result = DB::transaction(function () use ($role, $validatedData) {
            $role->update($validatedData);
            $role->syncPermissions($validatedData['permission_ids']);

            return $role;
        });

        return $result;
    }

    /**
     * if role is deleted,
     * delete its assigned permissions
     * remove it from users.
     */
    public function deleteRole(Role $role)
    {
        $result = DB::transaction(function () use ($role) {
            $oldRole = clone $role;
            $isDeleted = $role->delete();

            if ($isDeleted) {
                $role->permissions()->detach();
                $role->users()->detach();
            }

            return $isDeleted;
        });

        return $result ? true : false;
    }

    public function assignPermissionToRole($validatedData)
    {
        $roleId = $validatedData['role_id'];
        $permissionId = $validatedData['permission_id'];

        $role = $this->getRoleById($roleId);
        $permission = $this->getPermissionById($permissionId);

        if ($role->hasPermissionTo($permission)) {
            return false;
        }

        $role->givePermissionTo($permission);
        return true;
    }

    public function removePermissionFromRole($validatedData)
    {
        $roleId = $validatedData['role_id'];
        $permissionId = $validatedData['permission_id'];

        $role = $this->getRoleById($roleId);
        $permission = $this->getPermissionById($permissionId);

        if ($role->hasPermissionTo($permission)) {
            $role->revokePermissionTo($permission);
            return true;
        }

        return false;
    }

    public function getRoleById($roleId)
    {
        return Role::find($roleId);
    }

    public function getPermissionById($permissionId)
    {
        return Permission::find($permissionId);
    }

    public function getActiveRoles()
    {
        $roles = Role::where('is_active', 1)->where('is_available', 1)->get();
        return $roles->isEmpty() ? false : $roles;
    }

    public function changeStatus(Role $role, $isActive)
    {
        $result = DB::transaction(function () use ($role, $isActive) {
            $isActive = ($isActive == true) ? false : true;
            $role->update(['is_active' => $isActive]);
            $role->users()->detach();

            return $role;
        });
        return $result;
    }

    public function getRoleDetails(Role $role)
    {
        $roleWithPermissions = $this->model()::with('permissions')->find($role->id);
        return $roleWithPermissions;
    }

    public function removeUserFromRole(Role $role, User $user)
    {
        $result = DB::transaction(function () use ($role, $user) {
            $role->users()->detach($user->id);

            return $role;
        });

        return $result;
    }
}
