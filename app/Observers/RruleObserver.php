<?php

namespace App\Observers;

use App\Jobs\ClassRecordsGenerateQueue;
use App\Models\Rrule;

/**
 * 创建/更新 上课计划时， 自动生成今天的上课记录。
 */
class RruleObserver
{
    /**
     * Handle the rrule "created" event.
     * 创建当天的课程记录.
     * @param  \App\Models\Rrule  $rrule
     * @return void
     */
    public function created(Rrule $rrule)
    {
        // 针对上课计划 而不是请假计划
        // 创建订单时，判断今天是否有课，如果有，才生成！
        if ($rrule->type == Rrule::TYPE_SCHEDULE) {
            \Log::debug(__CLASS__, [__FUNCTION__, __LINE__, $rrule->start_at->diffInDays(now())]);
            ClassRecordsGenerateQueue::dispatch($rrule->order)->onQueue('high');
        }
    }

    /**
     * Handle the class record "updated" event.
     *
     * @param  \App\ClassRecord  $classRecord
     * @return void
     */
    public function updated(Rrule $rrule)
    {
        // if ($rrule->type == Rrule::TYPE_SCHEDULE) {
        //     //自动生成过去1个月的记录，特殊记录请编辑修改状态！！！
        //     // for ($i = 0; $i < 31; $i++) {
        //     //     ClassRecordsGenerateQueue::dispatch($rrule->order, $i)->onQueue('high');
        //     // }
        //     ClassRecordsGenerateQueue::dispatch($rrule->order)->onQueue('high');
        // }
    }
}
