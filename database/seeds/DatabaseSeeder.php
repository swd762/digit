<?php
//namespace Database\Seeds;

use App\Models\Role;
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

        $this->call(RoleSeeder::class);
        $this->call(UserSeeder::class);
        $this->call(PatientAndProductSeeder::class);
        factory(App\User::class, 5)->create()->each(function ($user) {
            $role = Role::where('name', 'user')->first();
            $user->roles()->attach($role);
        });
    }
}
