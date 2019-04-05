<?php

namespace App\Forms;

use Kris\LaravelFormBuilder\Form;
use App\Models\Book;

class BookForm extends Form
{
    public function buildForm()
    {
        $this->add('name', 'text', [
                'rules' => 'required',
                'label' => 'Name',
            ])
            ->add('type', 'select', [
                'label' => '类型',
                'rules' => 'required',
                'choices' => Book::TYPES,
                'empty_value' => '=== Select ==='
            ])
            ->add('publisher', 'text', [
                'label' => 'Publisher',
            ])
            ->add('path', 'url', [
                'label' => '路径/分享链接',
            ])
            ->add('page', 'number', [
                'label' => '页数',
                'value' => 0,//default 0
            ])
            ->add('submit', 'submit', [
                'label' => 'Save',
                'attr' => ['class' => 'btn btn-outline-primary'],
            ]);
    }
}
