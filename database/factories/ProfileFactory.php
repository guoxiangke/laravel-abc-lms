<?php
use App\Models\Profile;
use Faker\Generator as Faker;

$factory->define(Profile::class, function (Faker $faker) {
    return [
        'user_id' => $faker->randomDigit,
        'name' => $faker->name,
        'sex' => $faker->boolean,
        'birthday' =>  $faker->dateTime,
        'telephone' =>  $faker->unique()->e164PhoneNumber,
    ];
});
