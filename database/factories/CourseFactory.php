<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Course;
use Faker\Generator as Faker;

$factory->define(Course::class, function (Faker $faker) {
    return [
        'title' => $faker->sentence(3),
        'description' => $faker->paragraph,
        'start_date' => $faker->dateTimeBetween('-1 month', '+1 month'),
        'end_date' => $faker->dateTimeBetween('+2 months', '+6 months'),
    ];
});
