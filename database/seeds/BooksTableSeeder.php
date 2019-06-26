<?php

use App\Models\Book;
use Illuminate\Database\Seeder;

class BooksTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i = 1; $i < 9; $i++) {
            Book::create([
                'name'      => "{$i}年级上（一年级起点）",
                'type'      => 0,
                'publisher' => '人教版',
            ]);
            Book::create([
                'name'      => "{$i}年级下（一年级起点）",
                'type'      => 0,
                'publisher' => '人教版',
            ]);

            Book::create([
                'name'      => "{$i}年级上（一年级起点）",
                'type'      => 0,
                'publisher' => '外研社',
            ]);
            Book::create([
                'name'      => "{$i}年级下（一年级起点）",
                'type'      => 0,
                'publisher' => '外研社',
            ]);

            Book::create([
                'name'      => "{$i}年级上",
                'type'      => 0,
                'publisher' => '沪教版',
            ]);
            Book::create([
                'name'      => "{$i}年级下",
                'type'      => 0,
                'publisher' => '沪教版',
            ]);

            if ($i >= 3) {
                Book::create([
                    'name'      => "{$i}年级上（三年级起点）",
                    'type'      => 0,
                    'publisher' => '人教版',
                ]);
                Book::create([
                    'name'      => "{$i}年级下（三年级起点）",
                    'type'      => 0,
                    'publisher' => '人教版',
                ]);

                Book::create([
                    'name'      => "{$i}年级上（三年级起点）",
                    'type'      => 0,
                    'publisher' => '外研社',
                ]);
                Book::create([
                    'name'      => "{$i}年级下（三年级起点）",
                    'type'      => 0,
                    'publisher' => '外研社',
                ]);
            }
        }
    }
}
