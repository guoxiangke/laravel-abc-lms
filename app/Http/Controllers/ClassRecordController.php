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
            'rrule.order.agency', 'rrule.order.agency.profiles',
            'rrule.order.user', 'rrule.order.user.profiles',
            )
            ->orderBy('id','desc')
            ->paginate(100);
        return view('classRecords.index', compact('classRecords'));
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
                )
            ->where($allowRolesMap[$role], $user->id) //user_id
            ->paginate(50);
            break;//按上下↕️顺序找到第一个角色的即可返回
        }
        return view('classRecords.index4'.$roleName, compact('classRecords', 'roleName', 'userName'));
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
        return view('classRecords.edit', compact('form'));
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
        // \Storage::disk('upyun')->put('/', $request->file('mp3'));
        // dd($request->file('mp3'));
        $md5Id = md5($classRecord->id);
        if($request->file('mp3')){
            $fileMp3Adder = $classRecord->addMediaFromRequest('mp3')
                ->usingFileName($md5Id . '.m4a')
                ->toMediaCollection('mp3'); 
        }
        if($request->file('mp4')){
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
        $classRecord = $classRecord->fill($data)->save();
        flashy()->success('Update Success');

        if(Auth::user()->hasAnyRole(ClassRecord::ALLOW_LIST_ROLES)) {
            return redirect(route('classRecords.indexByRole'));
        }
        return redirect(route('classRecords.index'));
    }

    //todo
    public function downloadMp4(Request $request, ClassRecord $classRecord){
        $this->authorize('view', $classRecord);
        $file = $classRecord->mp4;
        return \Storage::disk('upyun')->download($file);
        // return Storage::download('file.jpg', $name, $headers);
    }
    
}
