<?php

namespace App\Forms;

use App\Models\Agency;
use App\Models\Book;
use App\Models\Order;
use App\Models\Product;
use App\Models\Student;
use App\Models\Teacher;
use Carbon\Carbon;
use Illuminate\Support\Facades\Input;
use Kris\LaravelFormBuilder\Form;

class OrderForm extends Form
{
    public function buildForm()
    {
        //todo permission for orders!
        $products = Product::all()
                    ->pluck('name', 'id')
                    ->toArray();
        $this->add('product_id', 'select', [
            'label'   => 'Product',
            'rules'   => 'required',
            'choices' => $products,
        ]);
        $students = Student::getAllReference();
        $teachers = Teacher::getAllReference();
        $agencies = Agency::getAllReference();
        $books = Book::where('type', 1)->get()->pluck('name', 'id')->toArray();
        $input = Input::all();

        $studentUid = null;
        $price = null;
        $period = null;
        $expiredAt = null;
        $agencyUid = null;

        $now = Carbon::now();
        $today = $now->format('Ymd');
        $rrule = 'DTSTART:'.$now->format('Ym??')."T??0000Z\nRRULE:FREQ=DAILY;COUNT=?;INTERVAL=1;WKST=MO;BYDAY=MO,TU,WE,TH,FR,SA,SU";
        if (isset($input['student_uid'])) {
            $studentUid = $input['student_uid'];
        }
        if (isset($input['trail'])) {
            $now = Carbon::now();
            $today = $now->format('Ymd');
            $rrule = "DTSTART:{$today}T180000Z\nRRULE:FREQ=DAILY;COUNT=1;INTERVAL=1;WKST=MO;BYDAY=MO,TU,WE,TH,FR,SA,SU";
            $period = 1;
            $price = 0.00;
            $expiredAt = $now->addDays(5)->format('Y-m-d');
        }
        if (isset($input['agency_uid'])) {
            $agencyUid = $input['agency_uid'];
        }
        $this
            ->add('student_uid', 'select', [
                'label'       => 'Student',
                'rules'       => 'required',
                'choices'     => $students,
                'selected'    => $studentUid,
            ])
            ->add('teacher_uid', 'select', [
                'label'       => 'Teacher',
                'rules'       => 'required',
                'choices'     => $teachers,
                'empty_value' => '=== Select ===',
            ])
            ->add('agency_uid', 'select', [
                'label'       => 'Agency',
                'choices'     => $agencies,
                'selected'    => $agencyUid,
                'empty_value' => '=== Select ===',
            ])
            ->add('book_id', 'select', [
                'label'       => 'Book',
                'choices'     => $books,
                'selected'    => 0,
                'empty_value' => '=== Select ===',
            ])
            ->add('price', 'text', [
                'rules' => 'required',
                'label' => 'Price',
                'value' => $price,
                'attr'  => ['placeholder' => '成交价,单位元,可带2为小数'],
            ])
            ->add('period', 'number', [
                'rules' => 'required',
                'label' => 'Period',
                'value' => $period,
                'attr'  => ['placeholder' => '课时'],
            ])
            ->add('rrule', 'repeated', [
                'type'          => 'textarea',
                'second_name'   => 'rrule_repeated',
                'first_options' => [
                    'rules' => 'required',
                    'label' => '上课计划',
                    'value' => $rrule,
                    'attr'  => [
                        'rows'        => 3,
                        'placeholder' => "DTSTART:20190330T180000Z\nRRULE:FREQ=DAILY;COUNT=5;INTERVAL=1;WKST=MO;BYDAY=TU",
                    ],
                    'help_block' => [
                        'text' => '共2行，第一行：第一次上课日期+时间，第二行：上课规律 <a target="_blank" href="https://jakubroztocil.github.io/rrule/">Gen a rule.toString()/点击生成内容</a>',
                        'tag'  => 'small',
                        'attr' => ['class' => 'form-text text-muted'],
                    ],
                ],
                'second_options' => [
                    'label' => '上课计划2',
                    'attr'  => [
                        'rows'        => 3,
                        'placeholder' => '如果有1天上2次课的，请填写，和上面👆一样，只是时间不一样',
                    ],
                    'help_block' => [
                        'text' => '共2行，第一行：第一次上课日期+时间，第二行：上课规律 <a target="_blank" href="https://jakubroztocil.github.io/rrule/">Gen a rule.toString()/点击生成内容</a>',
                        'tag'  => 'small',
                        'attr' => ['class' => 'form-text text-muted'],
                    ],
                ],
            ])
            ->add('expired_at', 'date', [
                'rules' => 'required',
                'value' => $expiredAt,
                'label' => '有效期至',
            ])
            ->add('status', 'select', [
                'label'    => '订单状态',
                'rules'    => 'required',
                'choices'  => Order::STATUS,
                'selected' => Order::STATU_ACTIVE,
            ])
            ->add('remark', 'textarea', [
                'label' => '备注',
                'attr'  => ['rows' => 4],
            ])
            ->add('submit', 'submit', [
                'label' => 'Save',
                'attr'  => ['class' => 'btn btn-outline-primary'],
            ]);
        if (isset($input['trail'])) {
            $this->add('trail', 'hidden', [
                'rules' => 'required',
                'value' => 1,
            ]);
        }
    }
}
