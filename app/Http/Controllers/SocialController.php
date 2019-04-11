<?php

namespace App\Http\Controllers;

use App\Models\Social;
use Illuminate\Http\Request;
use Kris\LaravelFormBuilder\FormBuilderTrait;
use Kris\LaravelFormBuilder\FormBuilder;
use App\Forms\SocialForm as CreateForm;
use Socialite;

class SocialController extends Controller
{
    use FormBuilderTrait;

    public function redirectToWechatProvider()
    {
        return Socialite::driver('weixin')->redirect();
    }

    public function handleWechatProviderCallback()
    {
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
        }
        Auth::loginUsingId($userId);
        return redirect('home');
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
        $form = $formBuilder->create(CreateForm::class);

        if (!$form->isValid()) {
            return redirect()->back()->withErrors($form->getErrors())->withInput();
        }

        $telephone = $request->input('telephone');
        $LoginUser = Profile::where('telephone', $telephone)->first();
        if($LoginUser){
            $userId = $LoginUser->user_id;
        }
        $email = $request->input('email');

        // Social::firstOrCreate(
        //     [
        //         'social_id' => $request->input('social_id'),
        //         'user_id' => $userId,
        //         'type' =>$request->input('type'),
        //     ]
        // );
        flashy()->success(__('Bind Success'));
        Auth::loginUsingId($userId);
        dd($request->all(),$LoginUser,$email);
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
