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
        // create test users
        factory(App\User::class, 5)->create()->each(function ($user) {
            //$role = factory(App\Models\Role::class)->make();
            $user->role()->save(factory(App\Models\Role::class)->make());
        });
        // create admin
        factory(App\User::class, 1)->create([
            'name' => 'admin'
        ])->each(function ($user) {
            $user->role()->save(factory(App\Models\Role::class)->make([
                'role_name' => 'admin'
            ]));
        });
    }
}
