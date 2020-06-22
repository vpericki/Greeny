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
        DB::table('users')->insert([
            'name' => 'pero',
            'email' => 'pero@pero.com',
            'password' => Hash::make('peropass'),
        ]);

        $user = User::where('email', 'pero@pero.com')->first();
        $role = Role::where('name', 'SuperAdmin')->first();

        $user->assignRole($role);
    }
}
