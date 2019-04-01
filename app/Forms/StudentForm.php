<?php

namespace App\Forms;

use Kris\LaravelFormBuilder\Form;
use App\Models\PayMethod;
use App\Models\Contact;
use App\Models\Student;
use App\Models\Agency;
use App\User;

class StudentForm extends Form
{
    public function buildForm()
    {
        $this->add('profile_name', 'text', [
                'rules' => 'required',
                'label' => '姓名*',
            ])
            ->add('profile_telephone', 'tel', [
                'rules' => 'required|min:11',
                'label' => '手机号*',
                'attr' => ['placeholder' => '可用于登陆']
            ])
            ->add('user_password', 'text', [
                'rules' => 'required|min:8',
                'label' => '登陆密码*',
                'attr' => ['placeholder' => '默认：Teacher123']
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
                'attr' => ['rows' => 2, 'placeholder'=>'登陆邮箱：agency_name@teleNo.com'],
            ])
            ->add('profile_sex', 'select', [
                'label' => '性别',
                'rules' => 'required',
                'choices' => ['女','男'],
                'selected' => 0,
                'empty_value' => '=== Select ==='
            ])
            ->add('profile_birthday', 'date', ['label' => '生日'])
            ->add('grade', 'select', [
                'label' => '年级*',
                'rules' => 'required',
                'choices' => Student::GRADES,
                'empty_value' => '=== Select ==='
            ])
            ->add('remark', 'textarea', [
                'label' => '备注',
                'attr' => ['rows' => 2],
            ])
            ->add('agency_uid', 'select', [
                'label' => '上级代理',
                'choices' => Agency::with('profile')->get()->pluck('profile.name','id')->toArray(),
                'empty_value' => '=== Select ==='
            ])
            ->add('recommender_uid', 'select', [
                'label' => '介绍学员',
                'choices' => Student::with('profile')->get()->pluck('profile.name','id')->toArray(),
                'empty_value' => '=== Select ==='
            ])
            ->add('submit', 'submit', [
                'label' => 'Save',
                'attr' => ['class' => 'btn btn-outline-primary'],
            ]);
    }
}
