<?php

namespace App\Forms\Edit;

use App\Models\Book;
use Kris\LaravelFormBuilder\Form;

class BookForm extends Form
{
    public function buildForm()
    {
        $book = $this->getData('entity');
        if (! $book) {
            return;
        }
        $this->add('name', 'text', [
                'rules' => 'required',
                'label' => 'Name',
                'value' => $book->name,
            ])
            ->add('type', 'select', [
                'label'       => '类型',
                'rules'       => 'required',
                'selected'    => $book->type,
                'choices'     => Book::TYPES,
                'empty_value' => '=== Select ===',
            ])
            ->add('publisher', 'text', [
                'label' => 'Publisher',
                'value' => $book->publisher,
            ])
            ->add('path', 'url', [
                'label' => '路径/分享链接',
                'value' => $book->url,
            ])
            ->add('page', 'number', [
                'label' => '页数',
                'value' => $book->page,
            ])
            ->add('submit', 'submit', [
                'label' => 'Save',
                'attr'  => ['class' => 'btn btn-outline-primary'],
            ]);
    }
}
