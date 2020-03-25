<?php

namespace App\Http\Controllers;

use App\Forms\Edit\SchoolForm as EditForm;
use App\Forms\SchoolForm as CreateForm;
use App\Models\Contact;
use App\Models\PayMethod;
use App\Models\Profile;
use App\Models\School;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Kris\LaravelFormBuilder\FormBuilder;
use Kris\LaravelFormBuilder\FormBuilderTrait;

class SchoolController extends Controller
{
    public function __construct()
    {
        $this->middleware(['admin']);
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
            'user',
            'user.paymethod',
            'user.profiles',
            'user.profiles.contacts'
            )
            ->orderBy('id', 'desc')
            ->paginate(100);

        return view('schools.index', compact('schools'));
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
            'url'    => action('SchoolController@store'),
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
        $this->validate($request, [
            'telephone'=> 'required|min:11|max:14|unique:profiles',
            'email' => 'required|unique:users',
        ]);
        $form = $formBuilder->create(CreateForm::class);

        if (! $form->isValid()) {
            return redirect()->back()->withErrors($form->getErrors())->withInput();
        }
        // create login user
        $user = User::firstOrNew([
            'name'  => 'school_'.str_replace(' ', '', $request->input('profile_name')),
            'email' => $request->input('email'),
        ]);
        $user->password = Hash::make($request->input('password') ?: 'Dxjy1234');
        $user->save();
        //give role
        $user->assignRole(User::ROLES['school']);

        //0.save school
        School::firstOrNew([
            'name'    => $request->input('school_name'),
            'user_id' => $user->id,
        ])->save();
        // $school = $user->school()->save($school);

        //确保只有一个手机号
        $profile = Profile::firstOrNew([
            'telephone' => $request->input('telephone'),
        ]);
        $birthday = $request->input('profile_birthday');
        if ($birthday) {
            $birthday = Carbon::createFromFormat('Y-m-d', $birthday);
        }
        $profile->fill([
            'user_id'  => $user->id,
            'name'     => $request->input('profile_name'),
            'sex'      => $request->input('profile_sex'),
            'birthday' => $birthday,
        ])->save();

        Contact::firstOrNew([
            'profile_id' => $profile->id,
            'type'       => 0, //Contact::TYPES[0] = skype
            'number'     => $request->input('contact_skype'),
            'remark'     => $request->input('contact_remark'),
        ])->save();
        // $contact = $profile->contact()->save($contact);

        $paymethod = PayMethod::firstOrNew([
            'type' => $request->input('pay_method'),
            //'支付类型 0-4'// 'PayPal','AliPay','WechatPay','Bank','Skype',
            'number' => $request->input('pay_number'),
            'remark' => $request->input('pay_remark'),
        ]);
        $user->paymethod()->save($paymethod);

        Session::flash('alert-success', __('Success'));

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
        return view('schools.show', compact('school'));
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
                'url'    => action('SchoolController@update', ['school'=>$school]),
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
        $this->validate($request, [
            'telephone'=> 'required|min:11|max:14|unique:profiles,telephone,'.$school->user_id.',user_id',
            'email' => 'required|unique:users,email,'.$school->user_id.',id',
        ]);
        $form = $this->form(EditForm::class);
        if (! $form->isValid()) {
            return redirect()->back()->withErrors($form->getErrors())->withInput();
        }

        // create login user
        $user = $school->user;
        $paymethod = $user->paymethod;

        $profile = $user->profiles->first();
        $contact = $profile->contacts->first();
        $name = User::getRegisterName($request->input('profile_name'));
        $user->name = 's_'.$name;
        $user->email = $request->input('email');
        if ($password = $request->input('user_password')) {
            $user->password = Hash::make($password);
        }
        $user->save();

        //0.save school
        $school->fill([
            'name' => $request->input('school_name'),
        ])->save();
        // $school = $user->school()->save($school);

        //确保只有一个手机号
        $birthday = $request->input('profile_birthday');
        if ($birthday) {
            $birthday = Carbon::createFromFormat('Y-m-d', $birthday);
        }
        $profile->fill([
            'telephone' => $request->input('telephone'),
            // 'user_id' => $user->id,
            'name'     => $request->input('profile_name'),
            'sex'      => $request->input('profile_sex'),
            'birthday' => $birthday,
        ])->save();

        $contact->fill([
            // 'profile_id' => $profile->id,
            'type'   => 0, //Contact::TYPES[0] = skype
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

        Session::flash('alert-success', __('Success'));

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
