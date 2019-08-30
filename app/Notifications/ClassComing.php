<?php

namespace App\Notifications;

use App\Models\ClassRecord;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Yansongda\LaravelNotificationWechat\WechatChannel;
use Yansongda\LaravelNotificationWechat\WechatMessage;

class ClassComing extends Notification
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

        $time = $this->classRecord->generated_at->format('H:i m/d 周N');
        $zoomId = $this->classRecord->teacher->teacher->zoom->pmi;
        $data = [
            'first'    => '👉您好，您预约的外教课堂即将开始！',
            'keyword1' => '大象英语外教一对一',
            'keyword2' => $time,
            'remark'   => ["外教Zoom：{$zoomId}\n 请先预习，准备好电脑、耳麦、测试网络，等待上课。", '#173177'],
        ];

        return WechatMessage::create()
            ->to($this->openId)
            ->template('Ryh6URaE8sLcuYYeoh63l81dOpQ-FxB0c023hdZz5Ik')
            ->url('https://lms.abc-chinaedu.com/login/wechat')
            ->data($data);
    }
}
