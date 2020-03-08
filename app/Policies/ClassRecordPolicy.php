<?php

namespace App\Policies;

use App\Models\ClassRecord;
use App\User;
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
            || $classRecord->agency_uid == $user->id  // 代理可以查看但不可以评论
            || $this->cut($user, $classRecord); // 编辑可以查看
    }

    //谁可以打星星？
    public function rate(User $user, ClassRecord $classRecord)
    {
        return $classRecord->user_id == $user->id || $user->isAdmin();
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
        return $classRecord->teacher_uid == $user->id // 老师可以编辑/上传
        || $user->can('Update any ClassRecord')
        || $user->isAdmin();
    }

    public function delete(User $user, ClassRecord $classRecord)
    {
        return $user->isAdmin();
    }

    // 编辑可以查看任意课程记录 并编辑
    public function cut(User $user, ClassRecord $classRecord)
    {
        return $user->can('Create a Video');
        // return $user->hasRole(User::ROLES['editor']) || $user->isAdmin();
    }

    public function aol(User $user, ClassRecord $classRecord)
    {
        return $classRecord->user_id == $user->id  // 学生可以请假
        || $this->edit($user, $classRecord) // 可以编辑的都可以aol
        || $this->status($user, $classRecord);
    }

    // 使状态归位 正常
    // 为老师点击异常
    public function reset(User $user, ClassRecord $classRecord)
    {
        return $user->can('Update any ClassRecord')
        || $this->status($user, $classRecord);
    }

    // Update any ClassRecord Status
    public function status(User $user, ClassRecord $classRecord)
    {
        return $user->can('Update any ClassRecord Status');
    }
}
