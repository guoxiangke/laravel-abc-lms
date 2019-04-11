<?php

namespace App\Forms\Edit;

use Kris\LaravelFormBuilder\Form;
use App\Models\PayMethod;
use App\Models\Contact;
use App\Models\School;
use App\Models\Zoom;

class TeacherForm extends Form
{
    public function buildForm()
    {
        $teacher = $this->getData('entity');
        if(!$teacher) return;
        $user = $teacher->user;
        $schoolId = $teacher->school?$teacher->school->id:0;
        $zoomId = $teacher->zoom?$teacher->zoom->id:0;
        // dd($zoomId);
        $paymethod = $user->paymethod;
        
        $profile = $user->profiles->first();
        // $profile = $teacher->profiles->first();
        $contact = $profile->contacts->first();


        //select zooms un-used!
        $zooms = Zoom::with('teacher')
            ->orderBy('id','desc')->get()->filter(function($zoom) use($zoomId){
                //它自己和没有分配的id
                return !$zoom->teacher || $zoom->id==$zoomId;
            })
            ->pluck('email','id')
            ->toArray();

        $this->add('school_id', 'select', [
                'label' => 'School',
                'choices' => School::all()->pluck('name', 'id')->toArray(),
                'selected' => $schoolId,
                'empty_value' => 'Freelancer/自由职业'
            ])
            ->add('profile_name', 'text',
                ['rules' => 'required','label' => '姓名','value'=>$profile->name]
            )
            ->add('user_password', 'text', [
                'label' => '登陆密码',
                'help_block' => [
                    'text' => '不填，默认为：Teacher123',
                    'tag' => 'small',
                    'attr' => ['class' => 'form-text text-muted']
                ],
            ])
            ->add('profile_telephone', 'tel', [
                'value' => $profile->telephone,
                'rules' => 'required|min:13',//+639158798611
                'label' => '手机号',
                'help_block' => [
                    'text' => '外教带+63,共计13位；中教带+86',
                    'tag' => 'small',
                    'attr' => ['class' => 'form-text text-muted']
                ],
            ])
            ->add('contact_type', 'select', [
                'label' => '联系方式',
                'choices' => Contact::TYPES,
                'selected' =>  $contact?$contact->type:0,
                'empty_value' => '=== Select ==='
            ])
            ->add('contact_number', 'text',[
                'rules' => 'required|min:4',
                'value'=> $contact?$contact->number:null,
                'label' => '联系方式账户ID'
            ])
            ->add('contact_remark', 'textarea', [
                'label' => '联系方式备注',
                'value'=> $contact?$contact->remark:null,
                'help_block' => [
                    'text' => '登陆邮箱：t_name@teacher.com',
                    'tag' => 'small',
                    'attr' => ['class' => 'form-text text-muted']
                ],
                'attr' => ['rows' => 2],
            ])
            ->add('zoom_id', 'select', [
                'label' => 'Zoom',
                'choices' => $zooms,
                'selected' => $zoomId,
                'empty_value' => '=== Select ===',
                'help_block' => [
                    'text' => '选择一个已有的zoomId分配给新建的Teacher，或者填写下面3个内容同时创建一个新zoom',
                    'tag' => 'small',
                    'attr' => ['class' => 'form-text text-muted']
                ],
            ])
            ->add('profile_sex', 'select', [
                'label' => '性别',
                'rules' => 'required',
                'choices' => ['女','男'],
                'selected' => $profile->sex,
                'empty_value' => '=== Select ==='
            ])
            ->add('profile_birthday', 'date', [
                'label' => '生日',
                'value'=>$profile->birthday?$profile->birthday->format('Y-m-d'):NULL
            ])
            ->add('pay_method', 'select', [
                'label' => '付款方式（中教必填）',
                'choices' => PayMethod::TYPES,
                'selected' => 1, //'PayPal'
                'selected' => $paymethod?$paymethod->type:0,
                'empty_value' => '=== Select ==='
            ])
            ->add('pay_number', 'text',[
                'label' => '付款账户ID（中教必填）',
                'value' => $paymethod?$paymethod->number:null,
            ])
            ->add('pay_remark', 'textarea', [
                'label' => '付款方式备注',
                'attr' => [
                    'rows' => 2,
                    'value' =>  $paymethod?$paymethod->remark:null,
                ]
            ])
            ->add('submit', 'submit', [
                'label' => 'Save',
                'attr' => ['class' => 'btn btn-outline-primary'],
            ]);
    }
}
