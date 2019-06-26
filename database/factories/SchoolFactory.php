<?php

use App\User;
use App\Models\School;
use Faker\Generator as Faker;

$factory->define(School::class, function (Faker $faker) {
    $name = 'S_'.$faker->firstNameMale;

    return [
        'user_id' => function () use ($name) {
            return factory(User::class)
                ->create(['name'=>$name])
                ->id;
        },
        'name'   => $name,
        'remark' => $faker->sentence,
    ];
});

$factory->afterMaking(School::class, function ($school, $faker) {
    $school->user->assignRole(User::ROLES['school']);
});

$factory->afterCreating(School::class, function ($school, $faker) {
    $school->user->assignRole(User::ROLES['school']);
});
