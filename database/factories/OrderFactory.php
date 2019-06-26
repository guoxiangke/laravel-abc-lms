<?php
use App\Models\Book;
use App\Models\Order;
use App\Models\Agency;
use App\Models\Student;
use App\Models\Teacher;
use Faker\Generator as Faker;

$factory->define(Order::class, function (Faker $faker) {
    return [
        //RRULE:FREQ=DAILY;COUNT=5;INTERVAL=1;WKST=MO
        'user_id' => function () {
            return factory(Student::class)
                ->create()
                ->user
                ->id;
        },
        'teacher_uid' => function () {
            return factory(Teacher::class)
                ->create()
                ->user
                ->id;
        },
        'agency_uid' => function () {
            return factory(Agency::class)
                ->create()
                ->user
                ->id;
        },
        'book_id' => function () {
            return factory(Book::class)
                ->create()
                ->id;
        },
        'product_id'=> 1,
        'price'     => 159,
        'period'    => 5,
        'status'    => 1,//rand(0,4),
        'expired_at'=> $faker->dateTimeBetween('+30 days', '+90 days'),
        'remark'    => $faker->paragraph,
    ];
});
