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
        Product::create([
            'name'        => '课程体验包',
            'description' => '新用户专享（7天有效）',
            'remark'      => '第一次，新用户专享（7天有效）',
            'price'       => 199,
        ]);

        $remark = <<<'EOF'
        1.上课时间为上午5点至晚上23点，一节课25分钟或者50分钟 
        2.学生需固定上课时间，特殊情况提前2个小时请假，勿需每日预约
        4.周卡必须上5节课，不足5节课的每少上1节扣除一课时
        5.周卡建议两人拼团，比如一学生上一三五，另一学生二四上课
        EOF;

        $products = [
            [
                'name'        => '次卡基础班80课时（12个月有效）',
                'description' => '（12个月有效）',
                'remark'      => $remark,
                'price'       => 4200,
            ],
            [
                'name'        => '次卡强化班240课时（24个月有效）',
                'description' => '（24个月有效）',
                'remark'      => $remark,
                'price'       => 12100,
            ],
            [
                'name'        => '次卡梦想班400课时（36个月有效）',
                'description' => '（36个月有效）',
                'remark'      => $remark,
                'price'       => 18800,
            ],
            [
                'name'        => '周卡提升班60课时（4个月有效）',
                'description' => '（4个月有效）',
                'remark'      => $remark,
                'price'       => 2600,
            ],
            [
                'name'        => '周卡希望班200课时（12个月有效）',
                'description' => '（12个月有效）',
                'remark'      => $remark,
                'price'       => 8300,
            ],
            [
                'name'        => '周卡飞跃班300课时（18个月有效）',
                'description' => '（18个月有效）',
                'remark'      => $remark,
                'price'       => 11800,
            ],
        ];

        collect($products)->map(function ($product) {
            Product::create($product);
        });
    }
}
