<?php

namespace App\Forms\Edit;

use App\User;
use Kris\LaravelFormBuilder\Form;

class ProfileForm extends Form
{
    public function buildForm()
    {
        $profile = $this->getData('entity');
        if (! $profile) {
            return;
        }
        $this
            ->add('user_id', 'hidden', ['label' => 'User Id', 'value' => $profile->user_id, ])
            ->add('name', 'text', [
                'rules' => 'required',
                'value' => $profile->name,
                'label' => '姓名',
            ])
            ->add('sex', 'select', [
                'label'       => '性别',
                'rules'       => 'required',
                'choices'     => ['女', '男'],
                'selected'    => $profile->sex,
                'empty_value' => '=== Select ===',
            ])
            ->add('birthday', 'date', ['label' => '生日', 'value'=>$profile->birthday ? $profile->birthday->format('Y-m-d') : null])
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
            ->add('submit', 'submit', [
                'label' => 'Save',
                'attr'  => ['class' => 'btn btn-outline-primary'],
            ]);

        //介绍人只有管理员可以更改！
        if ($profile->user->isAdmin()) {
            $recommend = User::with('profiles')->get()->pluck('profiles.0.name', 'id')->toArray();
            $this->addBefore('submit', 'recommend_uid', 'select', [
                    'label'       => '介绍人',
                    'choices'     => $recommend,
                    'selected'    => $profile->recommend_uid,
                    'empty_value' => '=== Select ===',
                ]);
        }
    }
}
