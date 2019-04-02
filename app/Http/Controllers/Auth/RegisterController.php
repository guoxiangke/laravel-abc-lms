<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;

use App\Models\Profile;

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
            'name' => ['required', 'string', 'max:25', 'unique:users'],
            'profile_name' => ['required', 'string', 'max:25'],
            'sex' => ['required', 'boolean'],
            'birthday' => ['required', 'string', 'max:25'],
            'telephone' => ['required', 'digits_between:9,13', 'unique:profiles'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
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
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        if($user){
            Log::info(__CLASS__,[__FUNCTION__,__LINE__,'Create a User Named: ' . $user->name]);
            try {
                $userProfile = new Profile;
                $userProfile->user_id = $user->id;
                $userProfile->name = $data['profile_name'];
                $userProfile->sex = $data['sex'];
                $userProfile->birthday = $data['birthday'];
                $userProfile->telephone = $data['telephone'];
                $userProfile->save();
                Log::info(__CLASS__,[__FUNCTION__,__LINE__,'Create an Profile for ' . $user->name]);
            } catch (\Exception $e) {
                Log::error(__CLASS__,[__FUNCTION__,__LINE__,$e->getMessage()]);
            }
        }
        return $user;
    }
}
