<?php

namespace App\Providers;

use App\Models\Agency;
use App\Models\Social;
use App\Models\Profile;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\ClassRecord;
use App\Policies\AgencyPolicy;
use App\Policies\ProfilePolicy;
use App\Policies\StudentPolicy;
use App\Policies\TeacherPolicy;
use App\Policies\ClassRecordPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        ClassRecord::class => ClassRecordPolicy::class,
        Student::class     => StudentPolicy::class,
        Agency::class      => AgencyPolicy::class,
        Teacher::class     => TeacherPolicy::class,
        // Social::class      => SocialPolicy::class,
        Profile::class     => ProfilePolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
        // for list policy & actions as admin group.
        Gate::define('admin', function ($user) {
            return $user->isAdmin();
        });
        // $this->authorize('admin'); // in Http/Controllers/*.controller

        // https://github.com/spatie/laravel-permission/wiki/Global-%22Admin%22-role
        //If you want an "Admin" role to respond true to all permissions, without needing to assign all those permissions to a role, you can use Laravel's Gate::before() method. For example:
        // Implicitly grant "Admin" role all permissions
        // This works in the app by using gate-related functions like auth()->user->can() and @can()
        Gate::before(function ($user, $ability) {
            return $user->hasRole('admin') ? true : null;
        });
    }
}
