<?php
use App\Models\Zoom;
use Faker\Generator as Faker;

$factory->define(Zoom::class, function (Faker $faker) {
    return [
        'email' => $faker->unique()->safeEmail,
        'password' => $faker->randomNumber(8),
        'pmi' => $faker->randomNumber(8),
    ];
});
