<?php

use Illuminate\Database\Seeder;
use App\User;
use App\Models\Rrule;
use Faker\Generator as Faker;
class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(Faker $faker)
    {
        $user = factory(User::class)->create([
            'email' => 'admin@daxiangyingyu.com',
            'password' =>  Hash::make('Profero@1'),
            'name' => 'admin',
        ]);

        $user = factory(User::class)->create([
            'email' => 'monika@daxiangyingyu.com',
            'password' =>  Hash::make('love0325'),
            'name' => 'monika',
        ]);
        $user->assignRole(User::ROLES['manager']);

        //Data for School & school admin
        // $school = factory(School::class)->create();
        //Data for 3 Teachers
        // $teacher = factory(Teacher::class, 5)->create();
        // // $agencies = factory(Agency::class, 3)->create();

        // $students = factory(Student::class, 3)->create();

        // $books = factory(Book::class, 5)->create();
        
        // $orders = factory(Order::class, 10)->create();

        // $orders = factory(Rrule::class, 1)->create();

    }
}
