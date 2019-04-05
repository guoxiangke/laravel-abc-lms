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

                    <div class="links">
                        <a href="/schools">Schools</a>
                        <a href="/zooms">Zooms</a>
                        <a href="/teachers">Teachers</a>
                        <a href="/agencies">Agency</a>
                        <br><br>
                        <a href="/books">Books</a>
                        <a href="/students">Students</a>
                        <a href="/products">Products</a>
                        <a href="/orders">Orders</a>
                        <br><br>
                        <a href="/rrules">Rrules</a>
                        <a href="/classRecords">ClassRecords</a>
                        <br><br>
                        <a href="/users">Users</a>
                        <a href="/roles">Roles</a>
                        <a href="/permissions">Permissions</a>
                    </div>
                    <div class="links-2">
                        <br>
                        @hasanyrole('student|agency|teacher')
                        <p>如果不是三个角色任意的，则显示</p>
                        <a href="{{ route('students.register') }}" class="btn btn-outline-dark"><i class="fas fa-graduation-cap fa-large"></i> 学生入口</a>

                        <a href="{{ route('agencies.register') }}" class="btn btn-outline-dark"><i class="fas fa-handshake fa-large"></i> 代理入口</a>

                        <a href="{{ route('teachers.register') }}" class="btn btn-outline-dark"><i class="fas fa-chalkboard-teacher fa-large"></i> I'm a teacher</a>
                        @else
                        @endhasanyrole
                    </div>

                    <div class="qr " >
                        <?php $link = route('register.recommend',['user'=>Auth::user()]); ?>
                        <br/>
                        我的推荐码<br/>
                        {!! QrCode::size(300)->generate($link); !!}
                        <br/>
                        推荐链接： {{ $link }}
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
