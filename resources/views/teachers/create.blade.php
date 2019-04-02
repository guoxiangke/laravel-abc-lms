@extends('layouts.app')

@section('title', 'Create Teacher')

@section('content')
<div class="container">
	<h1>Create A New Teacher</h1>

    <div class="show-links">
        <a href="{{ route('teachers.index') }}" class="btn btn-outline-dark"><i class="fas fa-angle-left fa-large"></i> Go Back</a>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-12 col-sm-12"> 
            {!! form($form) !!}
        </div>
    </div>
</div>
@endsection
