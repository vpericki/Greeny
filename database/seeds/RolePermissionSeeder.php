<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roles = ['SuperAdmin', 'Admin', 'User'];

        $permissions = [
            'viewDashboard',

            'assignRoles',
            'unassignRoles',
            'viewRoles',
            'viewPermissions',

            'viewUsers',
            'editUsers',

            'viewAchievements',
            'updateAchievements',
            'createAchievements',
            'deleteAchievements'
        ];

        $adminPermissions = [
            'viewAchievements',
            'updateAchievements',
            'createAchievements',
            'deleteAchievements',

            'viewUsers',
            'editUsers',
        ];

        $userPermissions = [
            'viewAchievements',
        ];

        foreach ($roles as $role) {
            $role = Role::updateOrCreate(['name' => $role, 'guard_name' => 'api']);

        }

        // Create permissions and add permissions to SuperAdmin
        $superAdminRole = Role::where('name', 'SuperAdmin')->first();
        foreach ($permissions as $permName) {
            $permission = Permission::updateOrCreate(['name' => $permName, 'guard_name' => 'api']);

            $superAdminRole->givePermissionTo($permission);
        }

        // Add permissions to Admin
        $adminRole = Role::where('name', 'Admin')->first();
        foreach ($adminPermissions as $permName) {

            $permission = Permission::where('name', $permName)->first();

            $adminRole->givePermissionTo($permission);
        }

        // Add permissions to User
        $userRole = Role::where('name', 'User')->first();
        foreach ($userPermissions as $permName) {

            $permission = Permission::where('name', $permName)->first();

            $userRole->givePermissionTo($permission);
        }
    }
}
