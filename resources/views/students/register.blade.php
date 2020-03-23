@extends('sb-admin2.app')

@section('title', '年级信息确认')

@section('content')
<div class="container">
	<h1 class="h3 mb-0 text-gray-800"><i class="fas fa-user-graduate fa-fw"></i>年级信息确认</h1>
	<br>

    <div class="row justify-content-center">
        <div class="col-md-12 col-sm-12"> 
            {!! form($form) !!}
        </div>
    </div>
</div>
@endsection
