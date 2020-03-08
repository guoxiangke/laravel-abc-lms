<?php

namespace App\Providers;

use App\Models\Profile;
use App\Models\Rrule;
use App\Models\Student;
use App\Observers\ProfileObserver;
use App\Observers\RruleObserver;
use App\Observers\StudentObserver;
use Carbon\Carbon;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        // if ($this->app->environment() !== 'production') {
        //     $this->app->register(\Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider::class);
        // }
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // Carbon::setLocale('zh');
        if ($this->app->environment() !== 'local') {
            URL::forceScheme('https');
        }

        Blade::if('env', function ($environment) {
            return app()->environment($environment);
        });

        //observes
        Rrule::observe(RruleObserver::class);
        Student::observe(StudentObserver::class);
        Profile::observe(ProfileObserver::class);
    }
}
