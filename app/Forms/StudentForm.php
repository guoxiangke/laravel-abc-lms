<?php

namespace App\Forms;

use App\Models\Book;
use App\Models\Student;
use App\User;
use Kris\LaravelFormBuilder\Form;

class StudentForm extends Form
{
    public function buildForm()
    {
        // 包括代理/学生 + laoshi
        $recommend = User::getAllReference();
        $this->add('profile_name', 'text', [
            'rules' => 'required',
            'label' => '姓名',
        ])
            ->add('profile_sex', 'select', [
                'label'       => '性别',
                'rules'       => 'required',
                'choices'     => ['女', '男'],
                'selected'    => 0,
                'empty_value' => '=== Select ===',
            ])
            ->add('profile_birthday', 'date', ['label' => '生日'])
            ->add('grade', 'select', [
                'label'       => '年级',
                'rules'       => 'required',
                'choices'     => Student::GRADES,
                'empty_value' => '=== Select ===',
            ])
            ->add('telephone', 'tel', [
                'rules'      => 'required|size:14',
                'label'      => '手机号',
                'value'      => '+86',
                'help_block' => [
                    'text' => '手机号可用于登陆,带+86，共计14位',
                    'tag'  => 'small',
                    'attr' => ['class' => 'form-text text-muted'],
                ],
            ])
            ->add('password', 'text', [
                'label'      => '登陆密码',
                'help_block' => [
                    'text' => '可用于登陆，密码默认：dxjy1234',
                    'tag'  => 'small',
                    'attr' => ['class' => 'form-text text-muted'],
                ],
            ])
            ->add('book_id', 'select', [
                'label'       => '同步教材',
                'choices'     => Book::where('type', Book::SYNC)->get()->pluck('name', 'id')->toArray(), // todo add publisher
                'empty_value' => '=== Select ===',
            ])
            ->add('contact_number', 'text', [
                'label' => 'Wechat/QQ/手机号',
            ])
            ->add('contact_remark', 'textarea', [
                'label' => '联系方式备注',
                'attr'  => [
                    'rows'        => 2,
                    'placeholder' => 'Wechat/QQ/手机号 可不填,备注写这里',

                ],
            ])
            ->add('recommend_uid', 'select', [
                'label'       => '介绍人',
                'choices'     => $recommend, //包括代理/学生
                'selected'    => 1,
            ])
            ->add('remark', 'textarea', [
                'label' => '备注',
                'attr'  => ['rows' => 2],
            ])
            ->add('submit', 'submit', [
                'label' => 'Save',
                'attr'  => ['class' => 'btn btn-outline-primary'],
            ]);
    }
}
