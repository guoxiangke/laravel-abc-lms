<?php

namespace App\Notifications;

use App\Models\ClassRecord;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Yansongda\LaravelNotificationWechat\WechatChannel;
use Yansongda\LaravelNotificationWechat\WechatMessage;

class ClassRecordNotifyByWechat extends Notification
{
    use Queueable;

    protected $classRecord;
    protected $openId;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(ClassRecord $classRecord, $openId)
    {
        $this->classRecord = $classRecord;
        $this->openId = $openId;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return [WechatChannel::class];
    }

    public function toWechat($notifiable)
    {
        // https://github.com/yansongda/laravel-notification-wechat
        // $accessToken = "n2McCJoqWKRi7hJbKFOqftgtU_EX6u2ZOvIi1lpx0fZJ3YW5Oo4iIPZEpi0ecct2lHMagK84xGF5rEm_DSMKrZFfCEZiYw1yZN3nZXzFSlHM-y88sIi5-dYeeCWx9S1iHXWaAJAMCB";
        // 'oH16Q5hX4-75CyIPAvXpNr7I4PXo'
        $time = $this->classRecord->generated_at->format('H:i （n月d日 l）');
        $zoomId = $this->classRecord->teacher->teacher->pmi;
        $data = [
            'first'    => ['尊贵的学员您好，您预约的在线外教课堂马上开始！', '#4F4AEF'],
            'keyword1' => ['大象英语外教一对一', '#FF246C'],
            'keyword2' => [$time, '#FF246C'],
            'remark'   => ["外教ID：{$zoomId}\n准备工作：\n 👉1.请打开电脑、测试耳麦及网络\n 👉2.请打开教材预习本次学习内容\n 👉3.请提前3分钟进入网络教室", '#4F4AEF'],
        ];

        return WechatMessage::create()
            ->to($this->openId)
            ->template('Ryh6URaE8sLcuYYeoh63l81dOpQ-FxB0c023hdZz5Ik')
            ->url(config('app.url_cn').'/login/wechat')
            ->data($data);
    }
}
