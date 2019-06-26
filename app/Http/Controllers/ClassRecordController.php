<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Order;
use App\Models\Student;
use App\Models\Teacher;
// use App\Forms\ClassRecordForm as CreateForm;
use App\Models\ClassRecord;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Kris\LaravelFormBuilder\FormBuilder;
use Kris\LaravelFormBuilder\FormBuilderTrait;
use App\Forms\Edit\ClassRecordForm as EditForm;

class ClassRecordController extends Controller
{
    use FormBuilderTrait;

    /**
     * The user repository instance.
     */
    // protected $classRecord; todo

    public function __construct(ClassRecord $classRecord)
    {
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
            'teacher',
            'teacher.profiles',
            'agency',
            'agency.profiles',
            'user',
            'user.profiles',
            'media'
            )
            ->orderBy('generated_at', 'desc')
            ->paginate(100);
        return view('classRecords.index', compact('classRecords'));
    }
    
    public function indexbyOrder(Order $order)
    {
        $classRecords = ClassRecord::with(
            'rrule',
            'teacher',
            'user',
            'media'
                )
            ->where('order_id', $order->id) //user_id
            ->orderBy('generated_at', 'desc')
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
        if (! $user->hasAnyRole(ClassRecord::ALLOW_LIST_ROLES)) {
            abort(403);
        }
        //$this->authorize('indexByRole');

        $allowRolesMap = [
            'agency'  => 'agency_uid',
            'teacher' => 'teacher_uid',
            'student' => 'user_id',
        ];

        foreach (ClassRecord::ALLOW_LIST_ROLES as $role) {
            $roleName = $role;
            if (! $user->hasRole($role)) {
                continue;
            }
            $userName = $user->profiles->first()->name;
            $classRecords = ClassRecord::with(
                'rrule',
                'user',
                'user.profiles',
                'teacher',//teacher user!
                'teacherModel',
                'teacherModel.zoom',
                'media',
                )
            ->orderBy('generated_at', 'desc')
            ->where($allowRolesMap[$role], $user->id);
            //只让学生看好看的！！！
            if ($user->hasAnyRole(['student', 'agency'])) {
                //给学生看的状态[0,1,3]
                $classRecords = $classRecords->whereIn('exception', [0,1,3]);
            }
            $classRecords = $classRecords->paginate(50);
            break;//按上下↕️顺序找到第一个角色的即可返回
        }

        $aolCount = 0;
        //为保证您的课时有效期，您每月只有2次自助请假机会，超过请联系专属课程顾问。本次请假操作不可撤销，确定请假？
            
        if ($user->hasRole('student')) {
            $start = new Carbon('first day of this month');
            $aolCount = ClassRecord::whereIn('exception', [ClassRecord::NORMAL_EXCEPTION_STUDENT, ClassRecord::EXCEPTION_STUDENT]) // 请假和旷课都算进去
                ->where('user_id', $user->id)
                ->where('updated_at', '>=', $start)
                ->pluck('exception')
                ->count();
        }
        return view('classRecords.index4'.$roleName, compact('classRecords', 'aolCount'));
    }


    public function indexByStudent(Student $student)
    {
        $classRecords = ClassRecord::with(
            'rrule',
            'user',
            'user.profiles',
                )
            ->where('user_id', $student->user_id)
            ->orderBy('generated_at', 'desc')
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
            ->orderBy('generated_at', 'desc')
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
    public function destroy(ClassRecord  $classRecord)
    {
        $classRecord->delete();
        Session::flash('alert-success', '删除成功！');
        return redirect()->route('classRecords.indexbyOrder', $classRecord->order_id);
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
                'url'    => action('ClassRecordController@update', ['id' => $classRecord->id]),
            ],
            ['entity' => $classRecord],
        );
        return view('classRecords.edit', compact('form', 'classRecord'));
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
        if (! $form->isValid()) {
            return redirect()->back()->withErrors($form->getErrors())->withInput();
        }
        $this->authorize('edit', $classRecord);
        //mp3 mp4
        // Setting 'public' permission for files uploaded on S3
        // https://github.com/spatie/laravel-medialibrary/issues/241
        // https://github.com/spatie/laravel-medialibrary/issues/241#issuecomment-226027435
        // https://github.com/spatie/laravel-medialibrary/issues/1018
        $md5Id = $classRecord->id .'_'. time();//md5($classRecord->id . );
        if ($request->file('mp3')) {
            $classRecord->clearMediaCollection('mp3');
            $fileMp3Adder = $classRecord->addMediaFromRequest('mp3')
                ->usingFileName($md5Id . '.m4a')
                ->toMediaCollection('mp3');
        }
        if ($request->file('mp4')) {
            $classRecord->clearMediaCollection('mp4');
            $fileMp4Adder = $classRecord->addMediaFromRequest('mp4')
                ->usingFileName($md5Id . '.mp4')
                ->toMediaCollection('mp4');
        }
        // $newsItem->getMedia('mp3')->first()->getUrl('thumb');
        // \Log::error(__FUNCTION__,[__CLASS__, $fileMp3Adder,$fileMp4Adder]);
        
        $data = $request->all();
        $generated_at = $request->input('generated_at');
        if ($generated_at) {
            $generated_at = Carbon::createFromFormat('Y-m-d\TH:i', $generated_at);//2019-04-09T06:00
            $data['generated_at'] = $generated_at;
        }
        if (! $request->input('agency_uid')) {
            unset($data['agency_uid']);
        }

        $classRecord->fill($data)->save();
        alert()->toast(__('Success'), 'success', 'top-center')->autoClose(3000);

        if (Auth::user()->hasAnyRole(ClassRecord::ALLOW_LIST_ROLES)) {
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
            case ClassRecord::NO_EXCEPTION://0归位正常
            case ClassRecord::EXCEPTION_TEACHER://4老师异常
                $this->authorize('admin', $classRecord);//管理员可操作
                break;
            default:
                // return abort('403');
                return response('Unauthorized.', 401);
                break;
        }

        $classRecord->exception = $exception;
        //默认=1/ture，如果有任何异常，标记为false，不作为已上课时总数计算
        //@see setExceptionAttribute 不用操心 weight
        // $classRecord->weight = 1;
        return ['success'=>$classRecord->save()];
    }
}
