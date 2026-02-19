<?php

use Faker\Generator as Faker;
use App\Models\Student;
use App\User;

$factory->define(Student::class, function (Faker $faker) {
    return [
        'user_id' => function () {
            return factory(User::class)->create()->id;
        },
        'birth_date' => $faker->dateTimeBetween('-25 years', '-10 years')->format('Y-m-d'),
    ];
});
