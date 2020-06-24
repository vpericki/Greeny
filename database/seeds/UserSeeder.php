<?php

use App\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        //Super admin admin and user
        DB::table('users')->insert([
            'name' => 'pero',
            'email' => 'pero@pero.com',
            'password' => Hash::make('peropass'),
        ]);

        $user = User::where('email', 'pero@pero.com')->first();

        // Give all roles to pero
        $roles = Role::all();

        $user->assignRole($roles);

        // Admin and user
        DB::table('users')->insert([
            'name' => 'pero1',
            'email' => 'pero1@pero.com',
            'password' => Hash::make('peropass'),
        ]);
        $user = User::where('email', 'pero1@pero.com')->first();
        $roles = Role::where('name', 'Admin')
        ->orWhere('name', 'User')
        ->get();
        $user->assignRole($roles);

        // User
        DB::table('users')->insert([
            'name' => 'pero2',
            'email' => 'pero2@pero.com',
            'password' => Hash::make('peropass'),
        ]);
        $user = User::where('email', 'pero2@pero.com')->first();
        $roles = Role::where('name', 'User')->first();
        $user->assignRole($roles);
    }
}
