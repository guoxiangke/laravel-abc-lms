<?php

namespace App\Forms\Edit;

use App\User;
use App\Models\ClassRecord;
use Kris\LaravelFormBuilder\Form;
use Illuminate\Support\Facades\Auth;

class ClassRecordForm extends Form
{
    public function buildForm()
    {
        $classRecord = $this->getData('entity');
        if (! $classRecord) {
            return;
        }
        $generated_at = $classRecord->generated_at->format('Y-m-d\TH:i');
        $rrule_id = $classRecord->rrule->order->title;

        $teachers = User::role('teacher')->with('profiles')->get()->pluck('profiles.0.name', 'id')->toArray();
        $agencies = User::role('agency')->with('profiles')->get()->pluck('profiles.0.name', 'id')->toArray();

        $remark = $classRecord->remark;
        $exception = $classRecord->exception;
        $exceptions = ClassRecord::EXCEPTION_TYPES;
        $user = Auth::user();
        //根据角色拥有编辑权限 不是老师才能看到状态更改！
        if (! $user->hasRole('teacher')) {
            $this->add('exception', 'select', [
                'label'    => 'Status',
                'rules'    => 'required',
                'choices'  => $exceptions,
                'selected' => $exception,
            ])
            ->add('generated_at', 'datetime-local', [
                'label' => '日期时间',
                'rules' => 'required',
                'value' => $generated_at,
            ])
            ->add('teacher_uid', 'select', [
                'label'       => 'Teacher',
                'rules'       => 'required',
                'choices'     => $teachers,
                'selected'    => $classRecord->teacher_uid,
                'empty_value' => '=== Select ===',
            ])
            ->add('agency_uid', 'select', [
                'label'       => 'Agency',
                'choices'     => $agencies,
                'selected'    => $classRecord->agency_uid,
                'empty_value' => '=== Select ===',
            ])
            ->add('rrule_id', 'static', [
                'value' => $rrule_id,
                'label' => 'Order',
            ]);
        }

        $this->add('remark', 'textarea', [
                'label' => 'Evaluation',
                'value' => $remark,
                'attr'  => [
                    'rows'        => 10,
                    'placeholder' => "**Book:** ??\r\n**Page:** ??\r\n**Mispronounced word(s):** ??\r\n**Corrected Sentence(s):** ??\r\n**Comment:** ??\r\n**Homework:** ??",
                ],
                'help_block' => [
                    'text' => '**This is bold text**<br/>~~This was mistaken text~~<br/>More <a href="https://help.github.com/en/articles/basic-writing-and-formatting-syntax#lists" target="_blank">Markdown</a> cheatsheet.<br/><s>You can copy below as a template</s> (Don\'t remove **):<br/>**Book:** 《 Let\'s go 》<br/>**Today\'s lesson:** Page *7-9<br/>**Next lesson:** Page *10<br/><br/>**Mispronounced word(s):**<br/>1. <br/>2.<br/><br/>**Corrected Sentence(s):**<br/>1.<br/>2.<br/><br/>**Comment:**<br/>The student needs to practice reading.<br/><br/>**Homework:**<br/>The student needs to practice reading.<br/>',
                    'tag'  => 'small',
                    'attr' => ['class' => 'form-text text-muted'],
                ],
            ]);

        $mp3 = $classRecord->getFirstMedia('mp3');
        $helpText = $mp3 ? '<br/>There alread has one mp3.<br/>Store Path: '.$mp3->getPath() : '';
        $this->add('mp3', 'file', [
            'label'      => $mp3 ? 'Re-Upload Mp3' : 'Mp3',
            'attr'       => ['accept' => '.mp3,.m4a'],
            'help_block' => [
                'text' => 'Allow File Type : .mp3, .m4a'.$helpText,
                'tag'  => 'small',
                'attr' => ['class' => 'form-text text-muted'],
            ],
        ]);
        $mp4 = $classRecord->getFirstMedia('mp4');
        $helpText = $mp4 ? '<br/>There alread has one mp4.<br/>Store Path: '.$mp4->getPath() : '';
        $this->add('mp4', 'file', [
            'label'      => $mp4 ? 'Re-Upload Mp4' : 'Mp4',
            'attr'       => ['accept' => '.mp4'],
            'help_block' => [
                'text' => 'Allow File Type : .mp4'.$helpText,
                'tag'  => 'small',
                'attr' => ['class' => 'form-text text-muted'],
            ],
        ]);

        $this->add('submit', 'submit', [
            'label' => 'Save',
            'attr'  => ['class' => 'btn btn-outline-primary'],
        ]);
    }
}
