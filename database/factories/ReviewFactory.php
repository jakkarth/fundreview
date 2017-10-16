<?php

use Faker\Generator as Faker;

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

$factory->define(App\Review::class, function (Faker $faker) {
    return [
        'fundraiser' => $faker->company,
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'rating' => mt_rand(1, 5),
        'review' => $faker->realText(200, 2)
    ];
});
