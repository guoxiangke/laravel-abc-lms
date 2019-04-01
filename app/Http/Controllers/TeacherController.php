<?php

namespace App\Http\Controllers;

use App\User;
use App\Models\Teacher;
use App\Models\Contact;
use App\Models\Profile;
use App\Models\PayMethod;
use App\Models\Zoom;
use App\Forms\TeacherForm;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Kris\LaravelFormBuilder\FormBuilderTrait;
use Kris\LaravelFormBuilder\FormBuilder;

class TeacherController extends Controller
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
        $teachers = Teacher::with(
            'user','school','zoom', 
            'user.paymethod', 'user.profile', 'user.profile.contact',
            'school',
        )->paginate(10);
        // dd($teachers->toArray());
        $tableHeader = ['id', 'Name', 'Email', 'ZoomEmail', 'ZoomPassword', 'Sex','Birthday', 'telephone',  'School', 'PayType', 'PayNO', 'Action'];
        return view('teachers.index',compact('teachers','tableHeader'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $form = $this->form(TeacherForm::class, [
            'method' => 'POST',
            'url' => action('TeacherController@store')
        ]); 
        return view('teachers.create', compact('form'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, FormBuilder $formBuilder)
    {
        $form = $formBuilder->create(TeacherForm::class);

        if (!$form->isValid()) {
            return redirect()->back()->withErrors($form->getErrors())->withInput();
        }
        // create login user
        $teacherUserName = 'teacher_'.str_replace(' ', '', $request->input('profile_name'));
        $contactType = $request->input('contact_type');//0-4
        $teacherEmail = $teacherUserName.'@'. Contact::TYPES[$contactType] . '.com';
        $user = User::where('email',$teacherEmail)->first();

        if(!$user){
            if($password=$request->input('user_password')?:'Teacher123'){
                $password = Hash::make($password);
            }
            $userData = [
                'name' => $teacherUserName,
                'email' => $teacherEmail,
                'password' => $password,
            ];
            $user = User::create($userData);
        }

        $user->assignRole(User::ROLES['teacher']);

        $teacher = Teacher::firstOrNew([
            'user_id' => $user->id,
            'school_id' => $request->input('school_id'),
        ]);
        $teacher = $user->teacher()->save($teacher);


        if($zoomId = $request->input('zoom_id')){
            $teacher->zoom_id = $zoomId;
            $teacher->save();
        }else{
            $zoom = Zoom::firstOrCreate([
                'email' => $request->input('zoom_email'),
                'password' => $request->input('zoom_password'),
                'pmi' => $request->input('zoom_pmi'),
            ]);
            $zoom = $teacher->zoom()->save($zoom);
        }
        
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
            'profile_id' => $profile->id,
            'type' => $request->input('contact_type'),
            'number' => $request->input('contact_number'),
            'remark' => $request->input('contact_remark'),
        ]);
        $contact = $profile->contact()->save($contact);

        //3. 中教必有 save payment
        if($request->input('pay_number')){
            $paymethod = PayMethod::create([
                'user_id' => $user->id,
                'type' => $request->input('pay_method'),//'支付类型 0-4'// 'PayPal','AliPay','WechatPay','Bank','Skype',
                'number' => $request->input('pay_number'),
                'remark' => $request->input('pay_remark'),
            ]);
            $paymethod = $user->paymethod()->save($paymethod);
        }

        flashy()->success('成功创建：'.$request->input('profile_name'));
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
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\teacher  $teacher
     * @return \Illuminate\Http\Response
     */
    public function edit(teacher $teacher)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\teacher  $teacher
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, teacher $teacher)
    {
        //
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
