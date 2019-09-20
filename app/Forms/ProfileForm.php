<?php

namespace App\Forms;

use App\User;
use Kris\LaravelFormBuilder\Form;
use Illuminate\Support\Facades\Auth;

class ProfileForm extends Form
{
    public function buildForm()
    {
        $this
            ->add('name', 'text', [
                'rules' => 'required',
                'label' => '姓名',
            ])
            ->add('sex', 'select', [
                'label'       => '性别',
                'rules'       => 'required',
                'choices'     => ['女', '男'],
                'selected'    => 0,
                'empty_value' => '=== Select ===',
            ])
            ->add('birthday', 'date', ['label' => '生日'])
            ->add('telephone', 'tel', [
                'rules'      => 'required|min:11',
                'label'      => '手机号',
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
        $user = Auth::user();
        if ($user->isAdmin()) {
            $recommend = User::with('profiles')->get()->pluck('profiles.0.name', 'id')->filter()->toArray();
            $this->addBefore('submit', 'recommend_uid', 'select', [
                    'label'       => '介绍人',
                    'choices'     => $recommend,
                    'empty_value' => '=== Select ===',
                ]);
            $this->addBefore('name', 'user_id', 'select', [
                    'label'       => 'User',
                    'choices'     => $recommend,
                    'empty_value' => '=== Select ===',
                ]);
        } else {
            $this->addBefore('name', 'user_id', 'static', ['label' => 'User Id', 'value' => $profile->user_id]);
        }
    }
}
