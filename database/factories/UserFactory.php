<?php
use App\User;
use App\Models\{Profile, Contact, PayMethod, School, Teacher};
use Illuminate\Support\Str;
use Faker\Generator as Faker;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(User::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'email_verified_at' => now(),
        'password' => Hash::make(Str::random(8)),
        'remember_token' => Str::random(10),
    ];
});


$factory->afterMaking(User::class, function ($user, $faker) {
    //user profile
    // $avatar = $faker->imageUrl(256,256);
    // $user->addMediaFromUrl($avatar)->toMediaCollection('avatar');

    // $profile = factory(Profile::class)->create([
    //     'user_id' => $user->id,
    //     'name'  => $user->name,
    // ]);
    // //user profile contact
    // $contact = factory(Contact::class)->create([
    //     'profile_id' => $profile->id,
    // ]);
    // //user paymethod
    // $paymethod = factory(PayMethod::class)->create([
    //     'user_id' => $user->id,
    // ]);
});

$factory->afterCreating(User::class, function ($user, $faker) {
    //user profile
    $profile = factory(Profile::class)->create([
        'user_id' => $user->id,
        'name'  => $user->name,
    ]);
    //user profile contact
    $contact = factory(Contact::class)->create([
        'profile_id' => $profile->id,
    ]);
    //user paymethod
    $paymethod = factory(PayMethod::class)->create([
        'user_id' => $user->id,
    ]);
});
