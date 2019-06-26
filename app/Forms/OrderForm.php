<?php

namespace App\Forms;

use Kris\LaravelFormBuilder\Form;
use App\Models\Product;
use App\Models\Order;
use App\Models\Book;
use App\User;

class OrderForm extends Form
{
    public function buildForm()
    {
        //todo permission for orders!
        $products = Product::all()
                    ->pluck('name', 'id')
                    ->toArray();
        $this->add('product_id', 'select', [
                'label' => 'Product',
                'rules' => 'required',
                'choices' => $products,
            ]);
        $students = User::role('student')->with('profiles')->get()->pluck('profiles.0.name', 'id')->toArray();
        $teachers = User::role('teacher')->with('profiles')->get()->pluck('profiles.0.name', 'id')->toArray();
        $agencies = User::role('agency')->with('profiles')->get()->pluck('profiles.0.name', 'id')->toArray();
        $books = Book::where('type', 1)->get()->pluck('name', 'id')->toArray();
        $this
            ->add('user_id', 'select', [
                'label' => 'Student',
                'rules' => 'required',
                'choices' => $students,
                'empty_value' => '=== Select ==='
            ])
            ->add('teacher_uid', 'select', [
                'label' => 'Teacher',
                'rules' => 'required',
                'choices' => $teachers,
                'empty_value' => '=== Select ==='
            ])
            ->add('agency_uid', 'select', [
                'label' => 'Agency',
                'choices' => $agencies,
                'empty_value' => '=== Select ==='
            ])
            ->add('book_id', 'select', [
                'label' => 'Book',
                'choices' => $books,
                'selected' => 0,
                'empty_value' => '=== Select ==='
            ])
            ->add('price', 'text', [
                'rules' => 'required',
                'label' => 'Price',
                'attr' => ['placeholder' => '成交价,单位元,可带2为小数'],
            ])
            ->add('period', 'number', [
                'rules' => 'required',
                'label' => 'Period',
                'attr' => ['placeholder' => '课时']
            ])
            ->add('rrule', 'repeated', [
                'type' => 'textarea',
                'second_name' => 'rrule_repeated',
                'first_options' =>[
                    'rules' => 'required',
                    'label' => '上课计划',
                    'attr' => [
                        'rows' => 3,
                        'placeholder' => "DTSTART:20190330T180000Z\nRRULE:FREQ=DAILY;COUNT=5;INTERVAL=1;WKST=MO;BYDAY=TU"
                    ],
                    'help_block' => [
                        'text' => '共2行，第一行：第一次上课日期+时间，第二行：上课规律 <a target="_blank" href="https://jakubroztocil.github.io/rrule/">Gen a rule.toString()/点击生成内容</a>',
                        'tag' => 'small',
                        'attr' => ['class' => 'form-text text-muted']
                    ],
                ],
                'second_options' =>[
                    'label' => '上课计划2',
                    'attr' => [
                        'rows' => 3,
                        'placeholder' => "如果有1天上2次课的，请填写，和上面👆一样，只是时间不一样"
                    ],
                    'help_block' => [
                        'text' => '共2行，第一行：第一次上课日期+时间，第二行：上课规律 <a target="_blank" href="https://jakubroztocil.github.io/rrule/">Gen a rule.toString()/点击生成内容</a>',
                        'tag' => 'small',
                        'attr' => ['class' => 'form-text text-muted']
                    ],
                ],
            ])
            ->add('expired_at', 'date', [
                'rules' => 'required',
                'label' => '有效期至'
            ])
            ->add('status', 'select', [
                'label' => '订单状态',
                'rules' => 'required',
                'choices' => Order::STATUS,
                'selected' => Order::STATU_ACTIVE,
            ])
            ->add('remark', 'textarea', [
                'label' => '备注',
                'attr' => ['rows' => 4],
            ])
            ->add('submit', 'submit', [
                'label' => 'Save',
                'attr' => ['class' => 'btn btn-outline-primary'],
            ]);
    }
}
