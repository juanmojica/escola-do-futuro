<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Teacher;
use App\User;
use Faker\Generator as Faker;

$factory->define(Teacher::class, function (Faker $faker) {
    return [
        'user_id' => function () {
            return factory(User::class)->create([
                'is_admin' => false,
                'role' => 'teacher',
            ])->id;
        },
    ];
});
