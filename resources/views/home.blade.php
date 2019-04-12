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
                    @hasanyrole('manager|admin')
                    <div class="nav-scroller py-1 mb-2">
                        <nav class="nav d-flex justify-content-between">
                            <a class="p-2 text-muted" href="/schools">{{__('Schools')}}</a>
                            <a class="p-2 text-muted" href="/zooms">{{__('Zooms')}}</a>
                            <a class="p-2 text-muted" href="/teachers">{{__('Teachers')}}</a>
                            <a class="p-2 text-muted" href="/agencies">{{__('Agency')}}</a>
                            <a class="p-2 text-muted" href="/books">{{__('Books')}}</a>
                            <a class="p-2 text-muted" href="/students">{{__('Students')}}</a>
                            <a class="p-2 text-muted" href="/products">{{__('Products')}}</a>
                            <a class="p-2 text-muted" href="/orders">{{__('Orders')}}</a>
                            <a class="p-2 text-muted" href="/rrules">{{__('Rrules')}}</a>
                            <a class="p-2 text-muted" href="/classRecords">{{__('ClassRecords')}}</a>
                            @role('admin')
                            <a class="p-2 text-muted" href="/users">{{__('Users')}}</a>
                            <a class="p-2 text-muted" href="/roles">{{__('Roles')}}</a>
                            <a class="p-2 text-muted" href="/permissions">{{__('Permissions')}}</a>
                            @endrole
                        </nav>
                      </div>
                    @endhasanyrole
                    <div class="links-2">
                        <br>
                        <?php //dd(Auth::user()->getRoleNames()->toArray());?>
                        @role('student')
                            <p>欢迎您，xxx 学员</p>
                            <a href="/class-records" class="btn btn-outline-dark">我的上课记录</a>
                            @unlessrole('agency')
                            <a href="{{ route('agencies.register') }}" class="btn btn-outline-dark"><i class="fas fa-handshake fa-large"></i> 成为代理</a>
                            @endunlessrole
                        @endrole


                        @role('teacher')
                            <p>Welcome，Teacher</p>
                            <a href="/class-records" class="btn btn-outline-dark">ClassRecords</a>
                        @endrole


                        @role('agency')
                            <?php $link = route('register.recommend',['user'=>Auth::user()]); ?>
                            <p>欢迎您</p>
                            <a href="{{ route('students.recommend') }}" class="btn btn-outline-dark">我的学生</a>
                            <a href="{{ route('classRecords.indexByRole') }}" class="btn btn-outline-dark">所有学员上课记录</a>


                        <div class="d-md-flex flex-md-equal w-100 my-md-3 pl-md-3">
                          <div class="bg-light mr-md-3 pt-3 px-3 pt-md-5 px-md-5 text-center overflow-hidden">
                            <div class="my-3 p-3">
                              <h2 class="display-5">我的推荐码</h2>
                              <p class="lead">长按可保存到手机</p>
                                
                            </div>
                            <div class="shadow-sm mx-auto" style="overflow: hidden; border-radius: 21px;">
                                
                                <img src="data:image/png;base64, {!! base64_encode(QrCode::format('png')->size(300)->margin(2)->generate($link)) !!} ">
                            </div>
                              <p class="pt-3">推荐链接： {{ $link }}</p>
                          </div>
                        </div>
                        @endrole

                        @hasanyrole('student|agency|teacher')
                        @else
                        <a href="{{ route('students.register') }}" class="btn btn-outline-dark"><i class="fas fa-graduation-cap fa-large"></i> 学生入口</a>

                        <a href="{{ route('agencies.register') }}" class="btn btn-outline-dark"><i class="fas fa-handshake fa-large"></i> 代理入口</a>

                        <a href="{{ route('teachers.register') }}" class="btn btn-outline-dark"><i class="fas fa-chalkboard-teacher fa-large"></i> I'm a teacher</a>
                        
                        @endhasanyrole
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    .links > a {
        color: #636b6f;
        padding: 0 25px;
        font-size: 13px;
        font-weight: 600;
        letter-spacing: .1rem;
        text-decoration: none;
    }
</style>
@endsection
