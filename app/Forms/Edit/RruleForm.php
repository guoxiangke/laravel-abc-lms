<?php

namespace App\Forms\Edit;

use Kris\LaravelFormBuilder\Form;

class RruleForm extends Form
{
    public function buildForm()
    {
        $rrule = $this->getData('entity');
        if (! $rrule) {
            return;
        }

        $this->add('order', 'static', [
                    'label' => '订单Id',
                    'rules' => 'required',
                    'value' => $rrule->order->title,
            ])
            ->add('start_at', 'datetime-local', [
                'label' => '日期时间',
                'rules' => 'required',
                'value' => $rrule->start_at->format('Y-m-d\TH:i'),
            ])
            ->add('string', 'textarea', [
                'label' => '计划',
                'value' => $rrule->string,
                'rules' => 'required',
                'attr'  => [
                    'rows'        => 3,
                    'placeholder' => "DTSTART:20190330T180000Z\nRRULE:FREQ=DAILY;COUNT=5;INTERVAL=1;WKST=MO;BYDAY=TU",
                ],
                'help_block' => [
                    'text' => '创建时共2行，第一行：第一次请假日期+时间(忽略)，第二行：请假规律 <a target="_blank" href="https://jakubroztocil.github.io/rrule/">Gen a rule.toString()/点击生成内容</a><br/>⚠️编辑时共1行，即只有第二行内容，默认已填充<br/>COUNT=？是总请假天数（或上课计划课时数）',
                    'tag'  => 'small',
                    'attr' => ['class' => 'form-text text-muted'],
                ],
            ])
            ->add('submit', 'submit', [
                'label' => 'Save',
                'attr'  => ['class' => 'btn btn-outline-primary'],
            ]);
    }
}
