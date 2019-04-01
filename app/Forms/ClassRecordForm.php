<?php

namespace App\Forms;

use Kris\LaravelFormBuilder\Form;
use App\Models\ClassRecord;
use Illuminate\Support\Facades\Auth;

class ClassRecordForm extends Form
{
    public function buildForm()
    {
        $classRecord = $this->getData('entity');
        if($classRecord){
            $generated_at = $classRecord->generated_at->format('Y-m-d H:i');
            $rrule_id = $classRecord->rrule->order->title;
            $teacher_uid =  $classRecord->teacher->profile->name;
            $remark =  $classRecord->remark;
            $exception =  $classRecord->exception;
        }
        $exceptions = ClassRecord::EXCEPTION_TYPES;

        $user = Auth::user();
        //根据角色拥有编辑权限 不是老师才能看到状态更改！
        if(!$user->hasRole('teacher')){
            $this->add('exception', 'select', [
                'label' => 'Status：',
                'choices' => $exceptions,
                'selected' => empty($exception)?null:$exception,
            ])
            ->add('teacher_uid', 'static', [
                'value' => empty($teacher_uid)?null:$teacher_uid,
                'label' => 'Teacher：',
            ]);

            $this->add('rrule_id', 'static', [
                'value' => empty($rrule_id)?null:$rrule_id,
                'label' => 'Order：',
            ]);
        }
        // dd($classRecord);
        $this->add('remark', 'textarea', [
                'label' => 'Evaluation：',
                'value' => empty($remark)?null:$remark,
                'attr' => [
                    'rows' => 10,
                    'placeholder' =>"**Book:** ??\r\n**Page:** ??\r\n**Mispronounced word(s):** ??\r\n**Corrected Sentence(s):** ??\r\n**Comment:** ??\r\n**Homework:** ??",
                ],
                'help_block' => [
                    'text' => '**This is bold text**<br/>~~This was mistaken text~~<br/>More <a href="https://help.github.com/en/articles/basic-writing-and-formatting-syntax#lists" target="_blank">Markdown</a> cheatsheet.<br/>You can copy blow as a template:<br/>**Book:** ABCs<br/>**Page:** 15<br/>**Mispronounced word(s):** ~~wrong~~<br/>**Corrected Sentence(s):** I is 10.<br/>**Comment:** ??<br/>**Homework:** ??<br/>',
                    'tag' => 'small',
                    'attr' => ['class' => 'form-text text-muted']
                ],
            ]);
        
        if($classRecord)
        {   
            $mp3 = $classRecord->getFirstMediaUrl('mp3');
            $helpText = $mp3?'<br/>There alread has one mp3.<br/>Store Path: ' . $mp3 : '';
            $this->add('mp3', 'file', [
                'label' => $mp3?'Re-Upload Mp3?':'Mp3',
                'attr' => ['accept' => '.mp3,.m4a'],
                'help_block' => [
                    'text' => 'Allow File Type : .mp3, .m4a'. $helpText,
                    'tag' => 'small',
                    'attr' => ['class' => 'form-text text-muted']
                ],
            ]);
            $mp4 = $classRecord->getFirstMediaUrl('mp4');
            $helpText = $mp4?'<br/>There alread has one mp4.<br/>Store Path: ' . $mp4 : '';
            $this->add('mp4', 'file', [
                'label' => $mp4?'Re-Upload Mp4?':'Mp4',
                'attr' => ['accept' => '.mp4'],
                'help_block' => [
                    'text' => 'Allow File Type : .mp4'. $helpText,
                    'tag' => 'small',
                    'attr' => ['class' => 'form-text text-muted']
                ],
            ]);
        }
        $this->add('generated_at', 'static', [
                'label' => 'Class At：',
                'value' => empty($generated_at)?null:$generated_at,
            ]);

        $this->add('submit', 'submit', [
            'label' => 'Save',
            'attr' => ['class' => 'btn btn-outline-primary'],
        ]);

    }
}
