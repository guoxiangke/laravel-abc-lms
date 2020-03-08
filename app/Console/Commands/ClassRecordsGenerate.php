<?php

namespace App\Console\Commands;

use App\Jobs\ClassRecordsGenerateQueue;
use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class ClassRecordsGenerate extends Command
{
    /**
     * The name and signature of the console command.
     *  为订单=8的某个日期进行生成 php artisan classrecords:generate --order=8 --date=2019-04-24
     *  对前X天的生成！ php artisan classrecords:generate 3 --order=2.
     * @var string
     */
    protected $signature = 'classrecords:generate {offset?} {--order=} {--date=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate avaible orders classes records by Queue';

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
        $offset = $this->argument('offset') ?? 0;
        $orderId = $this->option('order') ?? null;

        $date = $this->option('date') ?? 0; //2019-06-24 00:00:00
        if ($date) {
            $offset = Carbon::parse($date)->diffInDays(Carbon::now());
        }

        $this->info("Generate ClassRecords for $orderId begin!");
        if ($orderId) {
            $order = Order::find($orderId);
            if ($order->isActive()) {
                ClassRecordsGenerateQueue::dispatch($order, $offset)->onQueue('high');
            } else {
                Log::error('order is not active', [__CLASS__, __FUNCTION__, __LINE__]);
            }
        } else {
            Order::active()
                ->each(function (Order $order) use ($offset) {
                    ClassRecordsGenerateQueue::dispatch($order, $offset)->onQueue('high');
                });
        }
    }
}
