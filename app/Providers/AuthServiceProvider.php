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
    }
}
