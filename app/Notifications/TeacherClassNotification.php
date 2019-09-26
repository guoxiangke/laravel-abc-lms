<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use NotificationChannels\Twilio\TwilioChannel;
use NotificationChannels\Twilio\TwilioSmsMessage;

//紧急短信通知老师上课！ UrgentClassComing for teacher!!!
class TeacherClassNotification extends Notification
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
        $time = $notifiable->generated_at->format('H:i D');
        $studentName = $notifiable->user->name;
        $teacherName = $notifiable->teacher->name;
        $message = "【EE-Urgent】Class for {$studentName} at {$time}, Online and send a ready message plz!";
        $result = (new TwilioSmsMessage())->content($message);
        \Log::info(__CLASS__, [__FUNCTION__, $message, $result]);

        return $result;
    }
}
