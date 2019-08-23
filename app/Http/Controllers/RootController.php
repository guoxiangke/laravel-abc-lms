<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class RootController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('root');
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
