<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use NotificationChannels\Twilio\TwilioChannel;

//紧急Upyun短信通知学生上课！ UrgentClassComing for student!!!
class ClassRecordNotifyBySms extends Notification
{
    use Queueable;
    // protected $classRecord;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return [TwilioChannel::class];
    }

    public function toTwilio($notifiable)
    {
        // $classRecord = $notifiable
        $time = $notifiable->generated_at->format('H:i');
        $studentName = $notifiable->user->name;
        $teacherName = $notifiable->teacher->name;

        // 注意：需要替换这里的请求参数和 $options['http']['header'] 中的 token
        $msg['mobile'] = str_replace('+86', '', $notifiable->routeNotificationForTwilio()); // 必填参数，多个手机号使用逗号分隔
        $msg['template_id'] = 2284;  //todo 必填参数，短信模板 ID
        $msg['vars'] = "{$time}上课提醒：{$teacherName}老师的课堂"; //'a|b'; // 选填参数，模板变量
        $upyunSmsToken = config('upyun.token.sms');
        $url = 'https://sms-api.upyun.com/api/messages';

        $options = [
            'http' => [
                'header'  => "Content-type: application/x-www-form-urlencoded\r\nAuthorization: {$upyunSmsToken}",
                'method'  => 'POST',
                'content' => http_build_query($msg),
            ],
        ];
        $context = stream_context_create($options);
        $result = file_get_contents($url, false, $context);
        \Log::info(__CLASS__, [__FUNCTION__, $msg, [$result]]);
        // @see https://help.upyun.com/knowledge-base/sms-api/#e79fade4bfa1e58f91e98081e68ea5e58fa3
        return $result;
    }
}
