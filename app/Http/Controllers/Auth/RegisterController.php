<?php

namespace App\Http\Controllers\Auth;

use Pinyin;
use App\User;
use App\Models\Profile;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
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
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name'          => ['required', 'string', 'max:25'], //, 'unique:users'
            'sex'           => ['required', 'boolean'],
            'birthday'      => ['required', 'string', 'max:25'],
            'telephone'     => ['required', 'digits_between:9,13', 'unique:profiles'],
            'email'         => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password'      => ['required', 'string', 'min:6', 'confirmed'],
            'recommend_uid' => ['required', 'integer'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        $name = User::pinyin($data['name']);
        if (! $name) {
            $name = str_replace(' ', '_', $data['name']);
        }
        $name = 'u_'.$name;

        $user = User::create([
            'name'     => $name, //处理后的用户名
            'email'    => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        if ($user) {
            try {
                $userProfile = new Profile;
                $userProfile->user_id = $user->id;
                $userProfile->name = $data['name']; //原始姓名
                $userProfile->sex = $data['sex'];
                $userProfile->birthday = $data['birthday'];
                $telephone = $data['telephone'];
                if (Str::startsWith($telephone, '86')) {
                    $telephone = substr($telephone, 2);
                } else {
                    $telephone = '+'.$telephone; //+63XXXX
                }
                $userProfile->telephone = $telephone;
                //如果没有推荐人，都指向用户1，前端默认值为1
                $userProfile->recommend_uid = $data['recommend_uid'];
                $userProfile->save();
                Session::flash('alert-success', '你已经成功申请价值298元的外教体验课！客服将稍后与您联系，请注意微信或来电！');
                alert()->toast(__('Register Success'), 'success', 'top-center')->autoClose(3000);
                Log::info(__CLASS__, [__FUNCTION__, __LINE__, 'Create an Profile for '.$user->name]);
            } catch (\Exception $e) {
                Log::error(__CLASS__, [__FUNCTION__, __LINE__, $e->getMessage()]);
            }
        }

        return $user;
    }

    /**
     * Show the application registration form.
     * 推荐用户注册表单.
     * @return \Illuminate\Http\Response
     */
    public function showRegistrationFormByRecommend(User $user)
    {
        return view('auth.register', ['uid' => $user->id]);
    }
}
