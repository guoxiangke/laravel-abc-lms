<?php
# doc https://laravelacademy.org/post/19514.html
namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\Profile;
use Yansongda\LaravelNotificationWechat\WechatChannel;
use Yansongda\LaravelNotificationWechat\WechatMessage;

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
        return [WechatChannel::class, 'mail', 'database'];
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
                    ->from('noreply@birthday.com', 'BirthdayNotification')
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
        $profile = $this->profile;
        return [
            'profile_id' => $profile->id,
            'name' => $profile->name,
        ];
    }

    // https://github.com/yansongda/laravel-notification-wechat
    public function toWechat($notifiable)
    {
        $data = [
            'first' => "👉点击右下角菜单[爱不止息]->[一键续订],明天可继续接收",
            'keyword1' => 'kkkk',
            'keyword2' => "或回复【续订】,明日即可继续接收推送",
            'remark' => ['remark', "#173177"],
        ];

        return WechatMessage::create()
            // ->to('oTjEws-8eAAUqgR4q_ns7pbd0zN8')
            ->template("BXQvCd7W_jE83WXR6nMNMXxoEM0Mgz0EUwqBGQ_ebKI")
            ->url('http://github.com/yansongda')
            ->data($data);
    }
}
