<?php

namespace App\Console\Commands;

use App\Models\ClassRecord;
use App\Models\Social;
use App\Notifications\ClassRecordNotifyByMessenger;
use App\Notifications\ClassRecordNotifyByWechat;
use Carbon\Carbon;
use Illuminate\Console\Command;

class ClassRecordsNotification extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'classrecords:notification';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reminder student class is coming by wechat.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $now = Carbon::now();
        if ($now->minute == 45) {
            $now->startOfHour()->addHour();
        } else { //==15
            $now->minute = 30;
            $now->second = 0;
        }
        ClassRecord::where('generated_at', $now)
            ->whereIn('user_id', config('notify.test_user'))
            ->each(function (ClassRecord $classRecord) {
                // 通知学生
                $classRecord->user->socials->map(function ($social) use ($classRecord) {
                    if ($social->type == Social::TYPE_WECHAT) {
                        $classRecord->user->notify(new ClassRecordNotifyByWechat($classRecord, $social->social_id));
                    }
                });
                // 通知老师
                $classRecord->teacher->socials->map(function ($social) use ($classRecord) {
                    if ($social->type == Social::TYPE_FB_PSID) {
                        $classRecord->notify(new ClassRecordNotifyByMessenger($classRecord));
                    }
                });
            });
    }
}
