<?php

namespace App\Providers;

use Carbon\Carbon;
use App\Models\Rrule;
// use Spatie\Flash\Flash;
use App\Observers\RruleObserver;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Config;
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

        if (Config::get('app.env') === 'production') {
            URL::forceScheme('https');
        }

        // Flash::levels([
        //     'success' => 'alert-success',
        //     'warning' => 'alert-warning',
        //     'error' => 'alert-error',
        // ]);
        //observes
        Rrule::observe(RruleObserver::class);
    }
}
