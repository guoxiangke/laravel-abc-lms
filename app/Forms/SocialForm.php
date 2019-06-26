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
        if (! ($socialUser && $socialType)) {
            abort(403);
        }
        alert()->toast('Welcome ' . $socialUser->nickname ?: $socialUser->name, 'success', 'top-center')->autoClose(3000);
        $this->add('type', 'hidden', [
                'label' => 'type',
                'value' => $socialType,
            ])
            ->add('social_id', 'hidden', [
                'label' => 'type',
                'value' => $socialUser->id,
            ])
            ->add('username', 'text', [
                'rules'      => 'required',
                'label'      => __('LoginName'),
                'help_block' => [
                    'text' => '请向课程顾问询问您的邮箱/登陆名',
                    'tag'  => 'small',
                    'attr' => ['class' => 'form-text text-muted'],
                ],
            ])
            ->add('password', 'password', [
                'label'      => __('Password'),
                'rules'      => 'required',
                'help_block' => [
                    'text' => '请向课程顾问询问您的密码',
                    'tag'  => 'small',
                    'attr' => ['class' => 'form-text text-muted'],
                ],
            ])
            ->add('submit', 'submit', [
                'label' => __('Click to bind'),
                'attr'  => ['class' => 'btn btn-outline-primary'],
            ]);
    }
}
