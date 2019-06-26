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

    protected $order;
    protected $offset;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Order $order, $offset=0)
    {
        $this->order = $order;
        $this->offset = $offset;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $order = $this->order;
        
        //找出今天/昨天需要上的2节课 的时间H:i
        $byDay = Carbon::now()->subDays($this->offset);//sub方便为过去的日期生成记录！！
        Log::info(__CLASS__, [$order->title, $order->id, $byDay,'info']);
        $todayClassTimes = $order->hasClass($byDay); //H:i
        // 然后通过rrule的时间找到对应的rrule_id 然后创建 classRecord
        //标记完成如果 已经上课=订单PERIOD
        $count = $order->classDoneRecords()->count();
        if ($order->period == $count) { // || $order->expired_at<=Carbon::now()
            Log::info(__CLASS__, [$order->title, $order->id, 'Order::STATU_COMPLETED']);
            $order->status = Order::STATU_COMPLETED;
            $order->save();
            return;
        }
        
        //标记过期的订单！
        if ($order->expired_at<=Carbon::now()) {
            Log::info(__CLASS__, [$order->title, $order->id, 'Order::STATU_OVERDUE']);
            $order->status = Order::STATU_OVERDUE;
            $order->save();
            return;
        }

        //7天内每周一通知管理员
        $left = $order->period - $count;
        if ($left <= 7 && date('N') == 1) {
            bark_notify('到期提醒', "还剩{$left}天！{$order->title}");
            ftqq_notify("{$left}天到期提醒", "###{$order->title}### {$left}天到期", 'manager');
        }
        if (! $todayClassTimes) {
            Log::info(__CLASS__, [$order->title, $order->id, 'NoClassTodayOr']);
            return;
        }
        //2节课的情况 + 请假情况！！
        foreach ($order->schedules as $rrule) {
            if (in_array($rrule->start_at->format('H:i'), $todayClassTimes)) {
                try {
                    $classRecord = ClassRecord::firstOrCreate([
                        'rrule_id' => $rrule->id,
                        'teacher_uid' => $order->teacher_uid,
                        'generated_at' => $byDay->format('Y-m-d ' . $rrule->start_at->format('H:i') .':00'),
                        //必须，固定为生成当天的XX开始时间，避免重复生产
                        //@see $table->unique(['rrule_id', 'teacher_uid', 'generated_at']);
                        'user_id' => $order->user_id,//'student_uid',
                        'agency_uid' => $order->agency_uid,
                        'order_id' => $order->id,
                    ]);

                    //todo more notication : XXX has class today!!!
                    Log::info(__CLASS__, [$order->title, $order->id, $classRecord->wasRecentlyCreated?'wasRecentlyCreated':'NoRecentlyCreated', $classRecord->id]);
                } catch (\Exception $e) {
                    Log::error(__CLASS__, [$order->title, $e->getMessage()]);
                }
            }
        }
    }
}
