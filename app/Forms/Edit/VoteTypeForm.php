<?php

namespace App\Forms\Edit;

use Kris\LaravelFormBuilder\Form;

class VoteTypeForm extends Form
{
    public function buildForm()
    {
        $entity = $this->getData('entity');
        if (! $entity) {
            return;
        }
        $this->add('name', 'text', [
                'rules' => 'required',
                'label' => 'Name',
                'value' => $entity->name,
            ])
            ->add('description', 'text', [
                'rules' => 'required',
                'label' => 'Description',
                'value' => $entity->description,
            ])
            //一旦设定，不可chang
            ->add('type', 'static', [
                'label' => 'Type',
                'value' => $entity->type,
            ])
            ->add('votable_type', 'static', [
                'label'       => 'VotableType',
                'value' => $entity->votable_type,
            ])//App/Models/classRecord
            ->add('submit', 'submit', [
                'label' => 'Save',
                'attr'  => ['class' => 'btn btn-outline-primary'],
            ]);
    }
}
