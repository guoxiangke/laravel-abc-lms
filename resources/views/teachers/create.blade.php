@extends('sb-admin2.app')

@section('title', 'Create Teacher')

@section('content')
<div class="container">
	<h1 class="h3 mb-0 text-gray-800">Create Teacher</h1>

    <div class="show-links">
        <a href="{{ route('teachers.index') }}" class="btn btn-outline-dark"><i class="fas fa-angle-left fa-large"></i> {{__('Go Back')}}</a>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-12 col-sm-12"> 
            {!! form($form) !!}
        </div>
    </div>
</div>
@endsection

@include('layouts.chosen')
