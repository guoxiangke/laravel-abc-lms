@inject('markdown', 'Parsedown')
@extends('layouts.app')

@section('title', __('View ClassRecord'))

@section('content')
<div class="container">
    <h1>{{__('View ClassRecord')}}</h1>

    <?php
        $goBackLink = route('classRecords.index');
        if(Auth::user()->hasAnyRole(\App\Models\ClassRecord::ALLOW_LIST_ROLES)){
            $goBackLink = route('classRecords.indexByRole');
        }
    ?>
    <div class="show-links">
      <a href="{{ $goBackLink }}" class="btn btn-outline-dark"><i class="fas fa-angle-left fa-large"></i> Go Back</a>

      @can('edit', $classRecord)
      <a href="{{ route('classRecords.edit', $classRecord->id) }}" class="btn btn-warning">Edit</a>
      @endcan

      @if($mp4 = $classRecord->mp4)
        @if(!Auth::user()->hasRole('agency'))
          <a href="{{ route('classRecords.download', $classRecord) }}"><i class="fas fa-video fa-1x btn " style="color:#3490DC;"></i></a>
        @endif
      @else
        @if(Auth::user()->hasRole('teacher'))
          <a class="btn btn-warning" href="{{ route('classRecords.edit', $classRecord->id) }}#mp4">!mp4 <i class="far fa-file-video fa-large"></i></a>
        @endif
      @endif
    </div>

    <div class="row justify-content-center">
        <div class="col-md-12 col-sm-12">
          @if($classRecord->mp3)
          <div class="mp3">
            <audio style="width:100%"
              controls
              controlsList="nodownload"
              src="{{$classRecord->mp3Url}}">
              preload="none"
              Your browser does not support the <code>audio</code> element.
            </audio>
          </div>
          @else
            @if(Auth::user()->hasRole('teacher'))
              <a class="btn btn-warning text-uppercase  btn-goback" href="{{ route('classRecords.edit', $classRecord->id) }}#mp3">!mp3 <i class="far fa-file-audio fa-large"></i></a>
            @endif
          @endif

          @if($classRecord->remark)
            <div class="remark alert alert-primary" role="alert"  style="white-space: pre-wrap;">
                {!! $markdown->line($classRecord->remark) !!}
            </div>
          @else
            @if(Auth::user()->hasRole('teacher'))
              <a class="btn btn-warning text-uppercase  btn-goback" href="{{ route('classRecords.edit', $classRecord->id) }}#remark">Add Evaluation</a>
            @endif
          @endif

          @if(Auth::user()->hasRole('teacher'))
          <small class="form-text text-muted">
            **This is bold text**<br>~~This was mistaken text~~<br>More <a href="https://help.github.com/en/articles/basic-writing-and-formatting-syntax#lists" target="_blank">Markdown</a> cheatsheet.
          </small>
          @endif

          @can('comment', $classRecord)
              @comments(['model' => $classRecord])
              @endcomments
          @endcan 
        </div>
    </div>
</div>
@endsection
