<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class WechatUserAvatarQueue implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;


    protected $user;
    protected $avatar;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($user,$avatar)
    {
        $this->user = $user;
        $this->avatar = $avatar;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $avatar = $this->avatar;
        $user = $this->user;

        $avatarPath = '/tmp/avatar.png';
        file_put_contents($avatarPath, file_get_contents($avatar));
        //todo 每次登陆都更新头像？
        $user->addMedia($avatarPath)
           ->usingFileName($user->id . '.avatar.png')
           ->toMediaCollection('avatar');
    }
}
