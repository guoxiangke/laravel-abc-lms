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
        return [FacebookChannel::class]; //, TwilioChannel::class
    }

    public function toFacebook($notifiable)
    {
        return FacebookMessage::create()
            // ->to($this->user->fb_messenger_user_id) // Optional
            ->text('One of your invoices has been paid!')
            ->isUpdate() // Optional
            ->isTypeRegular() // Optional
            // Alternate method to provide the notification type.
            // ->notificationType(NotificationType::REGULAR) // Optional
            ->buttons([
                Button::create('Call Us for Support!', '+1(212)555-2368')->isTypePhoneNumber(),
                // Button::create('Start Chatting', ['invoice_id' => $this->invoice->id])->isTypePostback() // Custom payload sent back to your server
            ]); // Buttons are optional as well.
    }

    public function toTwilio($notifiable)
    {
        // $classRecord = $notifiable
        $time = $notifiable->generated_at->format('H:i D');
        $studentName = $notifiable->user->name;
        $teacherName = $notifiable->teacher->name;
        $message = "【EE-Urgent】Class for {$studentName} at {$time}, Online and send a ready message plz!";
        $result = (new TwilioSmsMessage())->content($message);
        \Log::info(__CLASS__, [__FUNCTION__, $result]);

        return $result;
    }
}
