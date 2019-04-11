@extends('layouts.app')

@section('title', 'Create Product')


@section('content')
<div class="container">
	<h1>Create Product</h1>

    <div class="show-links">
        <a href="{{ route('products.index') }}" class="btn btn-outline-dark"><i class="fas fa-angle-left fa-large"></i> {{__('Go Back')}}</a>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-12 col-sm-12"> 
            {!! form($form) !!}
        </div>
    </div>
</div>
@endsection
