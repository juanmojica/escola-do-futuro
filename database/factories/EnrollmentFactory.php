<?php

use Faker\Generator as Faker;
use App\Models\Enrollment;
use App\Models\Student;
use App\Models\Course;

$factory->define(Enrollment::class, function (Faker $faker) {
    return [
        'student_id' => function () {
            return factory(Student::class)->create()->id;
        },
        'course_id' => function () {
            return factory(Course::class)->create()->id;
        },
        'enrollment_date' => $faker->dateTimeBetween('-1 month', 'now')->format('Y-m-d'),
        'status' => $faker->randomElement(['ativa', 'concluida', 'cancelada']),
    ];
});
