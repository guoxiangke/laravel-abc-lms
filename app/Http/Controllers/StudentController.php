<?php

namespace App\Http\Controllers;

use App\User;
use App\Models\Agency;
use App\Models\Student;
use App\Models\Contact;
use App\Models\Profile;
use App\Models\PayMethod;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Kris\LaravelFormBuilder\FormBuilderTrait;
use Kris\LaravelFormBuilder\FormBuilder;
use App\Forms\StudentForm;
use App\Forms\Register\StudentRegisterForm;

class StudentController extends Controller
{
    
    use FormBuilderTrait;

    public function __construct(Student $Student) {
        // $this->classRecord = $classRecord;
        //中间件让具备指定权限的用户才能访问该资源
        //只有管理员可以访问所有 /classRecords
        $this->middleware(['admin'], ['only' => ['index']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $students = Student::with('user',
            'user.paymethod', 'user.profiles', 'user.profiles.contact',)->paginate(10);
        $tableHeader = ['Id','Name','Sex','Birthday','Grade','Telephone','PayType','PayNo','推荐人','代理','Email','Action'];
        return view('students.index', compact('students','tableHeader'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $form = $this->form(StudentForm::class, [
            'method' => 'POST',
            'url' => action('StudentController@store')
        ]); 
        return view('students.create', compact('form'));
    }

    public function register()
    {
        //必须是没学生角色才可以注册
        $this->authorize('create', Student::class);

        $form = $this->form(StudentRegisterForm::class, [
            'method' => 'POST',
            'url' => action('StudentController@registerStore')
        ]); 
        return view('students.register', compact('form'));
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
        $this->authorize('create', Student::class);

        $form = $formBuilder->create(StudentRegisterForm::class);

        if (!$form->isValid()) {
            return redirect()->back()->withErrors($form->getErrors())->withInput();
        }
        $user = $request->user();

        //region profile 真实姓名/电话 推荐人uid关联 更改机会
        $profileTelephone = $request->input('profile_telephone');
        $profileName = $request->input('profile_name');
        $recommendTelephone = $request->input('recommend_telephone');
        $userProfile = Profile::where('user_id', $user->id)->firstOrFail();
        $userProfileNeedSave  = false;
        if($recommendTelephone) {
            $recommendUser = Profile::where('telephone', '86' + $recommendTelephone)->first();
            if($recommendUser){
                $userProfile->recommend_uid = $recommendUser->user_id;
                $userProfileNeedSave  = true;
            }
        }
        if($profileTelephone){
            $userProfile->telephone = $profileTelephone;
            $userProfileNeedSave  = true;
        }
        if($profileName){
            $userProfile->name = $profileName;
            $userProfileNeedSave  = true;
        }
        if($userProfileNeedSave){
            $userProfile->save();
        }
        //endregion


        // dd($request->all(),$request->user()->toArray());
        
        $student = Student::firstOrCreate([
            'user_id' => $user->id,
            'grade' =>  $request->input('grade'),
            'name' => $request->input('english_name')?:$user->name, //英文名！
        ]);
        //创建关联关系？
        // $student = $user->student()->save($student);
        if($student){
            $user->assignRole(User::ROLES['student']);
            flashy()->success('成功');
            return redirect()->route('home');
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, FormBuilder $formBuilder)
    {
        $form = $formBuilder->create(StudentForm::class);

        if (!$form->isValid()) {
            return redirect()->back()->withErrors($form->getErrors())->withInput();
        }
        // create login user
        $userName = 'student_'.str_replace(' ', '', $request->input('profile_name'));
        $contactType = $request->input('contact_type');//0-4
        $email = $userName.'@'. Contact::TYPES[$contactType] . '.com';
        $user = User::where('email',$email)->first();

        if(!$user){
            if($password=$request->input('user_password')?:'Student1234'){
                $password = Hash::make($password);
            }
            $userData = [
                'name' => $userName,
                'email' => $email,
                'password' => $password,
            ];
            $user = User::create($userData);
        }

        $user->assignRole(User::ROLES['student']);

        $student = Student::firstOrNew([
            'user_id' => $user->id,
            'grade' =>  $request->input('grade'),
            'remark' =>  $request->input('remark'),
            'book_id' =>  $request->input('book_id'),
        ]);
        $student = $user->student()->save($student);

        //确保只有一个手机号
        $profile = Profile::firstOrNew([
            'telephone' => $request->input('profile_telephone'),
        ]);
        $profile = $profile->fill([
            'user_id' => $user->id,
            'name' => $request->input('profile_name'),
            'sex' => $request->input('profile_sex'),
            'birthday' =>  $request->input('profile_birthday'),
            'recommend_uid' => $request->input('recommend_uid'),
        ])->save();
        $profile = $user->profile()->save($profile);

        $contact = Contact::firstOrNew([
            'profile_id' => $profile->id,
            'type' => $request->input('contact_type'),
            'number' => $request->input('contact_number'),
            'remark' => $request->input('contact_remark'),
        ]);
        $contact = $profile->contact()->save($contact);

       
        flashy()->success('成功创建：'.$request->input('profile_name'));
        return redirect()->route('students.index');
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
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\agency  $agency
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, agency $agency)
    {
        //
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
