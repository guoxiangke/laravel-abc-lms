<?php

namespace App\Http\Controllers;

use App\User;
use App\Models\School;
use App\Models\Contact;
use App\Models\Profile;
use App\Models\PayMethod;
use App\Forms\SchoolForm as CreateForm;
use App\Forms\Edit\SchoolForm as EditForm;
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
        $schools = School::with(
                'user', 'user.paymethod', 'user.profiles', 'user.profiles.contacts'
            )
            ->orderBy('id','desc')
            ->paginate(100);
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
        $form = $this->form(CreateForm::class, [
            'method' => 'POST',
            'url' => action('SchoolController@store')
            // 'url' => route('schools.store', [],false),
        ]); 
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
        $form = $formBuilder->create(CreateForm::class);

        if (!$form->isValid()) {
            return redirect()->back()->withErrors($form->getErrors())->withInput();
        }
        // create login user
        $user = User::firstOrNew([
            'name' => 'school_'.str_replace(' ', '', $request->input('profile_name')),
            'email' => $request->input('user_email')
        ]);
        $user->password = Hash::make($request->input('password')?:'Dxjy1234');
        $user->save();
        //give role
        $user->assignRole(User::ROLES['school']);
        //todo 
        // dd(Storage::disk('onedrive')->put('/', $request->file('image'))) ;
        // $user->addMedia($pathToImage)->toMediaCollection('avatar');
        // $yourModel->addMedia($pathToFile)->toMediaCollection('big-files', 's3');

        //0.save school
        School::firstOrNew([
            'name' => $request->input('school_name'),
            'user_id' => $user->id,
        ])->save();
        // $school = $user->school()->save($school);

        //确保只有一个手机号
        $profile = Profile::firstOrNew([
            'telephone' => $request->input('profile_telephone'),
        ]);
        $profile->fill([
            'user_id' => $user->id,
            'name' => $request->input('profile_name'),
            'sex' => $request->input('profile_sex'),
            'birthday' =>  $request->input('profile_birthday'),
        ])->save();

        Contact::firstOrNew([
            'profile_id' => $profile->id,
            'type' => 0, //Contact::TYPES[0] = skype
            'number' => $request->input('contact_skype'),
            'remark' => $request->input('contact_remark'),
        ])->save();
        // $contact = $profile->contact()->save($contact);

        $paymethod = PayMethod::firstOrNew([
            'type' => $request->input('pay_method'),
            //'支付类型 0-4'// 'PayPal','AliPay','WechatPay','Bank','Skype',
            'number' => $request->input('pay_number'),
            'remark' => $request->input('pay_remark'),
        ]);
        $user->paymethod()->save($paymethod);

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
        $form = $this->form(
            EditForm::class, 
            [
                'method' => 'PUT',
                'url' => action('SchoolController@update', ['id'=>$school->id])
            ],
            ['entity' => $school],
        ); 
        return view('schools.edit', compact('form'));
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
        $form = $this->form(EditForm::class);
        if (!$form->isValid()) {
            return redirect()->back()->withErrors($form->getErrors())->withInput();
        }

        // create login user
        $user = $school->user;
        $paymethod = $user->paymethod;
        
        $profile = $user->profiles->first();
        $contact = $profile->contacts->first();

        $user->fill([
            'name' => 'school_'.str_replace(' ', '', $request->input('profile_name')),
            'email' => $request->input('user_email'),
            'password' => Hash::make($request->input('password')?:'Dxjy1234')
        ])->save();
        //give role
        // $user->assignRole(User::ROLES['school']);

        //todo 
        // dd(Storage::disk('onedrive')->put('/', $request->file('image'))) ;
        // $user->addMedia($pathToImage)->toMediaCollection('avatar');
        // $yourModel->addMedia($pathToFile)->toMediaCollection('big-files', 's3');

        //0.save school
        $school->fill([
            'name' => $request->input('school_name'),
        ])->save();
        // $school = $user->school()->save($school);

        //确保只有一个手机号
        $profile->fill([
            'telephone' => $request->input('profile_telephone'),
            // 'user_id' => $user->id,
            'name' => $request->input('profile_name'),
            'sex' => $request->input('profile_sex'),
            'birthday' =>  $request->input('profile_birthday'),
        ])->save();

        $contact->fill([
            // 'profile_id' => $profile->id,
            'type' => 0, //Contact::TYPES[0] = skype
            'number' => $request->input('contact_skype'),
            'remark' => $request->input('contact_remark'),
        ])->save();
        // $contact = $profile->contact()->save($contact);

        $paymethod->fill([
            'type' => $request->input('pay_method'),
            //'支付类型 0-4'// 'PayPal','AliPay','WechatPay','Bank','Skype',
            'number' => $request->input('pay_number'),
            'remark' => $request->input('pay_remark'),
        ])->save();
        // $user->paymethod()->save($paymethod);

        flashy()->success('Update Success');
        return redirect()->route('schools.index');
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
