<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Notification;
use App\Notifications\BirthdayNotification;
use App\Models\Profile;
use Carbon\Carbon;

class BirthdayNotify extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'birthday:notify';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Notify Admin who will need celebrate birthday';

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
        Profile::each(function($profile) use ($now){
            if($profile->birthday && $now->diffInDays($profile->birthday->setYear($now->year))<=7){
                Notification::route('mail', 'monika@abc-chinaedu.com')
                ->notify(new BirthdayNotification($profile));
            }
        });
    }
}
