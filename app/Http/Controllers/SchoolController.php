<?php

namespace App\Http\Controllers;

use App\User;
use App\Models\School;
use App\Models\Contact;
use App\Models\Profile;
use App\Models\PayMethod;
use App\Forms\SchoolForm;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Kris\LaravelFormBuilder\FormBuilderTrait;
use Kris\LaravelFormBuilder\FormBuilder;
use Illuminate\Support\Facades\Storage;

class SchoolController extends Controller
{
    public function __construct() {
        $this->middleware(['admin']); // isAdmin 中间件让具备指定权限的用户才能访问该资源
    }

    use FormBuilderTrait;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $schools = School::with('user', 'user.paymethod', 'user.profile', 'user.profile.contact')->paginate(10);
        //'ContactRemark' , 'PayRemark'
        $tableHeader = ['Id', 'Name', 'Email', 'Sex',  'Tel', 'Contact', 'PayMent', 'Action'];
        return view('schools.index',compact('schools','tableHeader'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // FormBuilder $formBuilder
        // $form = $formBuilder->create(SchoolForm::class, [
        //     'method' => 'POST',
        //     'url' => route('schools.store', [],false),
        // ]); 
        $form = $this->form(SchoolForm::class, [
            'method' => 'POST',
            'url' => action('SchoolController@store')
        ], ['is_admin' => true]); 
        return view('schools.create', compact('form'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, FormBuilder $formBuilder)
    {
        $form = $formBuilder->create(SchoolForm::class);

        if (!$form->isValid()) {
            return redirect()->back()->withErrors($form->getErrors())->withInput();
        }
        // create login user
        try {
            $user = User::firstOrCreate([
                'name' => 'school_'.str_replace(' ', '', $request->input('profile_name')),
                'email' => $request->input('user_email'),
                'password' => Hash::make($request->input('password')),
            ]);
        } catch (Exception $e) {
            flash()->error('该学校用户已存在：'.$request->input('school_name'));
            return back();
        }
        //give role
        $user->assignRole(User::ROLES['school']);
        //todo 
        // dd(Storage::disk('onedrive')->put('/', $request->file('image'))) ;
        // $user->addMedia($pathToImage)->toMediaCollection('avatar');
        // $yourModel->addMedia($pathToFile)->toMediaCollection('big-files', 's3');

        //0.save school
        $school = School::firstOrNew([
            'name' => $request->input('school_name'),
            'user_id' => $user->id,
        ]);
        $school = $user->school()->save($school);

        //确保只有一个手机号
        $profile = Profile::firstOrNew([
            'telephone' => $request->input('profile_telephone'),
        ]);
        $profile = $profile->fill([
            'user_id' => $user->id,
            'name' => $request->input('profile_name'),
            'sex' => $request->input('profile_sex'),
            'birthday' =>  $request->input('profile_birthday'),
        ])->save();
        $profile = $user->profile()->save($profile);

        $contact = Contact::firstOrNew([
            'type' => 0, //Contact::TYPES[0] = skype
            'number' => $request->input('contact_skype'),
            'remark' => $request->input('contact_remark'),
        ]);
        $contact = $profile->contact()->save($contact);

        $paymethod = PayMethod::firstOrNew([
            'type' => $request->input('pay_method'),
            //'支付类型 0-4'// 'PayPal','AliPay','WechatPay','Bank','Skype',
            'number' => $request->input('pay_number'),
            'remark' => $request->input('pay_remark'),
        ]);
        $profile = $user->paymethod()->save($paymethod);

        flashy()->success('成功创建：'.$request->input('school_name'));
        return redirect()->route('schools.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\school  $school
     * @return \Illuminate\Http\Response
     */
    public function show(school $school)
    {
        return view('schools.show',compact('school'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\school  $school
     * @return \Illuminate\Http\Response
     */
    public function edit(school $school)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\school  $school
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, school $school)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\school  $school
     * @return \Illuminate\Http\Response
     */
    public function destroy(school $school)
    {
        //
    }
}
