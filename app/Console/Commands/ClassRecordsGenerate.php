<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Jobs\ClassRecordsGenerateQueue;
use App\Models\Order;

class ClassRecordsGenerate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'classrecords:generate {offset?} {--order=}';

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
        $offset = $this->argument('offset')??0;
        $orderId = $this->option('order')??null;
        $this->info("Generate ClassRecords for $orderId begin!");
        if($orderId){
            $order = Order::find($orderId);
            ClassRecordsGenerateQueue::dispatch($order, $offset)->onQueue('high');
        }else{
            Order::active()
                ->each(function (Order $order) use ($offset) {
                    ClassRecordsGenerateQueue::dispatch($order, $offset)->onQueue('high');
                });
        }
    }
}
