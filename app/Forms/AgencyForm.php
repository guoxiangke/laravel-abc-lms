<?php

namespace App\Forms;

use Kris\LaravelFormBuilder\Form;
use App\Models\PayMethod;
use App\Models\Contact;
use App\Models\Agency;

class AgencyForm extends Form
{
    public function buildForm()
    {
        //agency_id 上级代理
        $this->add('profile_name', 'text', [
                'rules' => 'required',
                'label' => '姓名',
            ])
            ->add('telephone', 'tel', [
                'rules' => 'required|min:11',
                'label' => '手机号',
            ])
            ->add('user_password', 'text', [
                'label' => '登陆密码',
                'help_block' => [
                    'text' => '可用于登陆，密码默认：Agency1234',
                    'tag' => 'small',
                    'attr' => ['class' => 'form-text text-muted']
                ],
            ])
            ->add('contact_type', 'select', [
                'label' => '其他联系方式',
                'rules' => 'required',
                'choices' => Contact::TYPES,
                'empty_value' => '=== Select ==='
            ])
            ->add('contact_number', 'text',[
                'rules' => 'required|min:4',
                'label' => '联系方式账户ID'
            ])
            ->add('contact_remark', 'textarea', [
                'label' => '联系方式备注',
                'attr' => ['rows' => 2],
            ])
            ->add('profile_sex', 'select', [
                'label' => '性别',
                'rules' => 'required',
                'choices' => ['女','男'],
                'selected' => 0,
                'empty_value' => '=== Select ==='
            ])
            ->add('profile_birthday', 'date', ['label' => '生日'])
            ->add('pay_method', 'select', [
                'label' => '付款方式',
                'rules' => 'required',
                'choices' => PayMethod::TYPES,
                'selected' => 1, //'PayPal'
                'empty_value' => '=== Select ==='
            ])
            ->add('pay_number', 'text',[
                'rules' => 'required',
                'label' => '付款账户ID'
            ])
            ->add('pay_remark', 'textarea', [
                'label' => '付款方式备注',
                'attr' => ['rows' => 2],
            ])

            ->add('agency_type', 'select', [
                'label' => '代理类型',
                'choices' => Agency::TYPES,
                'selected' => 0,
                'empty_value' => '=== Select ==='
            ])
            ->add('agency_discount', 'number',[
                'rules' => 'min:0|max:100',
                'label' => '优惠折扣0-100'
            ])//todo 0-100 check!
            ->add('agency_id', 'select', [
                'label' => '介绍人/推荐人',
                'choices' => Agency::with('profiles')->get()->pluck('profiles.0.name','user_id')->toArray(),
                'empty_value' => '=== Select ==='
            ])
            ->add('submit', 'submit', [
                'label' => 'Save',
                'attr' => ['class' => 'btn btn-outline-primary'],
            ]);
    }
}
