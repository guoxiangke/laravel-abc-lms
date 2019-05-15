<?php
# doc https://laravelacademy.org/post/19514.html
namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\Profile;

class BirthdayNotification extends Notification implements ShouldQueue
{
    use Queueable;
    protected $profile;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Profile $profile)
    {
        $this->profile = $profile;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $profile = $this->profile;
        return (new MailMessage)
                    ->subject("{$profile->name} {$profile->birthday->format('n-d (Y)')}")
                    ->line('Name: '. $profile->name)
                    ->line('Birthday: '. $profile->birthday->format('M-jS (Y) l'))
                    ->line('Sex: '. Profile::SEXS[$profile->sex])
                    ->line('Role: '. $profile->user->getRoleNames()->implode('-'))
                    ->line("邮寄地址:  XXX{$profile->name}XXX")
                    ->line("收件人电话:  {$profile->telephone}")
                    ->action('User Info', url('/'))
                    ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
