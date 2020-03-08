<?php

namespace App\Forms;

use App\Models\Bill;
use App\Models\Order;
use App\Models\PayMethod;
use App\User;
use Kris\LaravelFormBuilder\Form;

class BillForm extends Form
{
    public function buildForm()
    {
        $users = User::getAllReference();
        $orders = $orders = Order::with([ // $order->title
            'student',
            'student.profiles',
            'teacher.profiles',
            'agency.profiles',
        ])
            ->active()->get()->map(function ($order) {
                return [$order->id=>$order->title];
            })->flatten()->toArray();
        $this
            ->add('type', 'select', [
                'label'   => '类型',
                'rules'   => 'required',
                'choices' => Bill::TYPES,
                'value'   => 0,
            ])
            ->add('created_at', 'datetime-local', [
                'rules' => 'required',
                'value' => now()->format('Y-m-d\TH:i'),
                'label' => '入账时间',
            ])
            ->add('user_id', 'select', [
                'label'       => 'User',
                'rules'       => 'required',
                'attr'  => ['placeholder' => '输入中文姓名搜索学生/英文学校/老师'],
                'choices'     => $users,
            ])
            ->add('order_id', 'select', [
                'label'       => 'Order',
                'choices'     => $orders,
                'empty_value' => '=== Select ===',
            ])
            ->add('price', 'text', [
                'rules' => 'required',
                'label' => 'Price',
                'attr'  => ['placeholder' => '单位元,可带2为小数'],
            ])
            ->add('currency', 'select', [
                'label'       => 'Currency',
                'choices'     => Bill::CURRENCIES,
                'empty_value' => '0',
            ])
            ->add('paymethod_type', 'select', [
                'label'    => '付款方式',
                'choices'  => PayMethod::TYPES,
                'selected' => 1,
            ])
            ->add('status', 'checkbox', [
                'value'      => 1,
                'label'      => '已入/出账',
                'checked'    => false,
                'help_block' => [
                    'text' => '默认是0:append，如已成交/入账，请打✓✔☑（即1:approved） ',
                    'tag'  => 'small',
                    'attr' => ['class' => 'form-text text-muted'],
                ],
            ])
            ->add('remark', 'textarea', [
                'label' => '备注',
                'attr'  => ['rows' => 5],
            ])
            ->add('submit', 'submit', [
                'label' => 'Save',
                'attr'  => ['class' => 'btn btn-outline-primary'],
            ]);
    }
}
