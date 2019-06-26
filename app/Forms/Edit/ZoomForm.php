<?php

namespace App\Forms\Edit;

use Kris\LaravelFormBuilder\Form;

class ZoomForm extends Form
{
    public function buildForm()
    {
        $entity = $this->getData('entity');
        if (! $entity) {
            return;
        }
        $this->add('email', 'email', [
                'rules' => 'required',
                'value' => $entity->email,
                'label' => '登陆邮箱',
            ])
            ->add('password', 'text', [
                'rules' => 'required',
                'value' => $entity->password,
                'label' => '登陆密码',
            ])
            ->add('pmi', 'number', [
                'rules' => 'required',
                'value' => $entity->pmi,
                'label' => 'PMI',
            ])
            ->add('submit', 'submit', [
                'label' => 'Save',
                'attr' => ['class' => 'btn btn-outline-primary'],
            ]);
    }
}
