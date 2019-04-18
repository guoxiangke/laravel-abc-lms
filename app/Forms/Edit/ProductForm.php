<?php

namespace App\Forms\Edit;

use Kris\LaravelFormBuilder\Form;
use App\Models\PayMethod;
use App\Models\Contact;
use App\Models\School;

class ProductForm extends Form
{
    public function buildForm()
    {
        $product = $this->getData('entity');
        if(!$product) return;
        $this->add('name', 'text', [
                'rules' => 'required',
                'value' => $product->name,
                'label' => 'Name',
            ])
            ->add('price', 'text', [
                'rules' => 'required',
                'value' => $product->price,
                'attr' => ['placeholder' => '单位元,可带2为小数'],
                'label' => 'Price',
            ])
            ->add('description', 'text', [
                'rules' => 'required',
                'value' => $product->description,
                'label' => 'Description',
            ])
            ->add('image', 'file', [
                'label' => 'Image',
            ])
            ->add('remark', 'textarea', [
                'label' => '备注',
                'value' => $product->remark,
                'attr' => ['rows' => 2],
            ])
            ->add('submit', 'submit', [
                'label' => 'Save',
                'attr' => ['class' => 'btn btn-outline-primary'],
            ]);
    }
}
