<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

$factory->define(\App\Models\User::class, function (Faker\Generator $faker) {
    static $password;

    return [
        'name' => $faker->name,
        'email' => $faker->safeEmail,
        'password' => '123456'
    ];
});
$factory->define(\App\Models\worklog::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->randomElement(worklogsSeeder::$worklogList),
        'time' => $faker->dateTimeBetween('-2 months'),
        'worklogs' => $faker->numberBetween(125, 500),
        'user_id' => $faker->randomDigit
    ];
});