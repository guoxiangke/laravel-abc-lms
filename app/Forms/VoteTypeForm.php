<?php

namespace App\Forms;

use Kris\LaravelFormBuilder\Form;

class VoteTypeForm extends Form
{
    public function buildForm()
    {
        $classes = getClassesList(app_path('Models'));
        foreach ($classes as $key => $class) {
            $option[$class->classname] = $class->classname;
        }
        $this->add('name', 'text', [
            'rules' => 'required',
            'label' => 'name',
        ])
            ->add('description', 'text', [
                'rules' => 'required',
                'label' => 'description',
            ])
            ->add('type', 'number', [
                'rules' => 'required',
                'label' => 'type',
                'value' => 1,
            ])
            ->add('votable_type', 'select', [
                'label'       => 'votable_type',
                'rules'       => 'required',
                'choices'     => $option,
            ])//App/Models/classRecord
            ->add('submit', 'submit', [
                'label' => 'Save',
                'attr'  => ['class' => 'btn btn-outline-primary'],
            ]);
    }
}
