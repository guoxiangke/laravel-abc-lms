<?php

namespace App\Forms;

use Kris\LaravelFormBuilder\Form;
use App\Models\PayMethod;
use App\Models\Contact;
use App\Models\School;

class ProductForm extends Form
{
    public function buildForm()
    {
        $this->add('name', 'text', [
                'rules' => 'required',
                'label' => 'Name',
            ])
            ->add('price', 'number', [
                'rules' => 'required',
                'label' => 'Price',
            ])
            ->add('description', 'text', [
                'rules' => 'required',
                'label' => 'Description',
            ])
            ->add('image', 'file', [
                'label' => 'Image',
            ])
            ->add('remark', 'textarea', [
                'label' => '备注',
                'attr' => ['rows' => 2],
            ])
            ->add('submit', 'submit', [
                'label' => 'Save',
                'attr' => ['class' => 'btn btn-outline-primary'],
            ]);
    }
}
