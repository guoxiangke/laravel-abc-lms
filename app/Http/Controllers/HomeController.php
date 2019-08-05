<?php

namespace App\Http\Controllers;

use App\Models\Social;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $isWeixinBind = Social::where('user_id', Auth::id())
            ->where('type', Social::TYPE_WECHAT)
            ->first();

        return view('home', compact('isWeixinBind'));
    }
}
