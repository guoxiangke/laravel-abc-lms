@extends('sb-admin2.app')

@section('title', 'Edit Book')

@section('content')
<div class="container">
	<h1>Edit Book</h1>

    <div class="show-links">
        <a href="{{ route('books.index') }}" class="btn btn-outline-dark"><i class="fas fa-angle-left fa-large"></i> {{__('Go Back')}}</a>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-12 col-sm-12"> 
            {!! form($form) !!}
        </div>
    </div>
</div>
@endsection
