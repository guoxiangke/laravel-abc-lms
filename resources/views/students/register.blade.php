@extends('layouts.app')

@section('title', '学生信息登记注册')

@section('content')
<div class="container">
	<h1><i class="fas fa-user fa-2x"></i> 学生信息登记</h1>

    <div class="show-links">
        <a href="{{ route('home') }}" class="btn btn-outline-dark"><i class="fas fa-angle-left fa-large"></i> Go Back</a>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-12 col-sm-12"> 
            {!! form($form) !!}
        </div>
    </div>
</div>
@endsection
