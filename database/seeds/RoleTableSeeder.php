<?php

use Illuminate\Database\Seeder;

use \CoreTecs\Models\Role;

class RoleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // DB::table('role_user')->delete();
        // DB::table('roles')->delete();
        
        $role = Role::create([
            'name' => 'user',
        ]);
            
        $role = Role::create([
            'name' => 'employee',
            'permissions' => [                  
                'manage-stock' => true,
                'manage-users' => false,
            ]
        ]);

        $role = Role::create([
            'name' => 'manager',
            'permissions' => [  
                'manage-users' => false,
                'manage-stock' => true,
            ]
        ]);

        $role = Role::create([
            'name' => 'administrator',
            'permissions' => [
                'manage-users' => true,
                'manage-stock' => true,                
            ]
        ]);
    }
}
