<?php

namespace App\Http\Controllers;

use App\User;
use App\Models\Agency;
use App\Models\Contact;
use App\Models\Profile;
use App\Models\PayMethod;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Kris\LaravelFormBuilder\FormBuilderTrait;
use Kris\LaravelFormBuilder\FormBuilder;


use App\Forms\AgencyForm as CreateForm;
use App\Forms\Edit\AgencyForm as EditForm;

use App\Forms\Register\AgencyRegisterForm;
use Carbon\Carbon;

class AgencyController extends Controller
{
    
    use FormBuilderTrait;

    public function __construct() {
        $this->middleware(['admin']); // isAdmin 中间件让具备指定权限的用户才能访问该资源
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $agencies = Agency::with('user','user.profiles','user.paymethod')
            ->orderBy('id','desc')
            ->paginate(100);
        return view('agencies.index',compact('agencies'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {

        $form = $this->form(CreateForm::class, [
            'method' => 'POST',
            'url' => action('AgencyController@store')
        ]); 
        return view('agencies.create', compact('form'));
    }

    public function register(Request $request)
    {
        //必须是没XX角色才可以注册
        $this->authorize('create', Agency::class);
        $form = $this->form(AgencyRegisterForm::class, [
            'method' => 'POST',
            'url' => action('AgencyController@registerStore')
        ]); 
        return view('agencies.register', compact('form'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function registerStore(Request $request, FormBuilder $formBuilder)
    {
        //必须是没XX角色才可以注册
        $this->authorize('create', Agency::class);
        $this->validate($request, [
            'telephone'=>'required|min:11|unique:profiles',
        ]);
        $form = $formBuilder->create(AgencyRegisterForm::class);

        if (!$form->isValid()) {
            return redirect()->back()->withErrors($form->getErrors())->withInput();
        }
        $user = $request->user();

    }
    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, FormBuilder $formBuilder)
    {
        $this->validate($request, [
            'telephone'=>'required|min:11|unique:profiles',
        ]);
        $form = $formBuilder->create(CreateForm::class);

        if (!$form->isValid()) {
            return redirect()->back()->withErrors($form->getErrors())->withInput();
        }
        // create login user
        $userName = 'agency_'.str_replace(' ', '', $request->input('profile_name'));
        $contactType = $request->input('contact_type');//0-3
        $email = $userName.'@'. Contact::TYPES[$contactType] . '.com';
        $user = User::where('email',$email)->first();

        if(!$user){
            if($password=$request->input('user_password')?:'Agency1234'){
                $password = Hash::make($password);
            }
            $userData = [
                'name' => $userName,
                'email' => $email,
                'password' => $password,
            ];
            $user = User::create($userData);
        }

        $user->assignRole(User::ROLES['agency']);

        Agency::firstOrNew([
            'user_id' => $user->id,
            'type' => $request->input('agency_type'),
            // 'discount' => $request->input('agency_discount'),
            // 'agency_uid' => $request->input('agency_id'),
        ])->save();
        // $agency = $user->agency()->save($agency);

        //确保只有一个手机号
        $profile = Profile::firstOrNew([
            'telephone' => $request->input('telephone'),
        ]);
        $birthday = $request->input('profile_birthday');
        if($birthday){
            //1966-11-18
            $birthday = Carbon::createFromFormat('Y-m-d', $birthday);
        }
        $profile->fill([
            'user_id' => $user->id,
            'name' => $request->input('profile_name'),
            'sex' => $request->input('profile_sex'),
            'birthday' =>  $birthday,
            'recommend_uid' => $request->input('agency_id'),
        ])->save();
        // $profile = $user->profile()->save($profile);

        Contact::firstOrNew([
            'profile_id' => $profile->id,
            'type' => $request->input('contact_type'),
            'number' => $request->input('contact_number'),
            'remark' => $request->input('contact_remark'),
        ])->save();
        // $contact = $profile->contact()->save($contact);

        //3. 必有 save payment
        $paymethod = PayMethod::create([
            'user_id' => $user->id,
            'type' => $request->input('pay_method'),//'支付类型 0-4'// 'PayPal','AliPay','WechatPay','Bank','Skype',
            'number' => $request->input('pay_number'),
            'remark' => $request->input('pay_remark'),
        ]);
        $paymethod = $user->paymethod()->save($paymethod);

        flashy()->success('成功创建：'.$request->input('profile_name'));
        return redirect()->route('agencies.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\agency  $agency
     * @return \Illuminate\Http\Response
     */
    public function show(agency $agency)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\agency  $agency
     * @return \Illuminate\Http\Response
     */
    public function edit(agency $agency)
    {
        $form = $this->form(
            EditForm::class, 
            [
                'method' => 'PUT',
                'url' => action('AgencyController@update', ['id'=>$agency->id])
            ],
            ['entity' => $agency],
        ); 
        return view('agencies.edit', compact('form'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\agency  $agency
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, agency $agency, FormBuilder $formBuilder)
    {
        $this->validate($request, [
            'telephone'=>'required|min:11|unique:profiles',
        ]);
        $form = $this->form(EditForm::class);
        if (!$form->isValid()) {
            return redirect()->back()->withErrors($form->getErrors())->withInput();
        }

        $user = $agency->user;
        $paymethod = $user->paymethod;
        
        $profile = $user->profiles->first();
        $contact = $profile->contacts->first();


        // create login user
        $userName = 'agency_'.str_replace(' ', '', $request->input('profile_name'));
        $contactType = $request->input('contact_type');//0-3
        $email = $userName.'@'. Contact::TYPES[$contactType] . '.com';

        if($password=$request->input('user_password')?:'Agency1234'){
            $password = Hash::make($password);
        }
        $userData = [
            'name' => $userName,
            'email' => $email,
            'password' => $password,
        ];
        $user->fill($userData)->save();
        // $user->assignRole(User::ROLES['agency']);

        $agency->fill([
            // 'user_id' => $user->id,
            'type' => $request->input('agency_type'),
            'discount' => $request->input('agency_discount'),
        ])->save();
        // $agency = $user->agency()->save($agency);

        $birthday = $request->input('profile_birthday');
        if($birthday){
            //1966-11-18
            $birthday = Carbon::createFromFormat('Y-m-d', $birthday);
        }
        $profile->fill([
            'telephone' => $request->input('telephone'),
            // 'user_id' => $user->id,
            'name' => $request->input('profile_name'),
            'sex' => $request->input('profile_sex'),
            'birthday' =>  $birthday,
            'recommend_uid' => $request->input('agency_id'),
        ])->save();

        $contact->fill([
            // 'profile_id' => $profile->id,
            'type' => $request->input('contact_type'),
            'number' => $request->input('contact_number'),
            'remark' => $request->input('contact_remark'),
        ])->save();

        //3. 必有 save payment
        $paymethod->fill([
            // 'user_id' => $user->id,
            'type' => $request->input('pay_method'),//'支付类型 0-4'// 'PayPal','AliPay','WechatPay','Bank','Skype',
            'number' => $request->input('pay_number'),
            'remark' => $request->input('pay_remark'),
        ])->save();

        flashy()->success('Update Success');
        return redirect()->route('agencies.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\agency  $agency
     * @return \Illuminate\Http\Response
     */
    public function destroy(agency $agency)
    {
        //
    }
}
