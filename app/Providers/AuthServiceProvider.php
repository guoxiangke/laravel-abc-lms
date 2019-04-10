<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

use App\Models\ClassRecord;
use App\Models\Student;
use App\Models\Agency;
use App\Models\Teacher;
use App\Policies\ClassRecordPolicy;
use App\Policies\StudentPolicy;
use App\Policies\AgencyPolicy;
use App\Policies\TeacherPolicy;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        ClassRecord::class => ClassRecordPolicy::class,
        Student::class => StudentPolicy::class,
        Agency::class => AgencyPolicy::class,
        Teacher::class => TeacherPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        //
        // https://github.com/spatie/laravel-permission/wiki/Global-%22Admin%22-role
        //If you want an "Admin" role to respond true to all permissions, without needing to assign all those permissions to a role, you can use Laravel's Gate::before() method. For example:
        // Implicitly grant "Admin" role all permissions
        // This works in the app by using gate-related functions like auth()->user->can() and @can()
        Gate::before(function ($user, $ability) {
            return $user->hasRole('admin') ? true : null;
        });
    }
}
