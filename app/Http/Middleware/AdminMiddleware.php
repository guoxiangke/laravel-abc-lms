<?php

namespace App\Http\Middleware;

use Closure;
use App\User;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
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
        // $request->user()->hasRole('admin')
        if ($request->user() && (
            $request->user()->isSuperuser()
            || $request->user()->hasAnyRole(User::MANAGER_ROLES)
        )) {
            return $next($request);
        }
        // if (!Auth::user()->hasPermissionTo('Administer roles & permissions')) // 用户是否具备此权限
        return abort('403') ;//|| redirect('/');
    }
}
