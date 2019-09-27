<?php

namespace App\Forms\Edit;

use App\Models\School;
use App\Models\Contact;
use App\Models\Teacher;
use App\Models\PayMethod;
use Kris\LaravelFormBuilder\Form;

class TeacherForm extends Form
{
    public function buildForm()
    {
        $teacher = $this->getData('entity');
        if (! $teacher) {
            return;
        }
        $user = $teacher->user;
        $schoolId = $teacher->school ? $teacher->school->id : 0;

        $paymethod = $user->paymethod;
        $contact = false;
        $profile = $user->profiles->first();
        if ($profile) {
            $contact = $profile->contacts->first();
        }

        $recommend = Teacher::with(['user', 'user.profiles'])->get()->pluck('user.profiles.0.name', 'user_id')->toArray();
        // dd($teacher->active);
        $this->add('active', 'choice', [
                'label' => '是否辞职',
                'choices' => ['0' => '辞职', '1' => '在职'],
                'selected' => $teacher->active,
                'multiple' => false,
            ])
            ->add('passion', 'choice', [
                'label' => '有无激情',
                'choices' => ['1' => '有', '0' => '无'],
                'selected' => $teacher->extra_attributes->passion ? 1 : 0,
                'multiple' => false,
            ])
            ->add('ontime', 'choice', [
                'label' => '准时情况',
                'choices' => ['1' => '准时', '0' => '不准时'],
                'selected' => $teacher->extra_attributes->ontime ? 1 : 0,
                'multiple' => false,
            ])
            ->add('network', 'choice', [
                'label' => '网络情况',
                'choices' => ['1' => '稳定', '0' => '不稳定'],
                'selected' => $teacher->extra_attributes->network ? 1 : 0,
                'multiple' => false,
            ])
            ->add('noisy', 'choice', [
                'label' => '环境嘈杂',
                'choices' => ['1' => '安静', '0' => '嘈杂'],
                'selected' => $teacher->extra_attributes->noisy ? 1 : 0,
                'multiple' => false,
            ])
            ->add('christ', 'choice', [
                'label' => '宗教信仰',
                'choices' => ['0' => 'NONE', '1' => 'Christ'],
                'selected' => $teacher->extra_attributes->christ ?: 0,
                'multiple' => false,
            ])
            ->add('messenger', 'text', [
                'label'       => 'Messenger',
                'value' => $teacher->extra_attributes->messenger,
                'help_block'  => [
                    'text' => 'https://www.facebook.com/messages/t/xxx.yy, then input xxx.yy',
                    'tag'  => 'small',
                    'attr' => ['class' => 'form-text text-muted'],
                ],
            ])
            ->add('school_id', 'select', [
                'label'       => 'School',
                'choices'     => School::all()->pluck('name', 'id')->toArray(),
                'selected'    => $schoolId,
                'empty_value' => 'Freelancer/自由职业',
            ])
            ->add('recommend_uid', 'select', [
                    'label'       => 'Referrer/推荐人',
                    'choices'     => $recommend,
                    'selected'    => $profile ? $profile->recommend_uid : null,
                    'empty_value' => '=== Select ===',
            ])
            ->add(
                'price',
                'number',
                [
                    'rules' => 'required',
                    'label' => 'Rate',
                    'value'=> $teacher->price,
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
                ['rules' => 'required', 'label' => '姓名', 'value'=>$profile ? $profile->name : null]
            )
            ->add('user_password', 'text', [
                'label'      => '登陆密码',
                'help_block' => [
                    'text' => '不填，默认为：Teacher123',
                    'tag'  => 'small',
                    'attr' => ['class' => 'form-text text-muted'],
                ],
            ])
            ->add('email', 'text', [
                'label'      => '登陆邮箱',
                'rules'      => 'required|email:rfc',
                'value'      => $user->email,
            ])
            ->add('telephone', 'tel', [
                'value'      => $profile ? $profile->telephone : null,
                'rules'      => 'required|string|min:12|max:14',
                'label'      => '手机号',
                'help_block' => [
                    'text' => '外教带+63,中教带+86,共计12~14位',
                    'tag'  => 'small',
                    'attr' => ['class' => 'form-text text-muted'],
                ],
            ])
            ->add('contact_type', 'select', [
                'label'       => '联系方式',
                'choices'     => Contact::TYPES,
                'selected'    => $contact ? $contact->type : 0,
                'empty_value' => '=== Select ===',
            ])
            ->add('contact_number', 'text', [
                'rules' => 'required|min:4',
                'value' => $contact ? $contact->number : null,
                'label' => '联系方式账户ID',
            ])
            ->add('contact_remark', 'textarea', [
                'label'      => '联系方式备注',
                'value'      => $contact ? $contact->remark : null,
                'help_block' => [
                    'text' => '登陆邮箱：t_name@teacher.com',
                    'tag'  => 'small',
                    'attr' => ['class' => 'form-text text-muted'],
                ],
                'attr' => ['rows' => 2],
            ])
            ->add('pmi', 'text', [
                'label'       => 'Zhumu PMI',
                'value'      => $teacher->pmi ?: null,
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
                'selected'    => $profile ? $profile->sex : null,
                'empty_value' => '=== Select ===',
            ])
            ->add('profile_birthday', 'date', [
                'label' => '生日',
                'value' => $profile ? ($profile->birthday ? $profile->birthday->format('Y-m-d') : null) : null,
            ])
            ->add('pay_method', 'select', [
                'label'       => '付款方式（中教必填）',
                'choices'     => PayMethod::TYPES,
                'selected'    => 1, //'PayPal'
                'selected'    => $paymethod ? $paymethod->type : 0,
                'empty_value' => '=== Select ===',
            ])
            ->add('pay_number', 'text', [
                'label' => '付款账户ID（中教必填）',
                'value' => $paymethod ? $paymethod->number : null,
            ])
            ->add('pay_remark', 'textarea', [
                'label' => '付款方式备注',
                'value' => $paymethod ? $paymethod->remark : null,
                'attr'  => [
                    'rows'  => 2,
                ],
            ])->add('submit', 'submit', [
                'label' => 'Save',
                'attr'  => ['class' => 'btn btn-outline-primary'],
            ]);
    }
}
