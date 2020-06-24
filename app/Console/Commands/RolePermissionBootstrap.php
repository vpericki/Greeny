<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionBootstrap extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'greeny_bootstrap:permissions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creates roles and permissions';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
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

        $this -> line('Settings up roles...');

        foreach ($roles as $role) {
            $role = Role::updateOrCreate(['name' => $role, 'guard_name' => 'api']);

            $this -> info('Created ' . $role->name . ' Role');
        }

        $this->line('Setting up permissions...');

        // Create permissions and add permissions to SuperAdmin
        $superAdminRole = Role::where('name', 'SuperAdmin')->first();
        foreach ($permissions as $permName) {
            $permission = Permission::updateOrCreate(['name' => $permName, 'guard_name' => 'api']);

            $superAdminRole->givePermissionTo($permission);

            $this->info("Created " . $permission->name . " Permission");
        }

        // Add permissions to Admin
        $adminRole = Role::where('name', 'Admin')->first();
        foreach ($adminPermissions as $permName) {

            $permission = Permission::where('name', $permName)->first();

            $adminRole->givePermissionTo($permission);
        }
        $this->info("Permissions are granted to Admin");


        // Add permissions to User
        $userRole = Role::where('name', 'User')->first();
        foreach ($userPermissions as $permName) {

            $permission = Permission::where('name', $permName)->first();

            $userRole->givePermissionTo($permission);
        }
        $this->info("Permissions are granted to User");




        $this->info("All permissions are granted to Super Admin");
        $this->line('Completed bootstrapping!');
    }
}
