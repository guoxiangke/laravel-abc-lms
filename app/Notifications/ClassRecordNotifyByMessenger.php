<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use NotificationChannels\Facebook\Components\Button;
use NotificationChannels\Facebook\FacebookChannel;
use NotificationChannels\Facebook\FacebookMessage;
use NotificationChannels\Twilio\TwilioChannel;
use NotificationChannels\Twilio\TwilioSmsMessage;

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
        $time = $notifiable->generated_at->format('F j H:i D');
        $studentName = $notifiable->user->name;
        $template = "Student: $studentName\nTime: $time\nActions:\n😎Click Ready button in 5 minutes before the class.\n😱Click Emergency button if you can't call the student.\n🤔Click Offline button if student is not online in 3~5 minutes.\nFor AOL/Absence click ".route('classRecords.show', $notifiable->id);

        return FacebookMessage::create()
            ->text($template)
            ->buttons([
                Button::create('Ready    ', ['id' => $notifiable->id, 'type'=>'ready'])->isTypePostback(),
                Button::create('Emergency', ['id' => $notifiable->id, 'type'=>'abscent'])->isTypePostback(),
                Button::create('Offline  ', ['id' => $notifiable->id, 'type'=>'offline'])->isTypePostback(),
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
