@extends('sb-admin2.app')

@section('title', '外教课堂精彩回放')

@section('content')
<div class="container">
	<h1>{{$video->created_at->format('n月d日')}}：外教与学员课堂互动剪影</h1>

    <div class="row justify-content-center">
        <div class="col-md-6 col-sm-6">
          <div>
                <video width="100%" height="auto" 
                  controls
                  preload="none">
                  <source src="{{$video->getCdnUrl()}}" type="video/mp4">
                  Your browser does not support the video tag.
                </video>
          </div>
          @guest
          <a class="btn-block btn btn-lg btn-success" href="/register/{{$video->classRecord->user_id}}?vcrid={{$video->class_record_id}}"><i class="fab fa-weixin fa-large"></i>
              立即注册，预约试听
          </a>
          @endguest
          @auth
          <a class="btn-block btn btn-lg btn-success" href="/register/{{auth()->id()}}?vcrid={{$video->class_record_id}}"><i class="fab fa-weixin fa-large"></i>
              分享朋友圈，赢推荐好礼！复制推荐链接或二维码
          </a>
          @endauth
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
