<?php

namespace App\Forms;

use Kris\LaravelFormBuilder\Form;
use App\Models\Rrule;
use App\Models\Order;

class RruleForm extends Form
{
    public function buildForm()
    {

        $order = $this->getData('entity');
        // $order->load(['user','user.profiles']);
        if($order){
            $this->add('order', 'static', [
                    'label' => '订单Id',
                    'value' => $order->title,
                ]);
        }
        $this
            ->add('type', 'checkbox', [
                'value' => 1,
                'label' => '计划类型',
                'checked' => false,
                'help_block' => [
                    'text' => '默认是请假，如不是请打✓✔☑（即创建新的上课计划） ',
                    'tag' => 'small',
                    'attr' => ['class' => 'form-text text-muted']
                ],
            ])
            ->add('string', 'textarea', [
                'label' => '计划*',
                'attr' => [
                    'rows' => 3,
                    'placeholder' => "DTSTART:20190330T180000Z\nRRULE:FREQ=DAILY;COUNT=5;INTERVAL=1;WKST=MO;BYDAY=TU"
                ],
                'help_block' => [
                    'text' => '创建时共2行，第一行：第一次请假日期+时间(忽略)，第二行：请假规律 <a target="_blank" href="https://jakubroztocil.github.io/rrule/">Gen a rule.toString()/点击生成内容</a><br/>⚠️编辑时共1行，即只有第二行内容，默认已填充<br/>COUNT=？是总请假天数（或上课计划课时数）',
                    'tag' => 'small',
                    'attr' => ['class' => 'form-text text-muted']
                ],
            ])
            ->add('submit', 'submit', [
                'label' => 'Save',
                'attr' => ['class' => 'btn btn-outline-primary'],
            ]);
    }
}
