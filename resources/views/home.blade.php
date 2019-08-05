@extends('layouts.app')

@section('title', __('Home'))

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">{{__('Dashboard')}}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    @hasrole('teacher')
                    
                    @else
                        @if(!$isWeixinBind)
                        <a class="text-center btn-sm btn btn-success text-white" target="_blank" href="{{ route('login.weixin') }}">微信绑定</a>
                        @endif
                    @endhasrole
                    
                    @hasanyrole('manager|admin')
                    <a class="btn btn-sm btn-danger btn-delete" target="_blank" href="http://123.206.80.254:9002/hooks/lms">数据同步</a>
                    <div class="nav-scroller py-1 mb-2">
                        <nav class="nav d-flex">
                            <a class="p-2 text-muted" href="/schools">{{__('Schools')}}</a>
                            <a class="p-2 text-muted" href="/teachers">{{__('Teachers')}}</a>
                            <a class="p-2 text-muted" href="/agencies">{{__('Agency')}}</a>
                            <a class="p-2 text-muted" href="/students">{{__('Students')}}</a>
                            <a class="p-2 text-muted" href="/orders">{{__('Orders')}}</a>
                        </nav>
                      </div>
                    <div class="nav-scroller py-1 mb-2">
                        <nav class="nav d-flex">
                            <a class="p-2 text-muted" href="/books">{{__('Books')}}</a>
                            <a class="p-2 text-muted" href="/zooms">{{__('Zooms')}}</a>
                            <a class="p-2 text-muted" href="/products">{{__('Products')}}</a>
                            <a class="p-2 text-muted" href="/rrules">{{__('Rrules')}}</a>
                            <a class="p-2 text-muted" href="/classRecords">{{__('ClassRecords')}}</a>
                            <a class="p-2 text-muted" href="/profiles">{{__('Profiles')}}</a>
                            <a class="p-2 text-muted" href="/bills">{{__('Bills')}}</a>
                        </nav>
                      </div>
                    @role('admin')
                    <div class="nav-scroller py-1 mb-2">
                        <nav class="nav d-flex justify-content">
                            <a class="p-2 text-muted" href="/users">{{__('Users')}}</a>
                            <a class="p-2 text-muted" href="/roles">{{__('Roles')}}</a>
                            <a class="p-2 text-muted" href="/permissions">{{__('Permissions')}}</a>
                        </nav>
                    </div>

                    <div class="nav-scroller py-1 mb-2">
                        <h5>Online Users</h5>
                        <nav class="nav d-flex justify-content">
                            @php
                                $users = new App\User;
                                $users = $users->leastRecentOnline();
                            @endphp
                            <ul>
                            @foreach($users as $tmpUser)
                               <li><a class="p-2 text-muted" href="/dev/su/{{$tmpUser->id}}">{{ $tmpUser->name }}</a></li>
                            @endforeach
                            </ul>
                        </nav>
                    </div>
                    @endrole
                    @endhasanyrole

                    @hasanyrole('agency|student')
                        <div class="container">
                          <div class="row">
                            <div class="col-sm-6  col-md-3 col-lg-3 col-xl-2 col-6 mt-4">
                                <a href="{{ route('classRecords.indexByRole') }}" class="btn btn-lg btn-success"><img class="icon-img" src="{{asset('images/icons/37-512.png')}}" alt=""> {{__('ClassRecords')}}</a>
                            </div>
                            <div class="col-sm-6  col-md-3 col-lg-3 col-xl-2 col-6  mt-4">
                                <a href="{{ route('referrals') }}" class="btn btn-lg btn-primary"><img class="icon-img" src="{{asset('images/icons/63-512.png')}}" alt=""> 推荐好友</a>
                            </div>
                            <div class="col-sm-6  col-md-3 col-lg-3 col-xl-2 col-6  mt-4">
                                <a target="_blank" href="https://shimo.im/docs/252MhfluDU8VGlNa" class="btn btn-lg btn-primary"><img class="icon-img" src="{{asset('images/icons/34-01-256.png')}}" alt=""> 使用帮助</a>
                            </div>
                          </div>
                        </div>
                        
                    @endrole

                    <div class="container links-2">

                        @role('teacher')
                            <a href="/class-records" class="btn btn-outline-dark">ClassRecords</a>
                        @endrole

                        @hasanyrole('student|agency|teacher')
                        @else
                        <a href="{{ route('students.register') }}" class="btn btn-outline-dark"><i class="fas fa-graduation-cap fa-large"></i> 学生信息修改</a>
                        
                        @endhasanyrole
                    </div>
                    <br>
                    <div class="row">

                    @hasanyrole('manager|admin')
                        <div class="col-md-4 pt-5 text-center bg-light">
                            <h5 class="display-5">微信专属登陆码</h5>
                            <p class="lead">长按收藏，一键登陆</p>
                            <img src="{{ asset('storage/loginqr/default0424.png') }}" alt="" width="300px" loading="lazy">
                        </div>
                    @endhasanyrole
                    </div>
            </div>
        </div>
    </div>


</div>
@endsection

@section('styles')
<style>
</style>
@endsection

@section('scripts')
<script>
    window.onload = function () {
        $('.btn-delete').click(function(e){
          e.preventDefault();
          if (confirm('Are you sure?')) {
              window.open($(this).attr('href'), '_blank', 'location=no,titlebar=no,toolbar=no,menubar=no,scrollbars=no,resizable=no,width=400,height=350,status=yes');;
          }
        });

    }
</script>
@endsection