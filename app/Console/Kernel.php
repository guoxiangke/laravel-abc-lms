<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        //$schedule->command('inspire')->everyMinute();
        // $schedule->command('horizon:snapshot')->everyFiveMinutes();
        // 每日課程紀錄生成
        //todo 提醒學生今日有課, 应该在生成的时候，采用微信模版
        $schedule->command('classrecords:generate')->daily()->at('06:30');
        // 管理人員 過期提醒
        $schedule->command('expiration:reminder')->weeklyOn(1, '7:00');
        // 提醒老師、管理员即將上課,提前5+1分鐘。
        $schedule->command('classrecords:reminder')->cron('26,56 * * * *');
        // 通知学生 提前30分钟
        $schedule->command('classrecords:notification')->everyThirtyMinutes();
        // $schedule->command('backup:clean --disable-notifications')->daily()->at('01:00');
        // $schedule->command('backup:run --disable-notifications')->daily()->at('02:00');
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
