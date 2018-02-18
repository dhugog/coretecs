<?php

use Illuminate\Database\Seeder;

use CoreTecs\Models\User;
use CoreTecs\Models\Role;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {        
        $role_adm = Role::where('name', 'administrator')->first();

        $user = new User();
        $user->name = 'Daniel Hugo Gasparotto';
        $user->email = 'daniel.gasparotto@coretecs.com';        
        $user->permission = 5;
        $user->id_role = $role_adm->id;
        $user->password = bcrypt('123');
        $user->save();        
    }
}
