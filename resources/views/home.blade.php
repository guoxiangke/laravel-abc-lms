@extends('layouts.app')

@section('title', __('Home'))

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Dashboard</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                    @hasanyrole('manager|admin')
                    <div class="links">
                        <a href="/schools">Schools</a>
                        <a href="/zooms">Zooms</a>
                        <a href="/teachers">Teachers</a>
                        <a href="/agencies">Agency</a>
                        <a href="/books">Books</a>
                        <a href="/students">Students</a>
                        <a href="/products">Products</a>
                        <a href="/orders">Orders</a>
                        <br><br>
                        <a href="/rrules">Rrules</a>
                        <a href="/classRecords">ClassRecords</a>
                        <br><br>
                        @role('admin')
                        <a href="/users">Users</a>
                        <a href="/roles">Roles</a>
                        <a href="/permissions">Permissions</a>
                        @endrole
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
                            <p>欢迎您，xxx 代理</p>
                        
                            <div class="qr" >
                                <?php $link = route('register.recommend',['user'=>Auth::user()]); ?>
                                <br/>
                                我的推荐码<br/>
                                <img src="data:image/png;base64, {!! base64_encode(QrCode::format('png')->size(300)->margin(2)->generate($link)) !!} ">
                                <br/>长按可保存到手机
                                <br/>
                                推荐链接： {{ $link }}
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
