<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function __construct()
    {
        // Only super admin can view users
        $this->middleware('role:SuperAdmin');
    }

    public function index () {
        // Fetch all users except currently logged in user
        return User::where('id', '!=', auth()->id())->with(['achievements', 'roles'])->get();
    }

    public function update(Request $request, $id) {
        $request -> validate([
            'name' => ['required'],
            'email' => ['required', 'email', "unique:users,email,$id,id",],
            'points' => ['required', 'integer', 'gt:-1']
        ]);


        if($user = User::where('id', $id)->first())
        {
            $user->name = $request->name;
            $user->email = $request->email;
            $user->points = $request->points;

            $user->save();
            return response()->json($user, 200);
        }

        return response()->json(['error' => 'User not found'], 404);

    }

    public function delete(Request $request, $id) {

        // Cannot delete self
        if($request->user()->id == $id) {
            return response()->json(['error' => 'Cannot delete self'], 400);
        }
        else if($user = User::where('id', $id)->first()) {
            $user->delete();
            return response()->json('successfully deleted', 204);
        }

        return response()->json(['error' => 'User not found'], 404);
    }

}
