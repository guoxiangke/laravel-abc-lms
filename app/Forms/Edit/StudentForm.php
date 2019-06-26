<?php

namespace App\Forms\Edit;

use App\Models\Book;
use App\Models\Agency;
use App\Models\Student;
use Kris\LaravelFormBuilder\Form;

class StudentForm extends Form
{
    public function buildForm()
    {
        $student = $this->getData('entity');
        if (! $student) {
            return;
        }
        $user = $student->user;
        $paymethod = $user->paymethod;
        $profile = $user->profiles->first();
        $contact = $profile->contacts->first();
        $recommend = Student::with('profiles')->get()->pluck('profiles.0.name', 'user_id')->union(Agency::with('profiles')->get()->pluck('profiles.0.name', 'user_id'))->unique()->toArray();
        // dd($recommend);

        $this->add('profile_name', 'text', [
                'rules' => 'required',
                'value' => $profile->name,
                'label' => '姓名',
            ])
            ->add('profile_sex', 'select', [
                'label'       => '性别',
                'rules'       => 'required',
                'choices'     => ['女','男'],
                'selected'    => $profile->sex,
                'empty_value' => '=== Select ===',
            ])
            ->add('profile_birthday', 'date', [
                'label' => '生日',
                'value' => $profile->birthday ? $profile->birthday->format('Y-m-d') : null,
            ])
            ->add('grade', 'select', [
                'label'       => '年级',
                'rules'       => 'required',
                'selected'    => $student->grade,
                'choices'     => Student::GRADES,
                'empty_value' => '=== Select ===',
            ])
            ->add('telephone', 'tel', [
                'rules'      => 'required|min:11',
                'label'      => '手机号',
                'value'      => $profile->telephone,
                'help_block' => [
                    'text' => '手机号可用于登陆,不带+86，11位',
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
            ->add('name', 'text', [
                'value'      => $user->name,
                'label'      => '英文名',
                'help_block' => [
                    'text' => '不填，自动为姓名的拼音+s_',
                    'tag'  => 'small',
                    'attr' => ['class' => 'form-text text-muted'],
                ],
            ])
            ->add('book_id', 'select', [
                'label'       => '同步教材',
                'selected'    => $student->book_id,
                'choices'     => Book::where('type', Book::SYNC)->get()->pluck('name', 'id')->toArray(),
                'empty_value' => '=== Select ===',
            ])
            ->add('contact_number', 'text', [
                'value' => $contact->number,
                'label' => 'Wechat/QQ/手机号',
            ])
            ->add('contact_remark', 'textarea', [
                'label' => '联系方式备注',
                'value' => $contact->remark,
                'attr'  => [
                    'rows'        => 2,
                    'placeholder' => 'Wechat/QQ/手机号 可不填,备注写这里',

                ],
            ])
            ->add('recommend_uid', 'select', [
                'label'       => '介绍人',
                'selected'    => $profile->recommend_uid,
                'choices'     => $recommend, //包括代理/学生
                'empty_value' => '=== Select ===',
            ])
            ->add('remark', 'textarea', [
                'value' => $student->remark,
                'label' => '备注',
                'attr'  => ['rows' => 2],
            ])
            ->add('submit', 'submit', [
                'label' => 'Save',
                'attr'  => ['class' => 'btn btn-outline-primary'],
            ]);
    }
}
