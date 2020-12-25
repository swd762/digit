<?php

use App\Models\Role;
use App\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $userRole = Role::where('name','user')->first();
        $adminRole = Role::where('name','admin')->first();

        // create admin user
        $adminUser = new User();
        $adminUser->name = 'admin';
        $adminUser->email = 'jhon@deo.com';
        $adminUser->password = bcrypt('secret');
        $adminUser->save();
        $adminUser->roles()->attach($adminRole);

        // create static user Saul
        $userUser = new User();
        $userUser->name = 'saul';
        $userUser->email = 'Saul@deo.com';
        $userUser->password = bcrypt('123456');
        $userUser->save();
        $userUser->roles()->attach($userRole);
    }
}


