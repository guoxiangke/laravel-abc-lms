<?php

use App\Models\Student;
use App\User;
use Faker\Generator as Faker;

$factory->define(Student::class, function (Faker $faker) {
    $name = 's_'.$faker->firstNameMale;

    return [
        'user_id' => function () use ($name) {
            return factory(User::class)
                ->create(['name'=>$name])
                ->id;
        },
        'name'  => 'e_'.$faker->name, //英文名字
        'grade' => rand(0, 17),
        //todo book_id
        'level'  => $faker->randomDigit,
        'remark' => $faker->paragraph,
    ];
});

$factory->afterMaking(Student::class, function ($student, $faker) {
    $student->user->assignRole(User::ROLES['student']);
});

$factory->afterCreating(Student::class, function ($student, $faker) {
    $student->user->assignRole(User::ROLES['student']);
});
