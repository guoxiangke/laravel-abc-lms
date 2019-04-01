<?php

namespace App\Forms;

use Kris\LaravelFormBuilder\Form;
use App\Models\PayMethod;
use App\Models\Contact;
use App\Models\School;

class ZoomForm extends Form
{
    public function buildForm()
    {
        $this->add('email', 'email', [
                'rules' => 'required',
                'label' => '登陆邮箱',
                'attr' => ['placeholder' => 'zoom17201@daxiangyingyu.com']
            ])
            ->add('password', 'text', [
                'rules' => 'required',
                'label' => '登陆密码',
                'attr' => ['placeholder' => 'Love17201']
            ])
            ->add('pmi', 'text', [
                'rules' => 'required',
                'label' => 'PMI',
                'attr' => ['placeholder' => '9849490463']
            ])
            ->add('submit', 'submit', [
                'label' => 'Save',
                'attr' => ['class' => 'btn btn-outline-primary'],
            ]);
    }
}
