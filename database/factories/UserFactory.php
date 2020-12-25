<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\User;
use Faker\Generator as Faker;
use Illuminate\Support\Str;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

// Users template
$factory->define(User::class, function (Faker $faker) {
    return [
        'name' => $faker->unique()->firstName,
        'email' => $faker->unique()->safeEmail,
        'password' => bcrypt('123456'), // password
        'remember_token' => Str::random(10),
        'first_name' => $faker->firstName,
        'last_name' => $faker->lastName

    ];
});


