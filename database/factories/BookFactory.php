<?php

use App\Models\Book;
use Faker\Generator as Faker;

$factory->define(Book::class, function (Faker $faker) {
    return [
        'name'      => $faker->firstNameMale,
        'type'      => rand(0, 1),
        'publisher' => $faker->firstNameMale,
    ];
});
