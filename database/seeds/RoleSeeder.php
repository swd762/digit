<?php

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // создание ролей
        $userRole = new Role();
        $userRole->name = 'user';
        $userRole->save();

        $adminRole = new Role();
        $adminRole->name = 'admin';
        $adminRole->save();
    }
}
