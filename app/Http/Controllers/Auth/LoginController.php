<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

use App\Models\Profile;
use Socialite;
use App\Http\Controllers\SocialController;
use Illuminate\Support\Facades\Auth;
use App\Models\Social;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }


    /**
     * Get the login username to be used by the controller.
     *
     * @return string
     */
    public function username()
    {
        return 'username';
    }

    /**
     * Attempt to log the user into the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return bool
     */
    protected function attemptLogin(Request $request)
    {
        $account = $request->get('username');
        if(is_numeric($account)){
            $field = 'id';
            $account = Profile::select('user_id')->where('telephone', $account)->first();
            $account = ($account==null)?0:$account->user_id;
        }
        elseif (filter_var($account, FILTER_VALIDATE_EMAIL)) {
            $field = 'email';
        }else{
            $field = 'name'; //禁用用户名登陆，因重名缘故
        }
        $password = $request->get('password');
        return $this->guard()->attempt([$field => $account, 'password' => $password], $request->filled('remember'));
    }

    /**
     * Validate the user login request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return void
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function validateLogin(Request $request)
    {
        $request->validate([
            $this->username() => 'required|string',
            'password' => 'required|string',
            'captcha' => 'required|captcha',
        ]);
    }

     /**
     * Redirect the user to the GitHub authentication page.
     *
     * @return \Illuminate\Http\Response
     */
    public function redirectToProvider()
    {
        if(Auth::id()) return redirect('home');
        return Socialite::driver('github')->redirect();
    }

    /**
     * Obtain the user information from GitHub.
     *
     * @return \Illuminate\Http\Response
     */
    public function handleProviderCallback()
    {
        if(Auth::id()) return redirect('home');
        $socialUser = Socialite::driver('github')->user();
        return SocialController::bind($socialUser, Social::TYPE_GITHUB);
    }


    public function redirectToFacebookProvider()
    {
        if(Auth::id()) return redirect('home');
        return Socialite::driver('facebook')->redirect();
    }
    
    public function handleFacebookProviderCallback()
    {
        if(Auth::id()) return redirect('home');
        $socialUser = Socialite::driver('facebook')->user();
        return SocialController::bind($socialUser, Social::TYPE_FACEBOOK);
    }
}
