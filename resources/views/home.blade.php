@extends('layouts.app')

@section('title', __('Home'))

@php 
    $user = Auth::user();
    $link = route('register.recommend',['user'=>$user]);
    //$avatar = $user->getFirstMedia('avatar');
    //if(!$avatar){
    //    $avatar = public_path('favicon.gif');
    //}else{
    //    $avatar = $avatar->getPath();
    //}
    $avatar = public_path('favicon.gif');
    $avatarString = file_get_contents($avatar);

@endphp

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
                    @endrole
                    @endhasanyrole
                    <div class="links-2">
                        @role('student')
                            <a href="/class-records" class="btn btn-outline-dark">我的上课记录</a>
                        @endrole


                        @role('teacher')
                            <a href="/class-records" class="btn btn-outline-dark">ClassRecords</a>
                        @endrole


                        @role('agency')
                            <a href="{{ route('students.recommend') }}" class="btn btn-outline-dark">我推荐的学生</a>
                            <a href="{{ route('classRecords.indexByRole') }}" class="btn btn-outline-dark">所有学员上课记录</a>
                        @endrole

                        @hasanyrole('student|agency|teacher')
                        @else
                        <a href="{{ route('students.register') }}" class="btn btn-outline-dark"><i class="fas fa-graduation-cap fa-large"></i> 学生入口</a>

                        <a href="{{ route('agencies.register') }}" class="btn btn-outline-dark"><i class="fas fa-handshake fa-large"></i> 代理入口</a>

                        <a href="{{ route('teachers.register') }}" class="btn btn-outline-dark"><i class="fas fa-chalkboard-teacher fa-large"></i> I'm a teacher</a>
                        
                        @endhasanyrole
                    </div>
                    <br>
                    <div class="row">
                    @unlessrole('teacher')
                        <div class="col-md-4 pt-5  text-center bg-light">
                            <h5 class="display-5">优惠推荐码</h5>
                            <p class="lead">截屏保存，转发好友</p>
                            <img src="data:image/png;base64, {!! base64_encode(QrCode::format('png')
                                //->mergeString($avatarString,.2)
                                ->size(300)
                                ->margin(2)
                                ->generate($link)) !!} "  width="260px" loading="lazy">
                        </div>
                    @endhasanyrole

                    @hasanyrole('manager|admin|agency')
                        <div class="col-md-4 pt-5  text-center bg-light">
                            <h5 class="display-5">统一收款码</h5>
                            <p class="lead">支付请备注学生姓名</p>
                            <img src="{{ asset('images/alipay.png')}}" alt="" width="300px" loading="lazy">
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
