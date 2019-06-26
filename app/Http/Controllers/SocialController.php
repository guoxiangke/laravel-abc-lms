<?php

namespace App\Http\Controllers;

use App\Models\Social;
use App\Models\Profile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use Kris\LaravelFormBuilder\FormBuilder;
use Kris\LaravelFormBuilder\FormBuilderTrait;

class SocialController extends Controller
{
    use FormBuilderTrait;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (Auth::user()->isAdmin()) {
            $socials = Social::paginate(50);
        } else {
            $socials = Social::where('user_id', Auth::id())->paginate(10);
        }

        return view('socials.index', compact('socials'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, FormBuilder $formBuilder)
    {
        if (Auth::id()) {
            return redirect('home');
        }

        $this->validate($request, [
            'username'=> 'required',
            'password'=> 'required',
        ]);
        $account = $request->get('username');
        if (is_numeric($account)) {
            $field = 'id';
            $account = Profile::select('user_id')->where('telephone', $account)->first();
            $account = ($account == null) ? 0 : $account->user_id;
        } elseif (filter_var($account, FILTER_VALIDATE_EMAIL)) {
            $field = 'email';
        } else {
            $field = 'name';
        }
        $password = $request->get('password');
        if (Auth::attempt([$field => $account, 'password' => $password], true)) {
            alert()->toast(__('Bind Success'), 'success', 'top-center')->autoClose(3000);
            Social::firstOrCreate(
                [
                    'social_id' => $request->input('social_id'),
                    'user_id'   => Auth::id(),
                    'type'      => $request->input('type'),
                ]
            );
        } else {
            alert()->toast(__('Wrong Credentials'), 'error', 'top-center')->autoClose(3000);

            return redirect('login');
        }

        return redirect('home');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Social  $social
     * @return \Illuminate\Http\Response
     */
    public function show(Social $social)
    {
        $this->authorize('view', $social);

        return view('socials.show', compact('social'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Social  $social
     * @return \Illuminate\Http\Response
     */
    public function edit(Social $social)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Social  $social
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Social $social)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Social  $social
     * @return \Illuminate\Http\Response
     */
    public function destroy(Social $social)
    {
        $this->authorize('delete', $social);
        $social->delete();
        alert()->toast(__('Unbind Success'), 'success', 'top-center')->autoClose(3000);
        if (Auth::user()->isAdmin()) {
            return redirect('students');
        }

        return redirect('socials');
    }
}
