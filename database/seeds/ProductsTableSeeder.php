<?php
use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $product = Product::create([
            'name' => '课程体验包',
            'description' => '新用户专享',
            'remark' => '第一次',
            'price' => 0,
        ]);
    }
}
