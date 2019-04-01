@extends('layouts.app')

@section('title', 'Edit Order')

@section('content')
<div class="container">
	<h1>Edit Order</h1>
    <button type="button" class="btn btn-outline-primary"><a href="{{ route('orders.index') }}">Go Back</a></button>
    <br>
    <br>
    <div class="row justify-content-center">
        <div class="col-md-12 col-sm-12"> 
            {!! form($form) !!}
        </div>
    </div>
</div>
@endsection
