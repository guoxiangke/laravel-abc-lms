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

class StudentController extends Controller
{
    
    use FormBuilderTrait;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $students = Student::with('user',
            'user.paymethod', 'user.profile', 'user.profile.contact',)->paginate(10);
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
            // 'book_id' =>  $request->input('book_id'),//todo book_id
            'agency_uid' => $request->input('agency_id'),
            'recommender_uid' => $request->input('recommend_uid'),
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
