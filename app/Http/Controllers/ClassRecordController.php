<?php

namespace App\Http\Controllers;

use App\Models\ClassRecord;
use Illuminate\Http\Request;
use Kris\LaravelFormBuilder\FormBuilderTrait;
use Kris\LaravelFormBuilder\FormBuilder;
// use App\Forms\ClassRecordForm as CreateForm;
use App\Forms\Edit\ClassRecordForm as EditForm;

use App\Models\Order;
use App\Models\RRule;
use App\Models\Student;
use App\Models\Teacher;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;


class ClassRecordController extends Controller
{
    use FormBuilderTrait;

    /**
     * The user repository instance.
     */
    // protected $classRecord; todo

    public function __construct(ClassRecord $classRecord) {
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
        $classRecords = ClassRecord::with(
            'rrule',
            'teacher', 'teacher.profiles',
            'agency', 'agency.profiles',
            'user', 'user.profiles',
            'media'
            )
            ->orderBy('generated_at','desc')
            ->paginate(100);
        return view('classRecords.index', compact('classRecords'));
    }
    
    public function indexbyOrder(Order $order){
        $classRecords = ClassRecord::with(
                'rrule',
                'teacher',
                'user',
                'media'
                )
            ->where('order_id', $order->id) //user_id
            ->orderBy('generated_at','desc')
            ->paginate(50);
        return view('classRecords.index4order', compact('classRecords'));
    }
    //indexByRole 我的所有课程记录
    public function indexByRole()
    {
        $user = Auth::user();
        //谁可以拥有此列表
        //只有老师、学生、和代理可以拥有本列表
        // const ALLOW_LIST_ROLES =['agency', 'teacher', 'student'];
        if(!$user->hasAnyRole(ClassRecord::ALLOW_LIST_ROLES)) {
            abort(403);
        }
        //$this->authorize('indexByRole');

        $allowRolesMap = [
            'agency' => 'agency_uid',
            'teacher' => 'teacher_uid',
            'student' => 'user_id',
        ];

        foreach (ClassRecord::ALLOW_LIST_ROLES as $role) {
            $roleName = $role;
            if(!$user->hasRole($role)){
                continue;
            }
            $userName = $user->profiles->first()->name;
            $classRecords = ClassRecord::with(
                'rrule',
                'user',
                'user.profiles',
                'teacher',//teacher user!
                'teacherModel','teacherModel.zoom',
                'media',
                )
            ->orderBy('generated_at','desc')
            ->where($allowRolesMap[$role], $user->id);
            //只让学生看好看的！！！
            if($user->hasAnyRole(['student', 'agency'])){
                //给学生看的状态[0,1,3]
                $classRecords = $classRecords->whereIn('exception', [0,1,3]);
            }
            $classRecords = $classRecords->paginate(50);
            break;//按上下↕️顺序找到第一个角色的即可返回
        }
        return view('classRecords.index4'.$roleName, compact('classRecords', 'roleName', 'userName'));
    }


    public function indexByStudent(Student $student)
    {
        $classRecords = ClassRecord::with(
                'rrule',
                'user',
                'user.profiles',
                )
            ->where('user_id', $student->user_id)
            ->orderBy('generated_at','desc')
            ->paginate(50);
        return view('classRecords.index4agency', compact('classRecords'));
    }

    public function indexByTeacher(Teacher $teacher)
    {
        $classRecords = ClassRecord::with(
                'rrule',
                'user',
                'user.profiles',
                )
            ->where('teacher_uid', $teacher->user_id)
            ->orderBy('generated_at','desc')
            ->paginate(50);
        return view('classRecords.index4teacher', compact('classRecords'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ClassRecord  $classRecord
     * @return \Illuminate\Http\Response
     */
    public function show(ClassRecord  $classRecord)
    {
        // $classRecord->load('comments');
        $this->authorize('view', $classRecord);
        return view('classRecords.show', compact('classRecord'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\ClassRecord  $classRecord
     * @return \Illuminate\Http\Response
     */
    public function edit(ClassRecord  $classRecord)
    {

        $this->authorize('edit', $classRecord);

        $form = $this->form(
            EditForm::class,
            [
                'method' => 'PUT',
                'url' => action('ClassRecordController@update', ['id' => $classRecord->id])
            ],
            ['entity' => $classRecord],
        ); 
        return view('classRecords.edit', compact('form','classRecord'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\ClassRecord  $classRecord
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ClassRecord $classRecord, FormBuilder $formBuilder)
    {
        $form = $this->form(EditForm::class);
        if (!$form->isValid()) {
            return redirect()->back()->withErrors($form->getErrors())->withInput();
        }
        $this->authorize('edit', $classRecord);
        //mp3 mp4
        // Setting 'public' permission for files uploaded on S3
        // https://github.com/spatie/laravel-medialibrary/issues/241
        // https://github.com/spatie/laravel-medialibrary/issues/241#issuecomment-226027435
        // https://github.com/spatie/laravel-medialibrary/issues/1018
        $md5Id = $classRecord->id .'_'. time();//md5($classRecord->id . );
        if($request->file('mp3')){
            $classRecord->clearMediaCollection('mp3');
            $fileMp3Adder = $classRecord->addMediaFromRequest('mp3')
                ->usingFileName($md5Id . '.m4a')
                ->toMediaCollection('mp3'); 
        }
        if($request->file('mp4')){
            $classRecord->clearMediaCollection('mp4');
            $fileMp4Adder = $classRecord->addMediaFromRequest('mp4')
                ->usingFileName($md5Id . '.mp4')
                ->toMediaCollection('mp4');
        }
        // $newsItem->getMedia('mp3')->first()->getUrl('thumb');
        // \Log::error(__FUNCTION__,[__CLASS__, $fileMp3Adder,$fileMp4Adder]);
        
        $data = $request->all();
        $generated_at = $request->input('generated_at');
        if($generated_at) {
            $generated_at = Carbon::createFromFormat('Y-m-d\TH:i', $generated_at);//2019-04-09T06:00
            $data['generated_at'] = $generated_at;
        }
        $classRecord->fill($data)->save();
        alert()->toast(__('Success'), 'success', 'top-center')->autoClose(3000);

        if(Auth::user()->hasAnyRole(ClassRecord::ALLOW_LIST_ROLES)) {
            return redirect(route('classRecords.indexByRole'));
        }
        return redirect(route('classRecords.show', $classRecord->id));
    }

    //todo vue
    public function flagException(Request $request, ClassRecord $classRecord, $exception)
    {
        //权限判断
        switch ($exception) {
            case ClassRecord::NORMAL_EXCEPTION_TEACHER://2老师请假
            case ClassRecord::EXCEPTION_STUDENT://3学生旷课
                $this->authorize('edit', $classRecord);//编辑权限
                break;
            case ClassRecord::NORMAL_EXCEPTION_STUDENT://1学生请假
                $this->authorize('aol', $classRecord);//aol权限
                break;
            case ClassRecord::NORMAL_EXCEPTION_STUDENT://0归位正常
            case ClassRecord::NORMAL_EXCEPTION_STUDENT://4老师异常
                $this->authorize('admin', $classRecord);//管理员可操作
                break;
            
            default:
                # code...
                break;
        }
        // dd($classRecord->toArray(), $exception);
        
        

        $classRecord->exception = $exception;
        //默认=1/ture，如果有任何异常，标记为false，不作为已上课时总数计算 
        $classRecord->weight = 1;

        // 默认为 0，正常
        // 学生请假 1 需要补课，标记 weight = 0，不作为已上课时总数计算 
        // 老师请假 2 需要补课，标记 weight = 0，不作为已上课时总数计算 
        // 学生异常请假 3  计算课时 标红 🙅不需要补课
        // 老师异常 4  计算课时 标黄 | 需要补课， 标记 weight = 0，不作为已上课时总数计算 
        //1,2 4需要补课，标记 weight = 0
        if($exception==ClassRecord::NORMAL_EXCEPTION_TEACHER //2老师请假
            || $exception==ClassRecord::NORMAL_EXCEPTION_STUDENT
            || $exception==ClassRecord::EXCEPTION_TEACHER){
            $classRecord->weight = 0;
        }
        
        return ['success'=>$classRecord->save()];
    } 
}
