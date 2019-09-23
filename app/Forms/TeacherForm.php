<?php

namespace App\Forms;

use App\Models\School;
use App\Models\Contact;
use App\Models\Teacher;
use App\Models\PayMethod;
use Kris\LaravelFormBuilder\Form;

class TeacherForm extends Form
{
    public function buildForm()
    {
        $recommend = Teacher::with(['user', 'user.profiles'])->get()->pluck('user.profiles.0.name', 'user_id')->toArray();

        $this->add('school_id', 'select', [
                'label'       => 'School',
                'choices'     => School::all()->pluck('name', 'id')->toArray(),
                'empty_value' => 'Freelancer/自由职业',
            ])
            ->add('recommend_uid', 'select', [
                    'label'       => 'Referrer/推荐人',
                    'choices'     => $recommend,
                    'selected'    => null,
                    'empty_value' => '=== Select ===',
            ])
            ->add(
                'price',
                'number',
                [
                    'rules' => 'required',
                    'label' => 'Rate',
                    'value'=> 0,
                    'help_block' => [
                        'text' => '单位：₱150.00/hour 默认为：0',
                        'tag'  => 'small',
                        'attr' => ['class' => 'form-text text-muted'],
                    ],
                ],
            )
            ->add(
                'profile_name',
                'text',
                ['rules' => 'required', 'label' => '姓名']
            )
            ->add('user_password', 'text', [
                'label'      => '登陆密码',
                'help_block' => [
                    'text' => '不填，默认为：Teacher123',
                    'tag'  => 'small',
                    'attr' => ['class' => 'form-text text-muted'],
                ],
            ])
            ->add('email', 'email', [
                'label'      => '登陆邮箱',
                'rules'      => 'required|email:rfc,dns',
                'help_block' => [
                    'text' => '不填，默认为：t_姓名@teacher.com',
                    'tag'  => 'small',
                    'attr' => ['class' => 'form-text text-muted'],
                ],
            ])
            ->add('telephone', 'tel', [
                'label'      => '手机号',
                'rules'      => 'required|string|min:12|max:14',
                'value'      =>  '+63',
                'help_block' => [
                    'text' => '带+63，共计12~14位',
                    'tag'  => 'small',
                    'attr' => ['class' => 'form-text text-muted'],
                ],
            ])
            ->add('contact_type', 'select', [
                'label'   => '联系方式',
                'choices' => Contact::TYPES,
                'selected' => 0,
                'empty_value' => '=== Select ===',
            ])
            ->add('contact_number', 'text', [
                'rules' => 'required|min:4',
                'label' => '联系方式账户ID',
            ])
            ->add('contact_remark', 'textarea', [
                'label'      => '联系方式备注',
                'help_block' => [
                    'text' => '登陆邮箱：t_name@teacher.com',
                    'tag'  => 'small',
                    'attr' => ['class' => 'form-text text-muted'],
                ],
                'attr' => ['rows' => 2],
            ])
            ->add('pmi', 'text', [
                'label'       => 'Zhumu PMI',
                'help_block'  => [
                    'text' => '可以带-或纯数字: 174-546-4410',
                    'tag'  => 'small',
                    'attr' => ['class' => 'form-text text-muted'],
                ],
            ])
            ->add('profile_sex', 'select', [
                'label'       => '性别',
                'rules'       => 'required',
                'choices'     => ['女', '男'],
                'selected'    => 0,
                'empty_value' => '=== Select ===',
            ])
            ->add('profile_birthday', 'date', ['label' => '生日'])
            ->add('pay_method', 'select', [
                'label'       => '付款方式（中教必填）',
                'choices'     => PayMethod::TYPES,
                'selected'    => 1, //'PayPal'
            ])
            ->add('pay_number', 'text', [
                'label' => '付款账户ID（中教必填）',
            ])
            ->add('pay_remark', 'textarea', [
                'label' => '付款方式备注',
                'attr'  => ['rows' => 2],
            ])
            ->add('active', 'choice', [
                'label' => '是否辞职',
                'choices' => ['1' => '在职', '0' => '辞职'],
                'selected' => 1,
                'multiple' => false,
            ])
            ->add('passion', 'choice', [
                'label' => '有无激情',
                'choices' => ['1' => '有', '0' => '无'],
                'selected' => 1,
                'multiple' => false,
            ])
            ->add('ontime', 'choice', [
                'label' => '准时情况',
                'choices' => ['1' => '准时', '0' => '不准时'],
                'selected' => 1,
                'multiple' => false,
            ])
            ->add('network', 'choice', [
                'label' => '网络情况',
                'choices' => ['1' => '准时', '0' => '不准时'],
                'multiple' => false,
            ])
            ->add('noisy', 'choice', [
                'label' => '环境嘈杂',
                'choices' => ['1' => '安静', '0' => '嘈杂'],
                'multiple' => false,
            ])
            ->add('messenger', 'text', [
                'label'       => 'Messenger',
                'help_block'  => [
                    'text' => 'https://www.facebook.com/messages/t/xxx.yy',
                    'tag'  => 'small',
                    'attr' => ['class' => 'form-text text-muted'],
                ],
            ])
            ->add('avatar', 'url', [
                'label'       => 'Avatar',
                'help_block'  => [
                    'text' => 'https://www.facebook.com/messages/t/xxx.yy, then input xxx.yy',
                    'tag'  => 'small',
                    'attr' => ['class' => 'form-text text-muted'],
                ],
            ])
            ->add('christ', 'choice', [
                'label' => '宗教信仰',
                'choices' => ['0' => 'NONE', '1' => 'Christ'],
                'multiple' => false,
            ])
            ->add('submit', 'submit', [
                'label' => 'Save',
                'attr'  => ['class' => 'btn btn-outline-primary'],
            ]);
    }
}
