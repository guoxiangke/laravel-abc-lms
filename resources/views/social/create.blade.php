@extends('layouts.app')

@section('title', '绑定')

@section('content')
<div class="container">
	<h1>绑定</h1>
    <div class="row justify-content-center">
        <div class="col-md-12 col-sm-12"> 
            {!! form($form) !!}
        </div>
    </div>
</div>
@endsection
