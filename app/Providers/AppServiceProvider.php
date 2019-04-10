<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Laravel\Horizon\Horizon;
use Carbon\Carbon;
// use Spatie\Flash\Flash;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Config;

use App\Models\ClassRecord;
use App\Observers\ClassRecordObserver;
use App\Models\Rrule;
use App\Observers\RruleObserver;
use App\Models\Order;
use App\Observers\OrderObserver;

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
        
        if(Config::get('app.env') === 'production') {
            URL::forceScheme('https');
        }
        
        // Flash::levels([
        //     'success' => 'alert-success',
        //     'warning' => 'alert-warning',
        //     'error' => 'alert-error',
        // ]);
        //observes
        ClassRecord::observe(ClassRecordObserver::class);
        Rrule::observe(RruleObserver::class);
        Order::observe(OrderObserver::class);
    }
}
