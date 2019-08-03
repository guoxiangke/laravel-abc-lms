<?php

namespace App\Forms;

use Kris\LaravelFormBuilder\Form;

class VideoForm extends Form
{
    public function buildForm()
    {
        $classRecord = $this->getData('entity');
        if (! $classRecord) {
            return;
        }
        $this->add('start_time', 'text', [
                'rules' => 'required',
                'label' => 'Begin',
                'value' => '01:01',
                'help_block' => [
                    'text' => '剪辑开始时间，格式为 MM:SS 忽略上下午',
                    'tag'  => 'small',
                    'attr' => ['class' => 'form-text text-muted'],
                ],
            ])
            ->add('end_time', 'text', [
                'rules' => 'required',
                'label' => 'End',
                'value' => '01:10',
                'help_block' => [
                    'text' => '剪辑结束时间，格式为 MM:SS 忽略上下午',
                    'tag'  => 'small',
                    'attr' => ['class' => 'form-text text-muted'],
                ],
            ])
            ->add('class_record_id', 'hidden', [
                'value' => $classRecord->id,
                'label' => 'class_record_id',
            ])
            ->add('submit', 'submit', [
                'label' => 'Save',
                'attr'  => ['class' => 'btn btn-outline-primary'],
            ]);
    }
}
