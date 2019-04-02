@extends('layouts.app')

@section('title', 'Create ClassRecord')

@section('content')
<div class="container">
	<h1>Create ClassRecord</h1>
	<br>
    <div class="row justify-content-center">
        <div class="col-md-12 col-sm-12"> 
            {!! form($form) !!}
        </div>
    </div>
</div>
@endsection
