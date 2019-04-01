<?php
use App\Models\Student;
use App\Models\Agency;
use App\User;
use Faker\Generator as Faker;

$factory->define(Student::class, function (Faker $faker) {
    $name =  's_' . $faker->firstNameMale;
    return [
        'user_id' => function () use ($name) {
            return factory(User::class)
                ->create(['name'=>$name])
                ->id;
        },
        'grade' =>  rand(0,17),
        //todo book_id
        'level' => $faker->randomDigit,
        'agency_uid' =>  function () {
            return factory(Agency::class)
                ->create()
                ->id;
        },
        // 'recommender_uid' =>  function () {
        //     return factory(User::class)
        //         ->create()
        //         ->id;
        // }, //todo 
        'remark' => $faker->paragraph,
    ];
});

$factory->afterMaking(Student::class, function ($student, $faker) {
    $student->user->assignRole(User::ROLES['student']);
});

$factory->afterCreating(Student::class, function ($student, $faker) {
    $student->user->assignRole(User::ROLES['student']);
});
