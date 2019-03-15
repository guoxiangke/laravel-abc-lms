<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Laravel\Horizon\Horizon;
use Carbon\Carbon;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Config;
class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Carbon::setLocale('zh');
        
        if(Config::get('app.env') != 'development') {
            URL::forceScheme('https');
        }

        Horizon::auth(function ($request) {
            $user = $request->user();
            if($user && $user->isSuperuser()){
                return true;
            }else{
                return false;
            }
        });
    }
}
