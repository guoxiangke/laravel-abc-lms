@inject('markdown', 'Parsedown')
@extends('layouts.app')

@section('title', __('View ClassRecord'))

@section('content')
<div class="container">
    <h1>{{__('View ClassRecord')}}</h1>

    <?php
        $goBackLink = route('classRecords.indexbyOrder', $classRecord->order);
        if(Auth::user()->hasAnyRole(\App\Models\ClassRecord::ALLOW_LIST_ROLES)){
            $goBackLink = route('classRecords.indexByRole');
        }
        $mp4 = $classRecord->mp4;
        $mp3 = $classRecord->mp3;
    ?>
    <div class="show-links">
      <a href="{{ $goBackLink }}" class="btn btn-outline-dark"><i class="fas fa-angle-left fa-large"></i> {{__('Go Back')}}</a>

      @can('edit', $classRecord)
      <a href="{{ route('classRecords.edit', $classRecord->id) }}" class="btn btn-warning">Edit</a>
      @endcan

      @can('delete', $classRecord)
      <div class="mt-3 mb-1">
      {{ Form::open(['method' => 'DELETE', 'route' => ['classRecords.destroy', $classRecord->id]]) }}
          {{ Form::submit('Delete', ['class' => 'btn btn-danger']) }}
      {{ Form::close() }}
      </div>
      @endcan

      @if(!$mp4)
        @role('teacher')
          <a class="btn btn-warning" href="{{ route('classRecords.edit', $classRecord->id) }}#mp4">!mp4 <i class="far fa-file-video fa-large"></i></a>
        @endrole
      @endif
    </div>

    <div class="row justify-content-center">
        <div class="col-md-12 col-sm-12">
          @if($mp3)
          <div class="mp3">
              @hasanyrole('manager|admin')
              <audio style="width:100%"
                controls
                controlsList="nodownload"
                src="{{$mp3}}">
                preload="none"
                Your browser does not support the <code>audio</code> element.
              </audio>
            </div>
            @endhasanyrole

            @hasanyrole('student|teacher|agency')
            <iframe id="video_top_audio" frameborder="0" width="100%" height="72px" src="https://waveplayer.cdn.bcebos.com/nocors.html?url={{$mp3}}&tiny=0&auto=0&title={{__('Class Review')}}" allowfullscreen></iframe>
            @endhasanyrole

          @else
            @role('teacher')
              <a class="btn btn-warning text-uppercase  btn-goback" href="{{ route('classRecords.edit', $classRecord->id) }}#mp3">!mp3 <i class="far fa-file-audio fa-large"></i></a>
            @endrole
          @endif

          @if($classRecord->remark)
            <div class="remark alert alert-primary" role="alert"  style="white-space: pre-wrap;">
                {!! $markdown->line($classRecord->remark) !!}
            </div>
          @else
            @role('teacher')
              <a class="btn btn-warning text-uppercase  btn-goback" href="{{ route('classRecords.edit', $classRecord->id) }}#remark">Add Evaluation</a>
            @endrole
          @endif

          @role('teacher')
          <small class="form-text text-muted">
            **This is bold text**<br>~~This was mistaken text~~<br>More <a href="https://help.github.com/en/articles/basic-writing-and-formatting-syntax#lists" target="_blank">Markdown</a> cheatsheet.
          </small>
          @endrole

          @if($mp4)
            @hasanyrole('manager|admin|student')
              <hr>
              课堂视频:
              <a href="{{$mp4}}" download id="download" target="_blank"><i class="fas fa-video fa-1x btn " style="color:#3490DC;"></i>
              </a>（请使用电脑）
              <br>
              文件大小: {{$classRecord->getFirstMedia('mp4')->human_readable_size}}
              <br>
              下载方法: 右键点击视频图标，选择链接另存为...
            @endhasanyrole
            @hasanyrole('manager|admin')
            <br>
            <div class="row">
              <div class="col-12 col-md-6 col-sm-12">
                <video width="100%" height="auto" 
                  id="video"
                  controls
                  preload="none"
                  controlsList="nodownload">
                  <source src="{{$mp4}}" type="video/mp4">
                  Your browser does not support the video tag.
                </video>
            </div>
            </div>
              <a href="#" data-speed="1" class="play-speed btn btn-outline-dark"><i class="fas fa-play fa-large"></i> 1X</a>
              <a href="#" data-speed="1.5" class="play-speed btn btn-outline-dark"><i class="fas fa-step-forward fa-large"></i> 1.5X</a>
              
              <a href="#" data-speed="2" class="play-speed btn btn-outline-dark"><i class="fas fa-forward fa-large"></i> 2X</a>
            @endhasanyrole
            @role('teacher')
              <hr>
              Video Info:
                <a href="{{$mp4}}" download id="download" target="_blank"><i class="fas fa-video fa-1x btn " style="color:#3490DC;"></i></a>
                <p>Video Size: {{$classRecord->getFirstMedia('mp4')->human_readable_size}}</p>
            @endrole
          @endif
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    window.onload = function () {
        $('#download').click(function(e){
          e.preventDefault();
        });
        $('.play-speed').click(function(e){
          e.preventDefault();
          var vid = document.getElementById("video");
          vid.playbackRate = $(this).data('speed');
          vid.play();
        });

    }
</script>
@endsection
