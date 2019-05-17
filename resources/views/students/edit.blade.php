@extends('layouts.app')

@section('title', 'Edit Student')

@section('content')
<div class="container">
	<h1>Edit Student</h1>

    <div class="show-links">
        <a href="{{ route('students.index') }}" class="btn btn-outline-dark"><i class="fas fa-angle-left fa-large"></i> {{__('Go Back')}}</a>
        @if(!$student->user->hasRole('agency'))
        <a href="{{ route('agencies.upgrade', $student->user_id) }}" class="btn btn-outline-danger">升级为代理</a>
        @endif
    </div>

    <div class="row justify-content-center">
        <div class="col-md-12 col-sm-12"> 
            {!! form($form) !!}
        </div>
    </div>
</div>
@endsection

@include('layouts.chosen')

