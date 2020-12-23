<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        factory(App\User::class, 5)->create()->each(function ($user) {
            //$role = factory(App\Models\Role::class)->make();
            $user->role()->save(factory(App\Models\Role::class)->make());
//            $user->role->role_name = 'user';
//            $user->role->save();
        });
      //  factory(App\User::class, 1)->create();
        //$this->call(UserTableSeeder::class);
    }
}
