<?php

use Illuminate\Database\Seeder;
use App\Models\Role;

class UserRoleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Role::create([
            'role_type' => 'Admin',
            'code' => 'admin'
        ]);
    }
}
