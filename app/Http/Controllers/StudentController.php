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

use App\Forms\StudentForm as CreateForm;
use App\Forms\Edit\StudentForm as EditForm;

use App\Forms\Register\StudentRegisterForm;
use Carbon\Carbon;

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
                'user.profiles', 'user.profiles.contacts',
                'user.profiles.recommend',
            )//'user.paymethod', 
            ->orderBy('id','desc')
            ->paginate(100);
        return view('students.index', compact('students'));
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

        $this->validate($request, [
            'telephone'=>'required|min:11|unique:profiles',
        ]);
        $form = $formBuilder->create(StudentRegisterForm::class);

        if (!$form->isValid()) {
            return redirect()->back()->withErrors($form->getErrors())->withInput();
        }
        $user = $request->user();

        //region profile 真实姓名/电话 推荐人uid关联 更改机会
        $profileTelephone = $request->input('telephone');
        $profileName = $request->input('profile_name');
        $recommendTelephone = $request->input('recommend_telephone');
        $userProfile = Profile::where('user_id', $user->id)->firstOrFail();
        $userProfileNeedSave  = false;
        if($recommendTelephone) {
            $recommendUser = Profile::where('telephone', $recommendTelephone)->first();
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
        $form = $formBuilder->create(CreateForm::class);

        $this->validate($request, [
            'telephone'=>'required|min:11|unique:profiles',
        ]);
        if (!$form->isValid()) {
            return redirect()->back()->withErrors($form->getErrors())->withInput();
        }
        // create login user
        $profileName = $request->input('profile_name');
        $name = $request->input('name'); //英文名！
        if(!$name) {
            $name = $profileName;
        }
        $name = 's_'.  User::pinyin($name);
        $email = $name.'@student.com';
        $user = User::where('email', $email)->first();

        if(!$user){
            $userData = [
                'name' => $name,
                'email' => $email,
                'password' => Hash::make($request->input('password')?:'dxjy1234')
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
            'telephone' => $request->input('telephone'),
        ]);
        $birthday = $request->input('profile_birthday');
        if($birthday){
            $birthday = Carbon::createFromFormat('Y-m-d', $birthday);
        }
        $profile->fill([
            'user_id' => $user->id,
            'name' => $request->input('profile_name'),
            'sex' => $request->input('profile_sex'),
            'birthday' => $birthday,
            'recommend_uid' => $request->input('recommend_uid')?:null,
        ])->save();

        Contact::firstOrNew([
            'profile_id' => $profile->id,
            'type' => 1,// Contact::TYPES[1] = 'wechat/qq',
            'number' => $request->input('contact_number')?: $request->input('telephone'),
            'remark' => $request->input('contact_remark'),
        ])->save();
        // $contact = $profile->contact()->save($contact);

       
        flashy()->success('成功创建：'.$request->input('profile_name'));
        return redirect()->route('students.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Student  $student
     * @return \Illuminate\Http\Response
     */
    public function show(agency $agency)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Student  $student
     * @return \Illuminate\Http\Response
     */
    public function edit(Student $student)
    {
        $form = $this->form(
            EditForm::class, 
            [
                'method' => 'PUT',
                'url' => action('StudentController@update', ['id'=>$student->id])
            ],
            ['entity' => $student],
        ); 
        return view('students.edit', compact('form'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Student  $student
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Student $student, FormBuilder $formBuilder)
    {
        $form = $this->form(EditForm::class);
        
        if (!$form->isValid()) {
            return redirect()->back()->withErrors($form->getErrors())->withInput();
        }

        $user = $student->user;
        // dd($zoomId);
        $paymethod = $user->paymethod;
        
        $profile = $user->profiles->first();
        // $profile = $teacher->profiles->first();
        $contact = $profile->contacts->first();


        // create login user

        $profileName = $request->input('profile_name');
        $name = $request->input('name'); //英文名！
        if(!$name) {
            $name = 's_'. User::pinyin($profileName);
        }
        // $email = $name . '@student.com';
        $password = $request->input('password')?:'dxjy1234';
        $password = Hash::make($password);
        $user->fill(compact('name', 'password'))->save();
        // $user->assignRole(User::ROLES['student']);

        $student->fill([
            // 'user_id' => $user->id,
            'grade' =>  $request->input('grade'),
            'remark' =>  $request->input('remark'),
            'book_id' =>  $request->input('book_id'),
        ])->save();

        //确保只有一个手机号
        $birthday = $request->input('profile_birthday');
        if($birthday){
            $birthday = Carbon::createFromFormat('Y-m-d', $birthday);
        }
        $profile->fill([
            'telephone' => $request->input('telephone'),
            // 'user_id' => $user->id,
            'name' => $request->input('profile_name'),
            'sex' => $request->input('profile_sex'),
            'birthday' =>  $birthday,
            'recommend_uid' => $request->input('recommend_uid')?:null,
        ])->save();

        $contact->fill([
            // 'profile_id' => $profile->id,
            // 'type' => 1,// Contact::TYPES[1] = 'wechat/qq',
            'number' => $request->input('contact_number')?: $request->input('telephone'),
            'remark' => $request->input('contact_remark'),
        ])->save();
        // $contact = $profile->contact()->save($contact);

        flashy()->success('Update Success');
        return redirect()->route('students.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Student  $student
     * @return \Illuminate\Http\Response
     */
    public function destroy(Student $student)
    {
        //
    }
}
