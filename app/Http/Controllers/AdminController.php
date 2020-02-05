<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Session;

class AdminController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('admin');
    }

    public function genClass()
    {
        Artisan::call('classrecords:generate');
        Session::flash('alert-success', '正在生成，请稍后刷新此页！');

        return redirect(route('classRecords.index'));
    }

    // switch user for dev
    // su -i
    public function su(User $user)
    {
        Auth::loginUsingId($user->id);

        Session::flash('alert-success', '切换登录成功！');
        if ($user->hasRole(['teacher', 'agency', 'student'])) {
            return redirect(route('classRecords.indexByRole'));
        }

        return redirect('home');
    }
    public function test(){
        //dd('test');
    }
}
