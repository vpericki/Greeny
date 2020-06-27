<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:SuperAdmin');
    }


    public function permissionIndex() {
        return Permission::all();
    }

    public function rolesIndex() {
        return Role::all();
    }


    public function assignRoleToUser(Request $request, $idUser,  $idRole) {

        $user = User::where('id', $idUser)->first();
        $role = Role::where('id', $idRole)->first();

        $user->assignRole($role);

        return response()->json([
            'message' => $role->name . ' role successfully assigned to user'
        ], 200);
    }

    public function removeRoleFromUser(Request $request, $idUser, $idRole) {

        if($request->user()->id == $idUser) {
            return response()->make('cannot remove or add roles to self', 400);
        }

        $user = User::where('id', $idUser)->first();
        $role = Role::where('id', $idRole)->first();

        $user -> removeRole($role);

        return response() -> json([
            'message' => $role . ' role successfully removed from user'
        ], 200);
    }


}
