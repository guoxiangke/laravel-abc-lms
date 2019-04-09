<?php

namespace App\Observers;

use App\Models\Rrule;
use App\Jobs\ClassRecordsGenerateQueue;

class RruleObserver
{
    /**
     * Handle the rrule "created" event.
     * 创建当天的课程记录
     * @param  \App\Models\Rrule  $rrule
     * @return void
     */
    public function created(Rrule $rrule)
    {
        // 针对上课计划 而不是请假计划
        if($rrule->type == Rrule::TYPE_SCHEDULE){
            ClassRecordsGenerateQueue::dispatch($rrule->order)->onQueue('high');
        }
        
    }
}
