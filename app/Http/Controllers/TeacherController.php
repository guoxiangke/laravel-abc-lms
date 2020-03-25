<?php

namespace App\Http\Controllers;

use App\Forms\Edit\TeacherForm as EditForm;
use App\Forms\Register\TeacherRegisterForm;
use App\Forms\TeacherForm as CreateForm;
use App\Models\Contact;
use App\Models\PayMethod;
use App\Models\Profile;
use App\Models\Teacher;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Kris\LaravelFormBuilder\FormBuilder;
use Kris\LaravelFormBuilder\FormBuilderTrait;
use Spatie\QueryBuilder\QueryBuilder;

class TeacherController extends Controller
{
    public function __construct()
    {
        $this->middleware(['admin']);
        //todo https://abc.dev/teacher/register
        // $this->middleware(['admin'], ['only' => ['index','edit']]);
    }

    use FormBuilderTrait;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $teachers = Teacher::with(
            'school',
            'paymethod',
            'user.profiles.contacts',
            'user.profiles.recommend.profiles',
            'user.profiles.recommend.teacher',
            )
            ->orderBy('school_id')
            ->orderBy('active', 'desc')
            ->orderBy('id', 'desc');
        $teachers = QueryBuilder::for($teachers)
            ->allowedIncludes(['user.profiles'])
            ->allowedFilters(['user.name', 'user.profiles.name'])
            ->paginate(500);

        return view('teachers.index', compact('teachers'));
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
            'url'    => action('TeacherController@store'),
        ]);

        return view('teachers.create', compact('form'));
    }

    public function register()
    {
        //必须是没XX角色才可以注册
        $this->authorize('create', Teacher::class);
        $form = $this->form(TeacherRegisterForm::class, [
            'method' => 'POST',
            'url'    => action('TeacherController@registerStore'),
        ]);

        return view('teachers.register', compact('form'));
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
        $this->authorize('create', Teacher::class, );
        $form = $formBuilder->create(TeacherRegisterForm::class);

        $this->validate($request, [
            'telephone'=> 'required|min:12|max:14|unique:profiles',
            'email' => 'required|unique:users',
        ]);
        if (! $form->isValid()) {
            return redirect()->back()->withErrors($form->getErrors())->withInput();
        }
        $user = $request->user();
        //??? todo registerStore
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
            'telephone'=> 'required|min:12|max:14|unique:profiles',
            'email' => 'required|unique:users',
        ]);
        $form = $formBuilder->create(CreateForm::class);

        if (! $form->isValid()) {
            return redirect()->back()->withErrors($form->getErrors())->withInput();
        }
        // create login user
        $teacherUserName = 't_'.str_replace(' ', '', $request->input('profile_name'));
        $contactType = $request->input('contact_type') ?: 0; //0-4
        $teacherEmail = $request->input('email') ?: $teacherUserName.'@teacher.com';
        $user = User::where('email', $teacherEmail)->first();

        if (! $user) {
            if ($password = $request->input('user_password') ?: 'Teacher123') {
                $password = Hash::make($password);
            }
            $userData = [
                'name'     => $teacherUserName,
                'email'    => $teacherEmail,
                'password' => $password,
            ];
            $user = User::create($userData);
        } else {
            Session::flash('alert-danger', '邮箱重复！');

            return redirect()->back()->withErrors($form->getErrors())->withInput();
        }

        $user->assignRole(User::ROLES['teacher']);

        $teacher = Teacher::firstOrNew([
            'user_id'   => $user->id,
            'school_id' => $request->input('school_id'),
        ]);

        if ($pmi = $request->input('pmi')) {
            $teacher->pmi = $pmi;
        }
        if ($price = $request->input('price')) {
            $teacher->price = $price;
        }
        $teacher->active = $request->input('active');

        foreach (Teacher::EXTRA_ATTRIBUTES as $key) {
            $teacher->extra_attributes->{$key} = $request->input($key);
        }
        $teacher->save();

        //确保只有一个手机号
        $profile = Profile::firstOrNew([
            'telephone' => $request->input('telephone'),
        ]);
        $birthday = $request->input('profile_birthday');
        if ($birthday) {
            //1966-11-18
            $birthday = Carbon::createFromFormat('Y-m-d', $birthday);
        }
        $profile->fill([
            'user_id'  => $user->id,
            'name'     => $request->input('profile_name'),
            'sex'      => $request->input('profile_sex'),
            'birthday' => $birthday,
            'recommend_uid' => $request->input('recommend_uid') ?: null,
        ])->save();
        // $profile = $user->profiles()->save($profile);

        Contact::firstOrNew([
            'profile_id' => $profile->id,
            'type'       => $contactType,
            'number'     => $request->input('contact_number'),
            'remark'     => $request->input('contact_remark'),
        ])->save();
        // $contact = $profile->contact()->save($contact);

        //3. 中教必有 save payment
        if ($request->input('pay_number')) {
            $paymethod = PayMethod::firstOrNew([
                'user_id' => $user->id,
                'type'    => $request->input('pay_method'), //'支付类型 0-4'// 'PayPal','AliPay','WechatPay','Bank','Skype',
                'number'  => $request->input('pay_number'),
                'remark'  => $request->input('pay_remark'),
            ])->save();
            // $paymethod = $user->paymethod()->save($paymethod);
        }

        Session::flash('alert-success', __('Success'));

        return redirect()->route('teachers.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\teacher  $teacher
     * @return \Illuminate\Http\Response
     */
    public function show(teacher $teacher)
    {
        return view('teachers.show', compact('teacher'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\teacher  $teacher
     * @return \Illuminate\Http\Response
     */
    public function edit(teacher $teacher)
    {
        $form = $this->form(
            EditForm::class,
            [
                'method' => 'PUT',
                'url'    => action('TeacherController@update', ['teacher'=>$teacher]),
            ],
            ['entity' => $teacher],
        );

        return view('teachers.edit', compact('form'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\teacher  $teacher
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, teacher $teacher, FormBuilder $formBuilder)
    {
        // https://laracasts.com/discuss/channels/laravel/forcing-a-unique-rule-to-ignore-a-given-id?page=1#reply=478201
        $this->validate($request, [
            'telephone' => 'required|min:12|max:14|unique:profiles,telephone,'.$teacher->user_id.',user_id',
            'email' => 'required|unique:users,email,'.$teacher->user_id.',id',
        ]);
        $form = $this->form(EditForm::class);
        if (! $form->isValid()) {
            return redirect()->back()->withErrors($form->getErrors())->withInput();
        }

        $user = $teacher->user;
        $schoolId = $teacher->school ? $teacher->school->id : 0;
        $paymethod = $user->paymethod;

        $profile = $user->profiles->first();

        // create login user
        $teacherUserName = 't_'.str_replace(' ', '', $request->input('profile_name'));
        $contactType = $request->input('contact_type') ?: 0; //0-4

        $teacherEmail = $request->input('email') ?: $teacherUserName.'@teacher.com';

        $user->name = $teacherUserName;
        $user->email = $teacherEmail;
        if ($password = $request->input('user_password')) {
            $user->password = Hash::make($password);
        }
        $user->save();

        // $user->assignRole(User::ROLES['teacher']);
        $teacher->fill([
            'active' => $request->input('active'),
            'price'      => $request->input('price') ?: 0,
            'pmi'   => $request->input('pmi') ?: null,
            'school_id' => $request->input('school_id') ?: null,
        ]);
        foreach (Teacher::EXTRA_ATTRIBUTES as $key) {
            $teacher->extra_attributes->{$key} = $request->input($key);
        }
        $teacher->save();

        //确保只有一个手机号
        $birthday = $request->input('profile_birthday');
        if ($birthday) {
            //1966-11-18
            $birthday = Carbon::createFromFormat('Y-m-d', $birthday);
        }
        if ($profile) {
            $contact = $profile->contacts->first();
        } else {
            //bug!!!
            $profile = Profile::create([
                'telephone' => $request->input('telephone'),
                'user_id'   => $user->id,
                'name'      => $request->input('profile_name'),
                'sex'       => $request->input('profile_sex'),
                'birthday'  => $birthday,
            ]);
            $contact = Contact::create([
                'profile_id' => $profile->id,
                'type'       => $contactType,
                'number'     => $request->input('contact_number'),
                'remark'     => $request->input('contact_remark'),
            ]);
        }
        $profile->fill([
            'telephone' => $request->input('telephone'),
            // 'user_id' => $user->id,
            'name'     => $request->input('profile_name'),
            'sex'      => $request->input('profile_sex'),
            'birthday' => $birthday,
            'recommend_uid' => $request->input('recommend_uid') ?: null,
        ])->save();
        // $profile = $user->profiles()->save($profile);

        $contact->fill([
            // 'profile_id' => $profile->id,
            'type'   => $contactType,
            'number' => $request->input('contact_number'),
            'remark' => $request->input('contact_remark'),
        ])->save();
        // $contact = $profile->contact()->save($contact);

        //3. 中教必有 save payment
        if ($request->input('pay_number') || $request->input('pay_remark')) {
            if ($request->has('pay_remark')) {
               $this->validate($request, [
                   'pay_number'=> 'required',
               ]);
            }
            if ($paymethod) {
                $paymethod->fill([
                    // 'user_id' => $user->id,
                    'type'   => $request->input('pay_method'), //'支付类型 0-4'// 'PayPal','AliPay','WechatPay','Bank','Skype',
                    'number' => $request->input('pay_number'),
                    'remark' => $request->input('pay_remark'),
                ])->save();
            } else {
                $paymethod = PayMethod::firstOrNew([
                    'user_id' => $user->id,
                    'type'    => $request->input('pay_method'), //'支付类型 0-4'// 'PayPal','AliPay','WechatPay','Bank','Skype',
                    'number'  => $request->input('pay_number'),
                    'remark'  => $request->input('pay_remark'),
                ])->save();
                Session::flash('alert-success', 'New Payment has been saved!');
                // Session::flash('alert-danger-detail', 'Payment has not been saved!');
            }
        }
        Session::flash('alert-success', __('Success'));

        return redirect()->route('teachers.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\teacher  $teacher
     * @return \Illuminate\Http\Response
     */
    public function destroy(teacher $teacher)
    {
        //
    }
}
