<?php

namespace App\Services;

use Carbon\Carbon;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserService extends BaseModelService
{
    private RoleService $roleService;

    public function __construct(RoleService $roleService)
    {
        $this->roleService = $roleService;
    }

    public function model(): string
    {
        return User::class;
    }

    /**
     * Get users and their roles
     * TODO:: Convert the last_login into human readable format
     */
    public function getUsers()
    {
        $users = $this->model()::with('roles')->get()->map(function ($user) {
            if ($user->last_login_at) {
                $lastLogin = Carbon::parse($user->last_login_at);
                $user->last_login_at = $lastLogin->diffForHumans();
            } else {
                $user->last_login_at = "Never logged in";
            }
            return $user;
        });
        return $users;
    }

    /**
     * Create user
     * Assign Roles
     */
    public function createUser($validatedData)
    {
        $validatedData['password'] = Hash::make($validatedData['password']);
        $result = DB::transaction(function () use ($validatedData) {
            $user = $this->create($validatedData);
            if ($validatedData['roles']) {
                $user->assignRole($validatedData['roles']);
            }

            return $user;
        });

        return $result;
    }

    public function updateUser(User $user, $validatedData)
    {
        $result = DB::transaction(function () use ($user, $validatedData) {
            $user->update($validatedData);

            if (array_key_exists('roles', $validatedData)) {
                $user->syncRoles($validatedData['roles']);
            }

            return $user;
        });
        return $result ?? false;
    }

    public function deleteUser(User $user)
    {
        // TODO::Check if the user is referred to in other tables --> Employee

        // if user is assigned any role remove data from pivot table when deleting user.
        $result = DB::transaction(function () use ($user) {
            $isDeleted = $this->delete($user->id);
            if ($isDeleted) {
                $user->roles()->detach();
            }
            return true;
        });
        return $result ? true : false;
    }

    public function assignRoleToUser($validatedData)
    {
        $userId = $validatedData['user_id'];
        $roleId = $validatedData['role_id'];

        $user = $this->getUserById($userId);
        $role = $this->roleService->getRoleById($roleId);

        if ($user->hasRole($role)) {
            return false;
        }

        $user->assignRole($role);
        return true;
    }

    public function removeRoleFromUser($validatedData)
    {
        $userId = $validatedData['user_id'];
        $roleId = $validatedData['role_id'];

        $user = $this->getUserById($userId);
        $role = $this->roleService->getRoleById($roleId);

        if ($user->hasRole($role)) {
            $user->removeRole($role);
            return true;
        }
        return false;
    }

    public function getUserById($userId)
    {
        return User::find($userId)->with('roles');
    }

    public function getUserByEmail($validatedData)
    {
        return $this->model()::where('email', $validatedData['email'])->first();
    }

    public function getUserByMobileNumber($mobileNumber)
    {
        return $this->model()::where('mobile_number', $mobileNumber)->first();
    }

    public function getUserDetails(User $user)
    {
        $user = $user->load('roles');
        return $user;
    }

    public function updatePassword(User $user, $validatedData)
    {
        $password = Hash::make($validatedData['password']);
        $user->update(['password' => $password]);

        return $user;
    }

    public function changeStatus(User $user, $isActive)
    {
        $result = DB::transaction(function () use ($user, $isActive) {
            $isActive = ( $isActive == true ) ? false : true;
            $user->update(['is_active' => $isActive]);

            return $user;
        });
        return $result;

    }
}
