<?php

use Illuminate\Database\Seeder;
use App\Models\Permission;

class PermissionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Permission::create([
            'name' => 'User Management',
            'code' => 'user_management'
        ]);

        Permission::create([
            'name' => 'Role Management',
            'code' => 'role_manage'
        ]);

        Permission::create([
            'name' => 'Master Management',
            'code' => 'master_manage'
        ]);

        Permission::create([
            'name' => 'Permission Management',
            'code' => 'permission_management'
        ]);
    }
}
