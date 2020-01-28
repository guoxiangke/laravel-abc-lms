<?php

namespace App\Jobs;

use Carbon\Carbon;
use App\Models\Order;
use App\Models\Rrule;
use App\Models\ClassRecord;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

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
    public function __construct(Order $order, $offset = 0)
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
        $byDay = Carbon::now()->subDays($this->offset); //sub方便为过去的日期生成记录！！
        Log::info(__CLASS__, [$order->title, $order->id, $byDay, 'info']);
        $todayClassTimes = $order->hasClass($byDay); //H:i
        // 然后通过rrule的时间找到对应的rrule_id 然后创建 classRecord
        //标记完成如果 已经上课=订单PERIOD
        $count = $order->classDoneRecords()->count();
        if ($order->period == $count) { // || $order->expired_at<=Carbon::now()
            Log::info(__CLASS__, [$order->title, $order->id, 'Order::STATU_COMPLETED']);
            $order->status = Order::STATU_COMPLETED;
            $order->save();

            bark_notify('订单完成', "{$order->title}");
            ftqq_notify("完成提醒{$order->title}", '', 'manager');

            return;
        }

        //标记过期的订单！
        if ($order->expired_at <= Carbon::now()) {
            Log::info(__CLASS__, [$order->title, $order->id, 'Order::STATU_OVERDUE']);
            $order->status = Order::STATU_OVERDUE;
            $order->save();

            bark_notify('订单过期', "{$order->title}");
            ftqq_notify("过期提醒{$order->title}", '', 'manager');

            return;
        }

        if (! $todayClassTimes) {
            Log::info(__CLASS__, [$order->title, $order->id, 'NoClassTodayOr']);

            return;
        }
        //2节课的情况 + 请假情况！！-下面情况
        //引入$uniqueTime变量 处理以下问题：
        //如果一个学生三个上课时间，1和3是同一个时间点20:00，没有合并rrule的同类项情况
        $uniqueTime = [];
        foreach ($order->schedules as $rrule) {
            // rrule规则暂停时，不生成记录 （rrule暂停功能）
            if ($rrule->status == Rrule::STATU_ACTIVE) {
                //去重：如果有两个重复项，忽略了第一个rrule
                $uniqueTime[$rrule->start_at->format('H:i')] = $rrule;
            }
        }
        // $uniqueTime = array_unique($uniqueTime);
        foreach ($uniqueTime as $time => $rrule) {
            if (in_array($time, $todayClassTimes)) {
                try {
                    $classRecord = ClassRecord::firstOrCreate([
                        'rrule_id'     => $rrule->id,
                        'teacher_uid'  => $order->teacher_uid,
                        'generated_at' => $byDay->format('Y-m-d '.$rrule->start_at->format('H:i').':00'),
                        //必须，固定为生成当天的XX开始时间，避免重复生产
                        //@see $table->unique(['rrule_id', 'teacher_uid', 'generated_at']);
                        'user_id'    => $order->user_id, //'student_uid',
                        'agency_uid' => $order->agency_uid,
                        'order_id'   => $order->id,
                    ]);

                    // $title = $classRecord->wasRecentlyCreated ? 'wasRecentlyCreated' : 'NoRecentlyCreated';
                    // Log::debug($title, [__CLASS__, __FUNCTION__, __LINE__, $order->title, $order->id, $classRecord->id]);
                } catch (\Exception $e) {
                    Log::error($e->getMessage(), [__CLASS__, __FUNCTION__, __LINE__, $order->title]);
                }
            }
        }
    }
}
