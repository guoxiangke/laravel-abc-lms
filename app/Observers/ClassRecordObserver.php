<?php

namespace App\Observers;

use App\Models\ClassRecord;

class ClassRecordObserver
{
    /**
     * Handle the class record "created" event.
     *
     * @param  \App\ClassRecord  $classRecord
     * @return void
     */
    public function created(ClassRecord $classRecord)
    {
        //
    }

    /**
     * Handle the class record "updated" event.
     *
     * @param  \App\ClassRecord  $classRecord
     * @return void
     */
    public function updated(ClassRecord $classRecord)
    {
    }

    /**
     * Handle the class record "deleted" event.
     *
     * @param  \App\ClassRecord  $classRecord
     * @return void
     */
    public function deleted(ClassRecord $classRecord)
    {
        //
    }

    /**
     * Handle the class record "restored" event.
     *
     * @param  \App\ClassRecord  $classRecord
     * @return void
     */
    public function restored(ClassRecord $classRecord)
    {
        //
    }

    /**
     * Handle the class record "force deleted" event.
     *
     * @param  \App\ClassRecord  $classRecord
     * @return void
     */
    public function forceDeleted(ClassRecord $classRecord)
    {
        //
    }
}
