<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(\App\Models\Activity::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->randomElement([
            'Writing e-mails',
            'Reading e-mails',
            'Calling the client',
            'Conference Call',
            'Skype Call',
            'Hangouts Call',
            'Conference Call',
            'Programming in PHP',
            'Programming in C#',
            'Estimating project',
            'Consultations with developers',
            'Testing the application',
        ]),
        'duration' => $faker->numberBetween(15, 360),
        'start' => $faker->dateTimeBetween('-2 months'),
        'user_id' => $faker->randomDigit,
    ];
});
