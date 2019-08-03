<?php

namespace App\Http\Controllers;

use Upyun\Upyun;
use App\Models\Video;
use App\Models\ClassRecord;
use Illuminate\Http\Request;
use Upyun\Config as UpyunConfig;
use App\Forms\VideoForm as CreateForm;
use Kris\LaravelFormBuilder\FormBuilder;
use Kris\LaravelFormBuilder\FormBuilderTrait;

class VideoController extends Controller
{
    use FormBuilderTrait;

    public function __construct()
    {
        $this->middleware(['admin']); // isAdmin 中间件让具备指定权限的用户才能访问该资源
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function cut(ClassRecord  $classRecord)
    {
        $form = $this->form(
            CreateForm::class,
            [
                'method' => 'POST',
                'url'    => action('VideoController@store'),
            ],
            ['entity' => $classRecord],
        );

        // $config = new UpyunConfig(config('upyun.bucket'), config('upyun.operator'), config('upyun.password'));
        // $config->processNotifyUrl = 'https://lms.abc-chinaedu.com/upyun/cut/callback';
        // $upyun = new Upyun($config);
        // $result = $upyun->queryProcessResult(['aa3974dd09404e09a1c8e93177f56903']);
        // dd($result );
        return view('videos.create', compact('form', 'classRecord'));
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
            ]);
            alert()->toast(__('Success'), 'success', 'top-center')->autoClose(3000);

            return redirect()->route('videos.cut', $classRecord);
        } else {
            alert()->toast('出错了，告诉程序员吧：190803-96', 'error', 'top-center')->autoClose(3000);
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
        //
    }
}
