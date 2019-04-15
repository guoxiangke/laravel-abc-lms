<?php

namespace App\Http\Controllers;

use App\Models\Social;
use Illuminate\Http\Request;
use Kris\LaravelFormBuilder\FormBuilderTrait;
use Kris\LaravelFormBuilder\FormBuilder;
use App\Forms\SocialForm as CreateForm;
use Socialite;
use App\Models\Profile;
use Illuminate\Support\Facades\Auth;

class SocialController extends Controller
{
    use FormBuilderTrait;

    public function redirectToWechatProvider()
    {
        if(Auth::id()) return redirect('home');
        return Socialite::driver('weixin')->redirect();
    }

    public function handleWechatProviderCallback()
    {
        if(Auth::id()) return redirect('home');

        $socialUser = Socialite::driver('weixin')->user();
        $userId = Social::where('social_id', $socialUser->id)->pluck('user_id')->first();
        //bind
        if(!$userId){
            $form = $this->form(
                CreateForm::class, 
                [
                    'method' => 'POST',
                    'url' => action('SocialController@store')
                ],
                ['socialUser' => $socialUser, 'socialType' => 1],
            ); 
            return view('social.create', compact('form'));
        }else{
            $user = Auth::loginUsingId($userId, true);
            //todo 每次登陆都更新头像？
            $user
               ->addMediaFromUrl($socialUser->avatar)
               ->usingFileName($user->id . '.avatar.png')
               ->toMediaCollection('avatar');
            return redirect('home');
        }
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
        if(Auth::id()) return redirect('home');

        $this->validate($request, [
            'username'=>'required',
            'password'=>'required',
        ]);
        $account = $request->get('username');
        if(is_numeric($account)){
            $field = 'id';
            $account = Profile::select('user_id')->where('telephone', $account)->first();
            $account = ($account==null)?0:$account->user_id;
        }
        elseif (filter_var($account, FILTER_VALIDATE_EMAIL)) {
            $field = 'email';
        }else{
            $field = 'name';
        }
        $password = $request->get('password');
        if (Auth::attempt([$field => $account, 'password' => $password],true)){
            flashy()->success(__('Bind Success'));
            Social::firstOrCreate(
                [
                    'social_id' => $request->input('social_id'),
                    'user_id' => Auth::id(),
                    'type' => $request->input('type'),
                ]
            );
        }else{
            flashy()->success(__('Wrong Credentials'));
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
        //
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
        //
    }
}
