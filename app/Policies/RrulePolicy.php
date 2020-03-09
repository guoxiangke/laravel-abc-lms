<?php

namespace App\Policies;

use App\User;
use App\Models\Rrule;
use Illuminate\Auth\Access\HandlesAuthorization;

// use Spatie\Permission\Models\Permission;
// Permission::create(['name' => 'View any Rrule']);
// Permission::create(['name' => 'Create a Rrule']);
// Permission::create(['name' => 'Update any Rrule']);
// Permission::create(['name' => 'View any ClassRecord']);

class RrulePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any rrules.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return $user->hasAnyPermission(['View any Rrule']);
    }

    /**
     * Determine whether the user can view the rrule.
     *
     * @param  \App\User  $user
     * @param  \App\Models\Rrule  $rrule
     * @return mixed
     */
    public function view(User $user, Rrule $rrule)
    {
        return $user->hasAnyPermission(['View any Rrule']);
    }

    /**
     * Determine whether the user can create rrules.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->hasAnyPermission(['Create a Rrule']);
    }

    /**
     * Determine whether the user can update the rrule.
     *
     * @param  \App\User  $user
     * @param  \App\Models\Rrule  $rrule
     * @return mixed
     */
    public function update(User $user, Rrule $rrule)
    {
        return $user->hasAnyPermission(['Update any Rrule']);
    }

    /**
     * Determine whether the user can delete the rrule.
     *
     * @param  \App\User  $user
     * @param  \App\Models\Rrule  $rrule
     * @return mixed
     */
    public function delete(User $user, Rrule $rrule)
    {
        //
    }

    /**
     * Determine whether the user can restore the rrule.
     *
     * @param  \App\User  $user
     * @param  \App\Models\Rrule  $rrule
     * @return mixed
     */
    public function restore(User $user, Rrule $rrule)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the rrule.
     *
     * @param  \App\User  $user
     * @param  \App\Models\Rrule  $rrule
     * @return mixed
     */
    public function forceDelete(User $user, Rrule $rrule)
    {
        //
    }
}
