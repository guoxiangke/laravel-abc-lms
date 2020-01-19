<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use NotificationChannels\Twilio\TwilioChannel;
use NotificationChannels\Twilio\TwilioSmsMessage;
use NotificationChannels\Facebook\FacebookChannel;
use NotificationChannels\Facebook\FacebookMessage;
use NotificationChannels\Facebook\Components\Button;
use NotificationChannels\Facebook\Enums\NotificationType;

//紧急短信通知老师上课！ UrgentClassComing for teacher!!!
class ClassRecordNotifyByMessenger extends Notification
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
        return [FacebookChannel::class]; //, TwilioChannel::class
    }

    public function toFacebook($notifiable)
    {
        $notifiable = $classRecord = ClassRecord::find(1);
        $time = $notifiable->generated_at->format('H:i D');
        $studentName = $notifiable->user->name;
        $template  = "$studentName having class at $time\n".route('classRecords.show',$notifiable->id);
        return FacebookMessage::create()
            ->text($template)
            ->buttons([
                Button::create("I am ready", ['id' => $notifiable->id, 'type'=>'ready'])->isTypePostback(),
                Button::create('I can\'t call', ['id' => $notifiable->id,'type'=>'abscent'])->isTypePostback(),
                Button::create("Student still offline", ['id' => $notifiable->id,'type'=>'offline'])->isTypePostback(),
            ]);
    }

    public function toTwilio($notifiable)
    {
        // $classRecord = $notifiable
        $time = $notifiable->generated_at->format('H:i D');
        $studentName = $notifiable->user->name;
        $teacherName = $notifiable->teacher->name;
        $message = "【EE-Urgent】Class for {$studentName} at {$time}, Online and send a ready message plz!";
        $result = (new TwilioSmsMessage())->content($message);
        return $result;
    }
}
