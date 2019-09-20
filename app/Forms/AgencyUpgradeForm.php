<?php

namespace App\Forms;

use App\Models\Agency;
use App\Models\PayMethod;
use Kris\LaravelFormBuilder\Form;

class AgencyUpgradeForm extends Form
{
    public function buildForm()
    {
        //agency_id 上级代理
        $user = $this->getData('entity');
        if (! $user) {
            return;
        }

        $profile = $user->profiles->first();
        // $profile = $teacher->profiles->first();
        $contact = $profile->contacts->first();

        $this->add('profile_name', 'static', [
                'rules' => 'required',
                'value' => $profile->name,
                'label' => '姓名',
            ])
            ->add('telephone', 'static', [
                'rules' => 'required|min:11',
                'value' => $profile->telephone,
                'label' => '手机号',
            ])
            ->add('pay_method', 'select', [
                'label'       => '付款方式',
                'rules'       => 'required',
                'choices'     => PayMethod::TYPES,
                'selected'    => 1, //'PayPal'
                'empty_value' => '=== Select ===',
            ])
            ->add('pay_number', 'text', [
                'rules' => 'required',
                'label' => '付款账户ID',
            ])
            ->add('pay_remark', 'textarea', [
                'label' => '付款方式备注',
                'attr'  => ['rows' => 2],
            ])
            ->add('type', 'select', [
                'label'       => '代理类型',
                'choices'     => Agency::TYPES,
                'selected'    => 0,
                'empty_value' => '=== Select ===',
            ])
            ->add('discount', 'number', [
                'rules' => 'required|min:0|max:100',
                'value' => 90,
                'label' => '优惠折扣0-100',
            ])//todo 0-100 check!
            ->add('agency_uid', 'select', [
                'label'       => '介绍人/推荐人',
                'choices'     => Agency::with('profiles')->get()->pluck('profiles.0.name', 'user_id')->filter()->toArray(),
                'empty_value' => '=== Select ===',
            ])
            ->add('submit', 'submit', [
                'label' => 'Save',
                'attr'  => ['class' => 'btn btn-outline-primary'],
            ]);
    }
}
