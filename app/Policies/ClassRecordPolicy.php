<?php

namespace App\Policies;

use App\User;
use App\Models\ClassRecord;
use Illuminate\Auth\Access\HandlesAuthorization;

class ClassRecordPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    //谁可以查看？代理可以
    public function view(User $user, ClassRecord $classRecord)
    {
        //可以评论的人一定能查看
        return $this->comment($user, $classRecord)
            || $classRecord->agency_uid == $user->id //代理可以查看但不可以评论
;
    }

    //谁可以评论？学生可以
    public function comment(User $user, ClassRecord $classRecord)
    {
        return $classRecord->user_id == $user->id || $this->edit($user, $classRecord);
    }

    //上传mp3 mp4 //谁可以编辑 == upload
    //学生可以查看，但不能编辑
    public function edit(User $user, ClassRecord $classRecord)
    {
        return $classRecord->teacher_uid == $user->id || $user->isAdmin();
    }

    public function delete(User $user, ClassRecord $classRecord)
    {
        return $user->isAdmin();
    }

    //学生可以请假
    public function aol(User $user, ClassRecord $classRecord)
    {
        return $classRecord->user_id == $user->id || $this->edit($user, $classRecord);
    }
}
