<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Artisan;

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
        alert()->toast('正在生成，请稍后刷新此页', 'success', 'top-center')->autoClose(3000);
        // return redirect('classRecords');
        return route('classRecords.index');
    }

    // switch user for dev
    // su -i
    public function su($uid)
    {
        Auth::loginUsingId($uid);
        alert()->toast('切换登录成功！', 'success', 'top-center')->autoClose(3000);

        return redirect('home');
    }
}
