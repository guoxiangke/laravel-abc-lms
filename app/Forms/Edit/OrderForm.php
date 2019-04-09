<?php

namespace App\Forms\Edit;

use Kris\LaravelFormBuilder\Form;
use App\Models\PayMethod;
use App\Models\Contact;
use App\Models\School;
use App\Models\Product;
use App\Models\Zoom;
use App\Models\Order;
use App\Models\Book;
use App\User;

class OrderForm extends Form
{
    public function buildForm()
    {
        $order = $this->getData('entity');
        if(!$order) return;
        $this->add('order', 'static', [
                'label' => '订单Id',
                'value' => $order->id,
            ]);
        
        $products = Product::all()
                    ->pluck('name','id')
                    ->toArray();
        $this->add('product_id', 'select', [
                'label' => 'Product',
                'rules' => 'required',
                'selected' => $order->product_id,
                'choices' => $products,
            ]);

        $students = User::role('student')->with('profiles')->get()->pluck('profiles.0.name','id')->toArray();
        $teachers = User::role('teacher')->with('profiles')->get()->pluck('profiles.0.name','id')->toArray();
        $agencies = User::role('agency')->with('profiles')->get()->pluck('profiles.0.name','id')->toArray();
        $books = Book::where('type',1)->get()->pluck('name','id')->toArray();
        $this
            ->add('user_id', 'select', [
                'label' => 'Student',
                'rules' => 'required',
                'choices' => $students,
                'selected' => $order->user_id,
                'empty_value' => '=== Select ==='
            ])
            ->add('teacher_uid', 'select', [
                'label' => 'Teacher',
                'choices' => $teachers,
                'selected' => $order->teacher_uid,
                'empty_value' => '=== Select ==='
            ])
            ->add('agency_uid', 'select', [
                'label' => 'Agency',
                'selected' => $order->agency_uid,
                'choices' => $agencies,
                'empty_value' => '=== Select ==='
            ])
            ->add('book_id', 'select', [
                'label' => 'Book',
                'choices' => $books,
                'selected' => $order->book_id,
                'selected' => 0,
                'empty_value' => '=== Select ==='
            ])
            ->add('price', 'number', [
                'rules' => 'required',
                'label' => 'Price',
                'value' => $order->price,
                'attr' => ['placeholder' => '成交价,单位元']
            ])
            ->add('period', 'number', [
                'rules' => 'required',
                'label' => 'Period',
                'value' => $order->period,
                'attr' => ['placeholder' => '课时']
            ])
            ->add('rrule', 'textarea', [
                'rules' => 'required',
                'label' => '上课计划',
                'value' => $order->rrules->first()->string,
                'attr' => [
                    'rows' => 3,
                    'placeholder' => "DTSTART:20190330T180000Z\nRRULE:FREQ=DAILY;COUNT=5;INTERVAL=1;WKST=MO;BYDAY=TU"
                ],
                'help_block' => [
                    'text' => '共2行，第一行：第一次上课日期+时间，第二行：上课规律 <a target="_blank" href="https://jakubroztocil.github.io/rrule/">Gen a rule.toString()/点击生成内容</a>',
                    'tag' => 'small',
                    'attr' => ['class' => 'form-text text-muted']
                ],
            ])
            ->add('expired_at', 'date', [
                'rules' => 'required',
                'value' => $order->expired_at->format('Y-m-d'),
                'label' => '有效期至'
            ])
            ->add('status', 'select', [
                'label' => '订单状态',
                'rules' => 'required',
                'choices' => Order::STATUS,
                'selected' => $order->status,
            ])
            ->add('remark', 'textarea', [
                'value' => $order->remark,
                'label' => '备注',
                'attr' => ['rows' => 2],
            ])
            ->add('submit', 'submit', [
                'label' => 'Save',
                'attr' => ['class' => 'btn btn-outline-primary'],
            ]);
    }
}
