<?php

namespace App\Forms;

use App\Models\PayMethod;
use Kris\LaravelFormBuilder\Form;

class SchoolForm extends Form
{
    public function buildForm()
    {
        $this->add(
            'school_name',
            'text',
            [
                'label'          => '学校名字',
                'rules'          => 'required|min:2',
                'error_messages' => [
                    'title.required' => 'The school name field is mandatory.',
                ],
            ]
        )
            ->add('image', 'file', [
                'label' => 'Logo: todo',
                'attr'  => ['placeholder' => '学校Logo'],
            ])
            ->add('user_email', 'email', [
                'label' => '学校登陆邮箱',
                'rules' => 'required',
            ])
            ->add('user_password', 'text', [
                'label'      => '学校登陆密码',
                'help_block' => [
                    'text' => '可用于登陆，密码默认：Dxjy1234',
                    'tag'  => 'small',
                    'attr' => ['class' => 'form-text text-muted'],
                ],
            ])
            ->add('profile_name', 'text', [
                'label' => '管理员姓名',
                'rules' => 'required|min:2',
            ])
            ->add('profile_sex', 'select', [
                'label'       => '管理员性别',
                'rules'       => 'required',
                'choices'     => ['女', '男'],
                'selected'    => 0,
                'empty_value' => '=== Select ===',
            ])
            ->add('profile_birthday', 'date', ['label' => '管理员生日'])
            ->add('telephone', 'tel', [
                'rules' => 'required|min:13',
                'label' => '管理员手机号',
            ])
            ->add('contact_skype', 'text', [
                'rules' => 'required|min:4',
                'label' => '管理员/联系人Skype',
            ])// type =0 skype number=contact_skype
            ->add('contact_remark', 'textarea', [
                'label' => '联系方式备注',
                'attr'  => ['rows' => 2],
            ])
            ->add('pay_method', 'select', [
                'label'       => '付款方式',
                'rules'       => 'required',
                'choices'     => PayMethod::TYPES,
                'selected'    => 0, //'PayPal'
                'empty_value' => '=== Select ===',
            ])
            ->add('pay_number', 'text', [
                'rules' => 'required',
                'label' => '付款账户ID',
            ])
            ->add('pay_remark', 'textarea', [
                'label' => '付款方式备注',
                'attr'  => ['rows' => 2],
            ])
            ->add('submit', 'submit', [
                'label' => 'Save',
                'attr'  => ['class' => 'btn btn-outline-primary'],
            ]);
    }
}
