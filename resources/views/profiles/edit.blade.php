@extends('layouts.app')

@section('title', __('Edit Profile'))

@section('content')
<div class="container">
	<h1 class="h3 mb-0 text-gray-800">{{__('Edit Profile')}}</h1>
    <div class="row justify-content-center">
        <div class="col-md-12 col-sm-12"> 
            {!! form($form) !!}
        </div>
    </div>
</div>
@endsection
