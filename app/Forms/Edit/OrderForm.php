<?php

namespace App\Forms\Edit;

use App\Models\Book;
use App\Models\Order;
use App\Models\Agency;
use App\Models\Product;
use App\Models\Student;
use App\Models\Teacher;
use Kris\LaravelFormBuilder\Form;
use Illuminate\Support\Facades\Auth;

class OrderForm extends Form
{
    public function buildForm()
    {
        $order = $this->getData('entity');
        if (! $order) {
            return;
        }
        $rrule = $order->rrules->first();
        $this->add('order', 'static', [
                'label' => '订单Id',
                'value' => $order->id,
            ]);

        $products = Product::all()
                    ->pluck('name', 'id')
                    ->toArray();
        $this->add('product_id', 'select', [
                'label'    => 'Product',
                'rules'    => 'required',
                'selected' => $order->product_id,
                'choices'  => $products,
            ]);

        $students = Student::getAllReference();
        $teachers = Teacher::getAllReference();
        $agencies = Agency::getAllReference();
        $books = Book::where('type', 1)->get()->pluck('name', 'id')->toArray();

        preg_match_all('/\n/', $order->remark, $matches);
        $rows = count($matches[0]) + 5;
        
         //权限判断，不是所有人可以看到价格 see order price!!
        $user = Auth::user();
        if($user->can('Update any Order')){
           $this->add('price', 'text', [
                'rules' => 'required',
                'label' => 'Price',
                'value' => $order->price,
                'attr'  => ['placeholder' => '成交价,单位元,可带2为小数'],
            ]);
        }

        $this
            ->add('student_uid', 'select', [
                'label'       => 'Student',
                'rules'       => 'required',
                'choices'     => $students,
                'selected'    => $order->student_uid,
                'empty_value' => '=== Select ===',
            ])
            ->add('teacher_uid', 'select', [
                'label'       => 'Teacher',
                'choices'     => $teachers,
                'rules'       => 'required',
                'selected'    => $order->teacher_uid,
                'empty_value' => '=== Select ===',
            ])
            ->add('agency_uid', 'select', [
                'label'       => 'Agency',
                'selected'    => $order->agency_uid,
                'choices'     => $agencies,
                'empty_value' => '=== Select ===',
            ])
            ->add('book_id', 'select', [
                'label'       => 'Book',
                'choices'     => $books,
                'selected'    => $order->book_id,
                'selected'    => 0,
                'empty_value' => '=== Select ===',
            ])
            ->add('period', 'number', [
                'rules' => 'required',
                'label' => 'Period',
                'value' => $order->period,
                'attr'  => ['placeholder' => '课时'],
            ])
            ->add('start_at', 'datetime-local', [
                'label' => '日期时间',
                'rules' => 'required',
                'value' => $rrule->start_at->format('Y-m-d\TH:i'),
            ])
            ->add('rrule', 'textarea', [
                'rules' => 'required',
                'label' => '上课计划',
                'value' => $rrule->string,
                'attr'  => [
                    'rows'        => 3,
                    'placeholder' => "DTSTART:20190330T180000Z\nRRULE:FREQ=DAILY;COUNT=5;INTERVAL=1;WKST=MO;BYDAY=TU",
                ],
                'help_block' => [
                    'text' => '上课规律 <a target="_blank" href="https://jakubroztocil.github.io/rrule/">Gen a rule.toString()/点击生成内容</a>,只要第二行的内容，第一行的填👆的日期时间',
                    'tag'  => 'small',
                    'attr' => ['class' => 'form-text text-muted'],
                ],
            ])
            ->add('expired_at', 'date', [
                'rules' => 'required',
                'value' => $order->expired_at->format('Y-m-d'),
                'label' => '有效期至',
            ])
            ->add('status', 'select', [
                'label'    => '订单状态',
                'rules'    => 'required',
                'choices'  => Order::STATUS,
                'selected' => $order->status,
            ])
            ->add('remark', 'textarea', [
                'value' => $order->remark,
                'label' => '备注',
                'attr'  => ['rows' => $rows],
            ])
            ->add('submit', 'submit', [
                'label' => 'Save',
                'attr'  => ['class' => 'btn btn-outline-primary'],
            ]);
    }
}
