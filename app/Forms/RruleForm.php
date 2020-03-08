<?php

namespace App\Forms;

use Kris\LaravelFormBuilder\Form;

class RruleForm extends Form
{
    public function buildForm()
    {
        $order = $this->getData('entity');
        if ($order) {
            $this->add('order_id', 'hidden', [
                'label' => $order->title,
                'rules' => 'required',
                'value' => $order->id,
            ]);
            $this->add('order', 'static', [
                'label' => '订单Id',
                'rules' => 'required',
                'value' => $order->title,
            ]);
        }
        $this
            ->add('type', 'checkbox', [
                'value'      => 1,
                'label'      => '计划类型',
                'checked'    => false,
                'help_block' => [
                    'text' => '默认是请假，如不是请打✓✔☑（即创建新的上课计划） ',
                    'tag'  => 'small',
                    'attr' => ['class' => 'form-text text-muted'],
                ],
            ])
            ->add('string', 'textarea', [
                'label' => '计划',
                'rules' => 'required',
                'attr'  => [
                    'rows'        => 3,
                    'placeholder' => "DTSTART:20190330T180000Z\nRRULE:FREQ=DAILY;COUNT=5;INTERVAL=1;WKST=MO;BYDAY=TU",
                ],
                'help_block' => [
                    'text' => '创建时共2行，第一行：第一次请假日期+时间(不可忽略，必须与上课的计划相同的时间)，第二行：请假规律 <a target="_blank" href="https://jakubroztocil.github.io/rrule/">Gen a rule.toString()/点击生成内容</a><br/>COUNT=？是总请假天数（或上课计划课时数）',
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
