<?php

namespace App\Http\Controllers;

use App\Forms\VideoForm as CreateForm;
use App\Models\ClassRecord;
use App\Models\Video;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Kris\LaravelFormBuilder\FormBuilder;
use Kris\LaravelFormBuilder\FormBuilderTrait;
use Upyun\Config as UpyunConfig;
use Upyun\Upyun;

class VideoController extends Controller
{
    use FormBuilderTrait;

    public function show(Video $video)
    {
        return view('videos.show', compact('video'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function cut(ClassRecord $classRecord)
    {
        //授权与 ClassRecordPolicy@cut 有关！
        $this->authorize('cut', $classRecord);
        $form = $this->form(
            CreateForm::class,
            [
                'method' => 'POST',
                'url'    => action('VideoController@store', ['class_record'=>$classRecord]),
            ],
            ['entity' => $classRecord],
        );

        return view('videos.create', compact('form', 'classRecord'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ClassRecord $classRecord, Request $request, FormBuilder $formBuilder)
    {
        $this->authorize('cut', $classRecord);
        $form = $formBuilder->create(CreateForm::class);
        $this->validate($request, [
            'start_time'=> 'required|regex:/\d{2}:\d{2}/i',
            'end_time'=> 'required|regex:/\d{2}:\d{2}/i',
        ]);
        if (! $form->isValid()) {
            return redirect()->back()->withErrors($form->getErrors())->withInput();
        }

        $avopts = '/ss/00:'.$request->input('start_time').'/es/00:'.$request->input('end_time');

        $classRecord = classRecord::find($request->input('class_record_id'));
        $source = $classRecord->getMp4Path();
        $dest = '/cut'.$classRecord->getNextCutVideoPath();

        $config = new UpyunConfig(config('upyun.bucket'), config('upyun.operator'), config('upyun.password'));
        $config->processNotifyUrl = 'https://lms.abc-chinaedu.com/upyun/cut/callback';
        $upyun = new Upyun($config);

        $result = $upyun->process([[
            'type' => 'video',  // video 表示视频任务, audio 表示音频任务
            'avopts' => $avopts, // 处理参数，`s` 表示输出的分辨率，`r` 表示视频帧率，`as` 表示是否自动调整分辨率
            'save_as' => $dest, // 新视频在又拍云存储的保存路径
        ]], Upyun::$PROCESS_TYPE_MEDIA, $source);

        if (count($result)) {
            $video = Video::create([
                'class_record_id' => $request->input('class_record_id'),
                'task_id' => $result[0],
                'start_time' => $request->input('start_time'),
                'end_time' => $request->input('end_time'),
                'path' => $dest,
                'user_id' => Auth::id(),
            ]);
            Session::flash('alert-success', __('Success'));

            return redirect()->route('videos.cut', $classRecord);
        } else {
            Session::flash('alert-danger', '出错了，告诉程序员吧：190803-96');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Video  $video
     * @return \Illuminate\Http\Response
     */
    public function destroy(Video $video)
    {
        $this->authorize('delete', $video);
        $video->delete();
        // don't forceDelete it
        // keep Upyun storage!!!
        // $config = new UpyunConfig(config('upyun.bucket'), config('upyun.operator'), config('upyun.password'));
        // $config->processNotifyUrl = 'https://lms.abc-chinaedu.com/upyun/cut/callback';
        // $upyun = new Upyun($config);
        // $res = $upyun->delete($video->path);
        // if ($res) {
        //     Session::flash('alert-success', 'Upyun文件删除成功！');
        // }
        Session::flash('alert-success', '删除成功！');

        return redirect()->back();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('viewAny', Video::class);
        $videos = Video::with(
            'user',
            'user.profiles',
            'user.roles',
            'classRecord.order.user.profiles',
            'classRecord.order.teacher.profiles',
            'classRecord.order.agency.profiles',
            )->withTrashed()
            ->orderBy('id', 'desc')
            ->paginate(100);

        return view('videos.index', compact('videos'));
    }
}
