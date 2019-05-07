<?php

namespace App\Policies;

use App\User;
use App\Models\Social;
use Illuminate\Auth\Access\HandlesAuthorization;

class SocialPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the social.
     *
     * @param  \App\User  $user
     * @param  \App\Models\Social  $social
     * @return mixed
     */
    public function view(User $user, Social $social)
    {
        return $user->id == $social->user_id
            || $user->isAdmin();
    }

    /**
     * Determine whether the user can create socials.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the social.
     *
     * @param  \App\User  $user
     * @param  \App\Models\Social  $social
     * @return mixed
     */
    public function update(User $user, Social $social)
    {
        return $user->id === $social->user_id
            || $user->isAdmin()
            ;
    }

    /**
     * Determine whether the user can delete the social.
     *
     * @param  \App\User  $user
     * @param  \App\Models\Social  $social
     * @return mixed
     */
    public function delete(User $user, Social $social)
    {
        return $user->id === $social->user_id
            || $user->isAdmin()
            ;
    }

}
