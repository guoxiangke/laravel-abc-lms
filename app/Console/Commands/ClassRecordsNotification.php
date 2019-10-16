<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Models\ClassRecord;
use Illuminate\Console\Command;
use App\Notifications\ClassComing;

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
                // todo $c = $classRecord->user; // ?
                $classRecord->user->socials->map(function ($social) use ($classRecord) {
                    if ($social->type == 1) {
                        $classRecord->user->notify(new ClassComing($classRecord, $social->social_id));
                    }
                });
            });
    }
}
