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
    $raisers = array('Spiffy Fundraiser', 'Sloppy Fundraiser', 'Some random fundraiser', 'Elephant frenzy', 'Coconut Bonanza', 'NASA\'s Moonwalk Anniversary LaunchOff', 'Booster');
    $raiser_k = array_rand($raisers);
    return [
        'fundraiser' => $raisers[$raiser_k],
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'rating' => (mt_rand(1, 5) + ($raiser_k * 5.0 / count($raisers)) / 2),//some fundraisers are better than others. Being truely random here wouldn't reflect real life.
        'review' => $faker->realText(200, 2)
    ];
});
