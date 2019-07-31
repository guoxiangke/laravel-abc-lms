<?php

namespace App\Observers;

use App\Models\Profile;

// https://learnku.com/articles/6657/model-events-and-observer-in-laravel
// 当模型已存在，不是新建的时候，依次触发的顺序是:
// saving -> updating -> updated -> saved
// 当模型不存在，需要新增的时候，依次触发的顺序则是
// saving -> creating -> created -> saved

// 那么 saving,saved 和 updating,updated 到底有什么区别呢？
// 上面已经讲过，Laravel 的 Eloquent 会维护实例的两个数组，分别是 original 和 attributes。
// 只有在 saved 事件触发之后，Laravel 才会对两个数组执行 syncOriginal 操作，这样就很好理解了。
// updating 和 updated 会在数据库中的真值修改前后触发。
// saving 和 saved 则会在 Eloquent 实例的 original 数组真值更改前后触发。
// 这样我们就可以根据业务场景来选择更合适的触发事件了～

class ProfileObserver
{
    /**
     * Handle the profile "created" event.
     *
     * @param  \App\Models\Profile  $profile
     * @return void
     */
    public function created(Profile $profile)
    {
        $title = '新用户注册';
        $recommend = $profile->recommend_uid ? $profile->recommend->name : '-';
        $detail = "用户名：$profile->name\n介绍人：$recommend\n";
        bark_notify($title, $detail);
        ftqq_notify($title.$detail, 'None', 'manager');
    }

    /**
     * Handle the profile "updated" event.
     *
     * @param  \App\Models\Profile  $profile
     * @return void
     */
    public function updated(Profile $profile)
    {
        //
    }

    /**
     * Handle the profile "deleted" event.
     *
     * @param  \App\Models\Profile  $profile
     * @return void
     */
    public function deleted(Profile $profile)
    {
        //
    }

    /**
     * Handle the profile "restored" event.
     *
     * @param  \App\Models\Profile  $profile
     * @return void
     */
    public function restored(Profile $profile)
    {
        //
    }

    /**
     * Handle the profile "force deleted" event.
     *
     * @param  \App\Models\Profile  $profile
     * @return void
     */
    public function forceDeleted(Profile $profile)
    {
        //
    }
}
