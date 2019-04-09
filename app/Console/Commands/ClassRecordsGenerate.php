<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Jobs\ClassRecordsGenerateQueue;

class ClassRecordsGenerate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'classrecords:generate';

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
        Order::active()
            ->each(function (Order $order) {
                ClassRecordsGenerateQueue::dispatch($order)->onQueue('high');
            });
    }
}
