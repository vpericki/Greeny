<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    public function permissionIndex() {
        return Permission::all()->paginate(100);
    }

    public function rolesIndex() {
        return Role::all()->paginate(100);
    }

    public function assignRoleToUser(Request $request, Role $role, User $user) {

        $user -> assignRole($role);

        return response()->json([
            'message' => $role->name . ' role successfully assigned to user'
        ], 200);
    }

    public function removeRoleFromUser(Request $request, Role $role, User $user) {

        $user -> removeRole($role);

        return response() -> json([
            'message' => $role . ' role successfully removed from user'
        ]);
    }


}
