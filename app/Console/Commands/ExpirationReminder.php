<?php

namespace App\Console\Commands;

use App\Models\Order;
use Illuminate\Console\Command;

class ExpirationReminder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'expiration:reminder';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Weekly expiration reminder';

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
        Order::active()
            ->each(function (Order $order) {
                $count = $order->classDoneRecords()->count();

                $left = $order->period - $count;
                if ($left <= 7) {
                    bark_notify('到期提醒', "还剩{$left}次课！{$order->title}");
                    ftqq_notify("{$left}天到期提醒{$order->title}", '', 'manager');
                }
            });
    }
}
