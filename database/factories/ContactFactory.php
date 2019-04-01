<?php
use App\Models\Contact;
use App\Models\Profile;
use Faker\Generator as Faker;

$factory->define(Contact::class, function (Faker $faker) {
    return [
        'profile_id' => function () {
            return factory(Profile::class)
                ->create()
                ->id;
        },
        'type' => rand(0,3),
        'number' => $faker->randomNumber(8),
        'remark' => $faker->paragraph,
    ];
});
