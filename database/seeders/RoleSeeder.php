<?php

namespace Database\Seeders;

use App\Constants\Constants;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('roles')->truncate();

        $roles = [
            [
                'id' => 1,
                'name' => Constants::ROLE_SUPER_ADMIN,
                'guard_name' => 'web',
                'is_editable' => true,
                'is_deletable' => false,
                'is_available' => true,
                'is_active' => true,
            ],
            [
                'id' => 2,
                'name' => Constants::ROLE_ADMIN,
                'guard_name' => 'web',
                'is_editable' => true,
                'is_deletable' => false,
                'is_available' => true,
                'is_active' => true,
            ],
            [
                'id' => 3,
                'name' => Constants::ROLE_EMPLOYEE,
                'guard_name' => 'web',
                'is_editable' => true,
                'is_deletable' => false,
                'is_available' => true,
                'is_active' => true,
            ],
            [
                'id' => 4,
                'name' => Constants::ROLE_HR,
                'guard_name' => 'web',
                'is_editable' => true,
                'is_deletable' => false,
                'is_available' => true,
                'is_active' => true,
            ],
            [
                'id' => 5,
                'name' => Constants::ROLE_CUSTOMER,
                'guard_name' => 'web',
                'is_editable' => true,
                'is_deletable' => false,
                'is_available' => true,
                'is_active' => true,
            ],
            [
                'id' => 6,
                'name' => Constants::ROLE_BUYER,
                'guard_name' => 'web',
                'is_editable' => true,
                'is_deletable' => false,
                'is_available' => true,
                'is_active' => true,
            ],
            [
                'id' => 7,
                'name' => Constants::ROLE_SUPPLIER,
                'guard_name' => 'web',
                'is_editable' => true,
                'is_deletable' => false,
                'is_available' => true,
                'is_active' => true,
            ],
            [
                'id' => 8,
                'name' => Constants::ROLE_VENDOR,
                'guard_name' => 'web',
                'is_editable' => true,
                'is_deletable' => false,
                'is_available' => true,
                'is_active' => true,
            ],
            [
                'id' => 9,
                'name' => Constants::ROLE_WHOLESALER,
                'guard_name' => 'web',
                'is_editable' => true,
                'is_deletable' => false,
                'is_available' => true,
                'is_active' => true,
            ],
            [
                'id' => 10,
                'name' => Constants::ROLE_RETAILER,
                'guard_name' => 'web',
                'is_editable' => true,
                'is_deletable' => false,
                'is_available' => true,
                'is_active' => true,
            ],

        ];

        foreach ($roles as $role) {
            Role::create($role);
        }

        $superAdmin = Role::where('name', Constants::ROLE_SUPER_ADMIN)->first();
        $permissions = Permission::all();
        $superAdmin->syncPermissions($permissions);

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
