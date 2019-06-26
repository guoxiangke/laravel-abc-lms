<?php

namespace App\Http\Middleware;

use App;
use Config;
use Closure;
use Session;
use Illuminate\Support\Str;

class SetLocale
{
    /**
     *
     * Handle an incoming request.
     * @see https://glutendesign.com/posts/detect-and-change-language-on-the-fly-with-laravel
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (Session::has('locale')) {
            $locale = Session::get('locale', Config::get('app.locale'));
        } else {
            $locale = 'en';

            if (Str::startsWith($request->server('HTTP_ACCEPT_LANGUAGE'), 'zh-CN')) {
                $locale = 'zh-CN';
            };
        }
        App::setLocale($locale);

        return $next($request);
    }
}
