<?php

namespace App\Forms;

use Kris\LaravelFormBuilder\Form;

class StudentsImportForm extends Form
{
    public function buildForm()
    {
        $this->add('field_excel', 'file', [
            'rules' => 'required',
            'label'      => '选择excel表格',
            'attr'  => ['accept' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'],
            'help_block' => [
                'text' => '<br/>⬇️<a href="/templates/users.xlsx">下载模版</a>',
                'tag' => 'div',
                'attr' => ['class' => 'help-block'],
            ],
        ])
            ->add('submit', 'submit', [
                'label' => 'Generate',
                'attr'  => ['class' => 'btn submit-confirm btn-danger d-inline-block'],
            ]);
    }
}
