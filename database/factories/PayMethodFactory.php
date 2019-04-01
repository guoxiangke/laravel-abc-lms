<?php
use App\Models\PayMethod;
use App\User;
use Faker\Generator as Faker;

$factory->define(PayMethod::class, function (Faker $faker) {
    return [
        'user_id' =>  function () {
            return factory(User::class)
                ->create()
                ->id;
        },
        'type' => rand(0,4),
        'number' => $faker->creditCardNumber,
        'remark'=> $faker->paragraph,
    ];
});
