<?php

namespace App\Http\Controllers;

use Socialite;
use App\Models\Social;
use App\Models\Profile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Forms\SocialForm as CreateForm;
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
        \Log::error(__FUNCTION__, [__CLASS__, __LINE__, Auth::id()]);
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
        $data = [$field => $account, 'password' => request('password')];
        \Log::error(__FUNCTION__, [__CLASS__, __LINE__, $data]);
        if (Auth::attempt($data, true)) {
            alert()->toast(__('Bind Success'), 'success', 'top-center')->autoClose(3000);
            Social::firstOrCreate(
                [
                    'social_id' => $request->input('social_id'),
                    'user_id'   => Auth::id(),
                    'type'      => $request->input('type'),
                ]
            );

            return redirect('home');
        } else {
            alert()->toast(__('Wrong Credentials'), 'error', 'top-center')->autoClose(3000);

            return redirect('login');
        }
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

    /**
     * Redirect the user to the GitHub authentication page.
     *
     * @return \Illuminate\Http\Response
     */
    public function redirectToGithubProvider()
    {
        if (Auth::id()) {
            return redirect('home');
        }

        return Socialite::driver('github')->redirect();
    }

    /**
     * Obtain the user information from GitHub.
     *
     * @return \Illuminate\Http\Response
     */
    public function handleGithubProviderCallback()
    {
        if (Auth::id()) {
            return redirect('home');
        }
        $socialUser = Socialite::driver('github')->user();

        return $this->bind($socialUser, Social::TYPE_GITHUB);
    }

    public function redirectToFacebookProvider()
    {
        if (Auth::id()) {
            return redirect('home');
        }

        return Socialite::driver('facebook')->redirect();
    }

    public function handleFacebookProviderCallback()
    {
        if (Auth::id()) {
            return redirect('home');
        }
        $socialUser = Socialite::driver('facebook')->user();

        return $this->bind($socialUser, Social::TYPE_FACEBOOK);
    }

    public function redirectToWechatProvider()
    {
        // if (Auth::id()) {
        //     return redirect('home');
        // }

        return Socialite::driver('weixin')->redirect();
    }

    public function handleWechatProviderCallback()
    {
        $userId = Auth::id();
        $socialUser = Socialite::driver('weixin')->user();
        if ($userId) {
            $this->socialUpdate($userId, Social::TYPE_WECHAT, $socialUser->avatar, $socialUser->nickname ?: $socialUser->name);
            alert()->toast(__('Bind Success'), 'success', 'top-center')->autoClose(3000);
            \Log::error(__FUNCTION__, [__CLASS__, __LINE__, $socialUser]);
            dd($userId);

        //return redirect('home');
        } else {
            dd($socialUser, __LINE__);
            //return $this->bind($socialUser, Social::TYPE_WECHAT);
        }
    }

    public function bind($socialUser, $type)
    {
        $userId = Social::where('social_id', $socialUser->id)->pluck('user_id')->first();
        \Log::error(__FUNCTION__, [__CLASS__, __LINE__, $userId]);
        //bind
        if (! $userId) {
            $form = $this->form(
                CreateForm::class,
                [
                    'method' => 'POST',
                    'url'    => action('SocialController@store'),
                ],
                ['socialUser' => $socialUser, 'socialType' => $type],
            );
            \Log::error(__FUNCTION__, [__CLASS__, __LINE__, 'socials.create']);

            return view('socials.create', compact('form'));
        }
        \Log::error(__FUNCTION__, [__CLASS__, __LINE__, $userId]);
        $user = Auth::loginUsingId($userId, true);
        $this->socialUpdate($userId, $type, $socialUser->avatar, $socialUser->nickname ?: $socialUser->name);
        \Log::error(__FUNCTION__, [__CLASS__, __LINE__, $userId]);

        return redirect('home');
    }

    public function socialUpdate($userId, $type, $avatar, $nickname)
    {
        $social = Social::where('user_id', $userId)
            ->where('type', $type)
            ->firstOrFail();
        $social->avatar = $avatar;
        $social->name = $nickname;

        return $social->save();
    }
}
