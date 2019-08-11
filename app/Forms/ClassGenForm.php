<?php

namespace App\Forms;

use Kris\LaravelFormBuilder\Form;

class ClassGenForm extends Form
{
    public function buildForm()
    {
        $this->add('days', 'number', [
                'rules' => 'required',
                'attr' => ['class' => 'form-control w-25 d-inline-block'],
                'label' => 'Days',
                'default_value' => 0,
                'help_block' => [
                    'text' => 'Generate classRecords N days before. 对前X天的生成！0代表今天',
                    'tag' => 'p',
                    'attr' => ['class' => 'help-block'],
                ],
            ])
            ->add('submit', 'submit', [
                'label' => 'Generate',
                'attr'  => ['class' => 'btn btn-confirm btn-danger d-inline-block'],
            ]);
    }
}
