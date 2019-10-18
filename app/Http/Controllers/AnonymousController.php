<?php

namespace App\Http\Controllers;

class AnonymousController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
    }

    public function recommend()
    {
        return view('anonymous.recommend');
    }
}
