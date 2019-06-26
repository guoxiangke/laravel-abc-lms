<?php

namespace App\Forms;

use Kris\LaravelFormBuilder\Form;

class ZoomForm extends Form
{
    public function buildForm()
    {
        $this->add('email', 'email', [
                'rules' => 'required',
                'label' => '登陆邮箱',
            ])
            ->add('password', 'text', [
                'rules' => 'required',
                'label' => '登陆密码',
            ])
            ->add('pmi', 'number', [
                'rules' => 'required',
                'label' => 'PMI',
            ])
            ->add('submit', 'submit', [
                'label' => 'Save',
                'attr'  => ['class' => 'btn btn-outline-primary'],
            ]);
    }
}
