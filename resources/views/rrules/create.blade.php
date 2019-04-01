@extends('layouts.app')

@section('title', 'Create Rrule')

@section('content')
<div class="container">
	<h1>Create Rrule</h1>
    <button type="button" class="btn btn-outline-primary"><a href="{{ route('rrules.index') }}">Go Back</a></button>
    <br>
    <br>
    <div class="row justify-content-center">
        <div class="col-md-12 col-sm-12"> 
            {!! form($form) !!}
        </div>
    </div>
</div>
@endsection
