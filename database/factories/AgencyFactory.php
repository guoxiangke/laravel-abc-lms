<?php
use App\User;
use App\Models\Agency;
use Faker\Generator as Faker;

$factory->define(Agency::class, function (Faker $faker) {
    $name =  'A_' . $faker->firstNameMale;
    return [
        'user_id' => function () use ($name) {
            return factory(User::class)
                ->create(['name'=>$name])
                ->id;
        },
        'type' => rand(0,1),
        'discount' => rand(80,100),
    ];
});

$factory->afterMaking(Agency::class, function ($agency, $faker) {
    $agency->user->assignRole(User::ROLES['agency']);
});

$factory->afterCreating(Agency::class, function ($agency, $faker) {
    $agency->user->assignRole(User::ROLES['agency']);
});
