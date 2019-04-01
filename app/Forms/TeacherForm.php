<?php

namespace App\Forms;

use Kris\LaravelFormBuilder\Form;
use App\Models\PayMethod;
use App\Models\Contact;
use App\Models\School;
use App\Models\Zoom;

class TeacherForm extends Form
{
    public function buildForm()
    {
        //select zooms un-used!
        $zooms = Zoom::with('teacher')->get()->filter(function($zoom){
            return !$zoom->teacher;
        })->pluck('email','id')->toArray();

        $this->add('school_id', 'select', [
                'label' => 'School',
                'choices' => School::all()->pluck('name', 'id')->toArray(),
                'empty_value' => '=== Select or Freelancer ==='
            ])
            ->add('profile_name', 'text', ['label' => '姓名*'])
            ->add('user_password', 'text', [
                'label' => '登陆密码',
                'attr' => ['placeholder' => '默认：Teacher123']
            ])
            ->add('profile_telephone', 'tel', [
                'rules' => 'required|min:8',
                'label' => '手机号*',
            ])
            ->add('contact_type', 'select', [
                'label' => '联系方式*',
                'choices' => Contact::TYPES,
                // 'selected' => 1, //'PayPal'
                'empty_value' => '=== Select ==='
            ])
            ->add('contact_number', 'text',[
                'rules' => 'required|min:4',
                'label' => '联系方式账户ID*'
            ])
            ->add('contact_remark', 'textarea', [
                'label' => '联系方式备注',
                'attr' => ['rows' => 2, 'placeholder'=>'登陆邮箱：teacher_name@wx/skype/qq.com'],
            ])
            ->add('zoom_id', 'select', [
                'label' => 'Zoom',
                'choices' => $zooms,
                'empty_value' => '=== Select ===',
                'help_block' => [
                    'text' => '选择一个已有的zoomId分配给新建的Teacher，或者填写下面3个内容创建一个新zoom',
                    'tag' => 'small',
                    'attr' => ['class' => 'form-text text-muted']
                ],
            ])
            ->add('zoom_email', 'email', [
                'label' => 'Zoom邮箱',
                'attr' => ['placeholder' => '新增Zoom登陆邮箱'],
            ])
            ->add('zoom_password', 'text', [
                'label' => 'Zoom密码',
                'attr' => ['placeholder' => '新增Zoom登陆密码']
            ])
            ->add('zoom_pmi', 'text', [
                'label' => 'Zoom PMI',
                'attr' => ['placeholder' => '新增ZoomPMI']
            ])
            ->add('profile_sex', 'select', [
                'label' => '性别',
                'rules' => 'required',
                'choices' => ['女','男'],
                'selected' => 0,
                'empty_value' => '=== Select ==='
            ])
            ->add('profile_birthday', 'date', ['label' => '生日'])
            ->add('pay_method', 'select', [
                'label' => '付款方式（中教必填）',
                'choices' => PayMethod::TYPES,
                'selected' => 1, //'PayPal'
                'empty_value' => '=== Select ==='
            ])
            ->add('pay_number', 'text',[
                'label' => '付款账户ID（中教必填）'
            ])
            ->add('pay_remark', 'textarea', [
                'label' => '付款方式备注',
                'attr' => ['rows' => 2],
            ])
            ->add('submit', 'submit', [
                'label' => 'Save',
                'attr' => ['class' => 'btn btn-outline-primary'],
            ]);
    }
}
