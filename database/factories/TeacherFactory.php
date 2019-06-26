<?php

use App\User;
use App\Models\Zoom;
use App\Models\School;
use App\Models\Teacher;
use Faker\Generator as Faker;

$factory->define(Teacher::class, function (Faker $faker) {
    $name = 'T_'.$faker->firstNameMale;

    return [
        'user_id' => function () use ($name) {
            return factory(User::class)
                ->create(['name'=>$name])
                ->id;
        },
        'school_id' => function () {
            return factory(School::class)
                ->create()
                ->id;
        },
        'zoom_id' => function () {
            return factory(Zoom::class)
                ->create()
                ->id;
        },
    ];
});

$factory->afterCreating(Teacher::class, function ($teacher, $faker) {
    $teacher->user->assignRole(User::ROLES['teacher']);
});

$factory->afterMaking(Teacher::class, function ($teacher, $faker) {
    $teacher->user->assignRole(User::ROLES['teacher']);
});
