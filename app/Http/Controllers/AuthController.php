<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Spatie\Permission\Models\Role;

class AuthController extends Controller
{
    public function register(Request $request) {
        $request -> validate([
            'username' => ['required'],
            'email' => ['required', 'email', 'unique:users'],
            'password' => ['required', 'min:8', 'confirmed']
        ]);


        // Assign User role to new users
        $userRole = Role::where('name', 'User')->first();

        User::create([
            'name' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ])->assignRole($userRole);

        return response() -> json(['success' => 'Successfully created new user'], 201);
    }

    public function login(Request $request) {

        $request -> validate([
            'email' => ['required'],
            'password' => ['required']
        ]);


        $credentials = $request->only('email', 'password');

        if(Auth::attempt($credentials)) {
            return response()->json([Auth::user(), Auth::user()->getRoleNames(), 'token' => Auth::user()->createToken("token")], 200);
        }

        throw ValidationException::withMessages([
            'email' => ['The provided credentials are incorrect.']
        ]);
    }

    public function logout() {
        Auth::logout();
    }

    public function currentUser(Request $request) {
      return response()->json([Auth::user(), Auth::user()->getRoleNames(), 'token' => Auth::user()->createToken("token"), Auth::user()->achievements], 200);
    }
}
