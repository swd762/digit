<?php

use App\Models\Role;
use App\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

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
        $userRole = Role::where('name', 'user')->first();
        $adminRole = Role::where('name', 'admin')->first();

        // create admin user
        $adminUser = new User();
        $adminUser->name = 'admin';
        $adminUser->email = 'jhon@deo.com';
        $adminUser->password = bcrypt('secret');
        $adminUser->first_name = 'Дмитрий';
        $adminUser->last_name = 'Борисович';
        $adminUser->save();
        $adminUser->roles()->attach($adminRole);

        // create static user Saul
        $userUser = new User();
        $userUser->name = 'saul';
        $userUser->email = 'Saul@deo.com';
        $userUser->first_name = 'Алексей';
        $userUser->last_name = 'Иванович';
        $userUser->password = bcrypt('123456');
        $userUser->save();
        $userUser->roles()->attach($userRole);

        for ($i = 0; $i < 10; $i++) {
            $user = User::create([
                'name' => "user$i",
                'email' => "user$i@deo.com",
                'first_name' => "Иван",
                'last_name' => 'Иванов',
                'password' => Hash::make('123456')
            ]);

            $user->roles()->attach($userRole);
        }
    }
}
