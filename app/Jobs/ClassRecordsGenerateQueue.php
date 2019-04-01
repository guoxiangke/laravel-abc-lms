<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

use App\Models\Order;
use App\Models\ClassRecord;
use Carbon\Carbon;

use Illuminate\Support\Facades\Log;

class ClassRecordsGenerateQueue implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //https://github.com/laravel/framework/blob/747577b6f03171f16c2c1e1413efdb83485a78ab/src/Illuminate/Database/Concerns/BuildsQueries.php#L51-L67 each or chunk
        Order::active()
            ->each(function (Order $order) {
                //找出今天/昨天需要上的2节课 的时间H:i
                $byDay = Carbon::now()->subDays(0);//sub方便为过去的日期生成记录！！
                $todayClassTimes = $order->hasClass($byDay); //H:i
                // 然后通过rrule的时间找到对应的rrule_id 然后创建 classRecord

                Log::info('todayClassTimes', [$todayClassTimes]);
                //2节课的情况 + 请假情况！！
                foreach ($order->schedules as $rrule) {
                    if(in_array($rrule->start_at->format('H:i'), $todayClassTimes)){
                        try {
                            $classRecord = ClassRecord::firstOrCreate([
                                'rrule_id' => $rrule->id,
                                'teacher_uid' => $order->teacher_uid,
                                'generated_at' => $byDay->format('Y-m-d ' . $rrule->start_at->format('H:i') .':00' ),
                                //必须，固定为生成当天的XX开始时间，避免重复生产
                                //@see $table->unique(['rrule_id', 'teacher_uid', 'generated_at']);
                                'user_id' => $order->user_id,//'student_uid',
                                'agency_uid' => $order->agency_uid,
                                'order_id' => $order->id,
                            ]);

                            if($classRecord->wasRecentlyCreated){
                                //todo more notication : XXX has class today!!!
                                Log::info('ClassRecordsGenerateQueue', [$order->title]);
                            }
                        } catch (\Exception $e) {
                            Log::info('ClassRecordsGenerateQueue', [$e->getMessage(), 'UniqueKeyException']);
                        }
                    }
                }
            
            });
    }
}
