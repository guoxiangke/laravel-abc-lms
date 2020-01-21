<?php

namespace App\Forms\Edit;

use App\Models\Agency;
use App\Models\Contact;
use App\Models\PayMethod;
use Kris\LaravelFormBuilder\Form;

class AgencyForm extends Form
{
    public function buildForm()
    {
        //agency_id 上级代理
        $agency = $this->getData('entity');
        if (! $agency) {
            return;
        }
        $user = $agency->user;
        $paymethod = $user->paymethod;

        $profile = $user->profiles->first();
        // $profile = $teacher->profiles->first();
        $contact = $profile->contacts->first();
        $recommend = Agency::getAllReference();
        $this->add('profile_name', 'text', [
                'rules' => 'required',
                'value' => $profile->name,
                'label' => '姓名',
            ])
            ->add('telephone', 'tel', [
                'label'      => '手机号',
                'rules'      => 'required|size:14',
                'value'      => $profile->telephone,
                'help_block' => [
                    'text' => '手机号可用于登陆,带+86，共计14位',
                    'tag'  => 'small',
                    'attr' => ['class' => 'form-text text-muted'],
                ],
            ])
            ->add('user_password', 'text', [
                'label'      => '登陆密码',
                'help_block' => [
                    'text' => '可用于登陆，密码默认：Agency1234',
                    'tag'  => 'small',
                    'attr' => ['class' => 'form-text text-muted'],
                ],
            ])
            ->add('contact_type', 'select', [
                'label'       => '其他联系方式',
                'rules'       => 'required',
                'selected'    => $contact ? $contact->type : '',
                'choices'     => Contact::TYPES,
                'empty_value' => '=== Select ===',
            ])
            ->add('contact_number', 'text', [
                'rules' => 'required|min:4',
                'value' => $contact ? $contact->number : '',
                'label' => '联系方式账户ID',
            ])
            ->add('contact_remark', 'textarea', [
                'label' => '联系方式备注',
                'value' => $contact ? $contact->remark : '',
                'attr'  => ['rows' => 2],
            ])
            ->add('profile_sex', 'select', [
                'label'       => '性别',
                'rules'       => 'required',
                'choices'     => ['女', '男'],
                'selected'    => $profile->sex,
                'empty_value' => '=== Select ===',
            ])
            ->add('profile_birthday', 'date', [
                'label' => '生日',
                'value' => $profile->birthday ? $profile->birthday->format('Y-m-d') : null,
            ])
            ->add('pay_method', 'select', [
                'label'       => '付款方式',
                'rules'       => 'required',
                'choices'     => PayMethod::TYPES,
                'selected'    => $paymethod->type,
                'empty_value' => '=== Select ===',
            ])
            ->add('pay_number', 'text', [
                'rules' => 'required',
                'value' => $paymethod->number,
                'label' => '付款账户ID',
            ])
            ->add('pay_remark', 'textarea', [
                'label' => '付款方式备注',
                'value' => $paymethod->remark,
                'attr'  => ['rows' => 2],
            ])
            ->add('agency_name', 'text', [
                'rules' => 'required',
                'label' => '代理机构名称',
            ])
            ->add('agency_type', 'select', [
                'label'       => '代理类型',
                'choices'     => Agency::TYPES,
                'selected'    => $agency->type,
                'empty_value' => '=== Select ===',
            ])
            ->add('agency_discount', 'number', [
                'value' => $agency->discount,
                'rules' => 'min:0|max:100',
                'label' => '优惠折扣0-100',
            ])//todo 0-100 check!
            ->add('agency_id', 'select', [
                'label'       => '介绍人/推荐人',
                'selected'    => $profile->recommend_uid,
                'choices'     => $recommend, //不包括自己
                'empty_value' => '=== Select ===',
            ])
            ->add('submit', 'submit', [
                'label' => 'Save',
                'attr'  => ['class' => 'btn btn-outline-primary'],
            ]);
    }
}
