<?php

namespace App\Forms\Register;

use Kris\LaravelFormBuilder\Form;
use App\Models\PayMethod;
use App\Models\Contact;
use App\Models\Student;
use App\Models\Agency;
use App\Models\Book;
use App\User;

class StudentRegisterForm extends Form
{
    public function buildForm()
    {
        $this
            ->add('grade', 'select', [
                'label' => '年级',
                'rules' => 'required',
                'choices' => Student::GRADES,
                'empty_value' => '=== 请选择 ==='
            ])
            ->add('book_id', 'select', [
                'label' => '同步教材',
                'choices' => Book::where('type', Book::SYNC)->get()->pluck('name','id')->toArray(),
                'empty_value' => '=== Select ==='
            ])
            ->add('english_name', 'text', [
                'label' => '英文名',
                'help_block' => [
                    'text' => '没有可以留空',
                    'tag' => 'small',
                    'attr' => ['class' => 'form-text text-muted']
                ],
            ])
            ->add('profile_name', 'text', [
                'label' => '学生姓名',
                'help_block' => [
                    'text' => '⚠️您仅有这一次更改机会<br/>如果注册时已正确填写真实姓名，可以留空',
                    'tag' => 'small',
                    'attr' => ['class' => 'form-text text-muted']
                ],
            ])
            ->add('profile_telephone', 'tel', [
                'label' => '家长手机号',
                'help_block' => [
                    'text' => '⚠️您仅有这一次更改机会，可用于登陆本站<br/>如果注册时已正确填写，可以留空',
                    'tag' => 'small',
                    'attr' => ['class' => 'form-text text-muted']
                ],
            ])
            ->add('contact_qq', 'text',[
                'label' => 'QQ/微信',
                'help_block' => [
                    'text' => '可以留空，家长QQ号或微信号',
                    'tag' => 'small',
                    'attr' => ['class' => 'form-text text-muted']
                ],
            ]);
            //如果是默认推荐人，给更改机会
            if(auth()->user()->profiles->first()->recommend_uid === 1){
                $this->add('recommend_telephone', 'tel', [
                    'label' => '推荐人手机号',
                    'help_block' => [
                        'text' => '若无，可以留空',
                        'tag' => 'small',
                        'attr' => ['class' => 'form-text text-muted']
                    ],
                ]);
            }
            $this->add('submit', 'submit', [
                'label' => 'Save',
                'attr' => ['class' => 'btn btn-outline-primary'],
            ]);
    }
}
