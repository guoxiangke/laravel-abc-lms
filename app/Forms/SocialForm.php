<?php

namespace App\Forms;

use Kris\LaravelFormBuilder\Form;

class SocialForm extends Form
{
    public function buildForm()
    {
        $socialUser = $this->getData('socialUser');
        $socialType = $this->getData('socialType');
        // ['socialUser' => $socialUser, 'socialType' => 1],
        if(!($socialUser && $socialType)) return;
        $this->add('type', 'static', [
                'label' => 'type',
                'value' => $socialType,
            ])
            ->add('name', 'static', [
                'label' => 'name',
                'value' => $socialUser->name,
            ])->add('nickname', 'static', [
                'label' => 'nickname',
                'value' => $socialUser->nickname,
            ])
            ->add('telephone', 'text', [
                'label' => 'Publisher',
                'rules' => 'required|min:11',
                'help_block' => [
                    'text' => '请输入您给课程顾问的手机号,不带+86，11位',
                    'tag' => 'small',
                    'attr' => ['class' => 'form-text text-muted']
                ],
            ])
            ->add('email', 'text', [
                'label' => 'Name',
                'help_block' => [
                    'text' => '或者向课程顾问询问您的绑定邮箱地址',
                    'tag' => 'small',
                    'attr' => ['class' => 'form-text text-muted']
                ],
            ])
            ->add('submit', 'submit', [
                'label' => __('Bind'),
                'attr' => ['class' => 'btn btn-outline-primary'],
            ]);
    }
}
