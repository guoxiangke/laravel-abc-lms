<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Profile;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Kris\LaravelFormBuilder\FormBuilderTrait;

class LoginController extends Controller
{
    use FormBuilderTrait;
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

    public function showLoginForm()
    {
        return view('sb-admin2.login');
    }

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    // protected $redirectTo = '/home';

    public function redirectTo()
    {
        // User role
        $user = \Auth::user();

        if ($user->hasRole(['teacher', 'agency', 'student'])) {
            return '/class-records';
        }

        //None of roles, 调整到登记为学生页面
        $roles = $user->getRoleNames()->count();
        if (! $roles) {
            return '/student/register';
        }

        return '/home';
    }

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
        if (is_numeric($account)) {
            $field = 'id';
            if (! Str::startsWith($account, '+')) {
                $account = "+86{$account}";
            }
            $account = Profile::select('user_id')->where('telephone', $account)->first();
            $account = ($account == null) ? 0 : $account->user_id;
        } elseif (filter_var($account, FILTER_VALIDATE_EMAIL)) {
            $field = 'email';
        } else {
            $field = 'name'; //done no need! 禁用用户名登陆，因重名缘故
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
            'password'        => 'required|string',
            'captcha'         => 'required|captcha',
        ]);
    }
}
