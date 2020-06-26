<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function __construct()
    {
        // Only super admin can view users
        $this->middleware('role:SuperAdmin');
    }

    public function index () {
        return User::all();
    }

    public function update(Request $request, $id) {
        $request -> validate([
            'name' => ['required'],
            'email' => ['required', 'email', "unique:users,email,$id,id",],
            'points' => ['required', 'integer', 'gt:-1']
        ]);

        $user = User::where('id', $id)->first();

        $user->name = $request->name;
        $user->email = $request->email;
        $user->points = $request->points;

        $user->save();

        return response()->json($user, 200);
    }

}
