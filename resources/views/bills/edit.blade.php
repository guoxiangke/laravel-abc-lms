@extends('sb-admin2.app')

@section('title', 'Edit Bill')

@section('content')
<div class="container">
	<h1 class="h3 mb-0 text-gray-800"><i class="fab fa-fw fa-cc-visa"></i>Edit Bill</h1>

    <div class="show-links">
        <a href="{{ route('bills.index') }}" class="btn btn-outline-dark"><i class="fas fa-angle-left fa-large"></i> {{__('Go Back')}}</a>
    </div>

	  <div class="mt-3  mb-1">
	  {{ Form::open(['method' => 'DELETE', 'route' => ['bills.destroy', $bill->id]]) }}
	      {{ Form::submit('Delete', ['class' => 'btn btn-sm submit-confirm btn-danger']) }}
	  {{ Form::close() }}
	  </div>

    <div class="row justify-content-center">
        <div class="col-md-12 col-sm-12"> 
            {!! form($form) !!}
        </div>
    </div>
</div>
@endsection
@include('layouts.chosen')
