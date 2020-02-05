<?php

namespace App\Policies;

use App\User;
use App\Models\Video;
use Illuminate\Auth\Access\HandlesAuthorization;

class VideoPolicy
{
    use HandlesAuthorization;
    
    /**
     * Determine whether the user can view the video.
     * 默认公开，可以转发到朋友圈
     * @param  \App\User  $user
     * @param  \App\Models\Video  $video
     * @return mixed
     */
    public function view(User $user, Video $video)
    {
        // todo 添加 deleted_at 软删除
        // why 部分视频遭到投诉后，软删除后不可公开查看
        if ($video->deleted_at) {
            return false;
        }
        // Anyone can View published Video item.
        // 默认公开，可以转发到朋友圈
        return true;
    }

    /**
     * Determine whether the user can create videos.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        // visitors cannot
        if ($user === null) {
            return false;
        }

        if ($user->can('Create a Video')) {
            return true;
        }
    }

    /**
     * Determine whether the user can update the video.
     *
     * @param  \App\User  $user
     * @param  \App\Models\Video  $video
     * @return mixed
     */
    public function update(User $user, Video $video)
    {
        // visitors cannot
        if ($user === null) {
            return false;
        }

        if ($user->can('Update any Video')) {
            return true;
        }
    }

    /**
     * Determine whether the user can delete the video.
     *
     * @param  \App\User  $user
     * @param  \App\Models\Video  $video
     * @return mixed
     */
    public function delete(User $user, Video $video)
    {
        // visitors cannot
        if ($user === null) {
            return false;
        }

        if ($user->can('Delete any Video')) {
            return true;
        }
    }

    /**
     * Determine whether the user can restore the video.
     *
     * @param  \App\User  $user
     * @param  \App\Models\Video  $video
     * @return mixed
     */
    public function restore(User $user, Video $video)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the video.
     *
     * @param  \App\User  $user
     * @param  \App\Models\Video  $video
     * @return mixed
     */
    public function forceDelete(User $user, Video $video)
    {
        //
    }
}
