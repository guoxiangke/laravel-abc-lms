@extends('layouts.app')

@section('title', 'Cut Video')

@section('content')
<div class="container">
	<h1>Cut Video</h1>

    <div class="show-links">
        <a href="{{ route('classRecords.show', $classRecord->id) }}" class="btn btn-outline-dark"><i class="fas fa-angle-left fa-large"></i> {{__('Go Back')}}</a>
    </div>
	@php
        $mp4 = $classRecord->mp4;
	@endphp

    <div class="row justify-content-center">
        <div class="col-md-6 col-sm-6"> 
            {!! form($form) !!}
            @if($mp4)
            <br>
            <div class="row">
              <div class="col-12 col-md-12 col-sm-12">
                <video width="100%" height="auto" 
                  id="video"
                  controls
                  preload="none"
                  controlsList="nodownload">
                  <source src="{{$classRecord->getMp4LinkByCdn('upyun')}}" type="video/mp4">
                  Your browser does not support the video tag.
                </video>
              </div>
            </div>
              <a href="#" data-speed="1" class="play-speed btn btn-outline-dark"><i class="fas fa-play fa-large"></i> 1X</a>
              <a href="#" data-speed="1.5" class="play-speed btn btn-outline-dark"><i class="fas fa-step-forward fa-large"></i> 1.5X</a>
              <a href="#" data-speed="2" class="play-speed btn btn-outline-dark"><i class="fas fa-forward fa-large"></i> 2X</a>
          	@endif
        </div>
        <div class="col-md-6 col-sm-6"> 
          @foreach($classRecord->videos as $video)
          <div>
                <div class="mt-3  mb-1">
                {{ Form::open(['method' => 'DELETE', 'route' => ['videos.destroy', $video->id]]) }}
                   {{$video->start_time}} - {{$video->end_time}} {{ Form::submit('Delete', ['class' => 'btn btn-sm btn-delete btn-danger']) }}
                {{ Form::close() }}
                <a target="_blank" class="btn btn-sm btn-success" href="/videos/{{ $video->hashid() }}">Share</a>
                </div>
                <video width="70%" height="auto" 
                  controls
                  preload="none">
                  <source src="{{$video->getCdnUrl()}}" type="video/mp4">
                  Your browser does not support the video tag.
                </video>
          </div>
          @endforeach
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
        $('.btn-delete').click(function(e){
          e.preventDefault();
          if (confirm('Are you sure?')) {
              $(this).parent('form').submit();
          }
        });

    }
</script>
@endsection
