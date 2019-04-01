@extends('layouts.app')

@section('content')
<div class="container">
	<h1>Create Zoom</h1>
	<br>
    <div class="row justify-content-center">
        <div class="col-md-12 col-sm-12"> 
            {!! form($form) !!}
        </div>
    </div>
</div>
@endsection
