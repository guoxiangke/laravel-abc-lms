<?php

namespace App\Http\Middleware;

use Closure;

class VerifyMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $messengerVerifyToken = \Config::get('services.facebook.messenger_verify_token');
        if ($request->input('hub_mode') === 'subscribe'
            && $request->input('hub_verify_token') === $messengerVerifyToken) {
            return response($request->input('hub_challenge'), 200);
        }

        return $next($request);
    }
}
