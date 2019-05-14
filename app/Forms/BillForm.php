<?php

namespace App\Forms;

use App\Models\Bill;
use App\Models\Order;
use App\User;
use App\Models\PayMethod;
use Kris\LaravelFormBuilder\Form;

class BillForm extends Form
{
    public function buildForm()
    {
        $users = User::with('profiles')->get()->pluck('profiles.0.name','id')->toArray();
        $orders = Order::with(['user','teacher','agency','user.profiles','teacher.profiles','agency.profiles'])->active()->get()->map(function($order){
            return [$order->id=>$order->title];
        })->flatten()->toArray();
        $this
            ->add('type', 'select', [
                'label' => '类型',
                'rules' => 'required',
                'choices' => Bill::TYPES,
                'value' => 0,
            ])
            ->add('user_id', 'select', [
                'label' => 'Student',
                'rules' => 'required',
                'choices' => $users,
                'empty_value' => '=== Select ==='
            ])
            ->add('order_id', 'select', [
                'label' => 'Order',
                'choices' => $orders,
                'empty_value' => '=== Select ==='
            ])
            ->add('price', 'text', [
                'rules' => 'required',
                'label' => 'Price',
                'attr' => ['placeholder' => '单位元,可带2为小数'],
            ])
            ->add('paymethod_type', 'select', [
                'label' => '付款方式',
                'choices' => PayMethod::TYPES,
                'selected' => 1,
            ])
            ->add('status', 'checkbox', [
                'value' => 0,
                'label' => '已入/出账',
                'checked' => false,
                'help_block' => [
                    'text' => '默认是0:append，如已成交/入账，请打✓✔☑（即1:approved） ',
                    'tag' => 'small',
                    'attr' => ['class' => 'form-text text-muted']
                ],
            ])
            ->add('remark', 'textarea', [
                'label' => '备注',
                'attr' => ['rows' => 2],
            ])
            ->add('submit', 'submit', [
                'label' => 'Save',
                'attr' => ['class' => 'btn btn-outline-primary'],
            ]);
    }
}
