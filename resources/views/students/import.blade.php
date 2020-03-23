@extends('sb-admin2.app')

@section('title', 'Import Students')

@section('content')
<div class="container">
	<h1 class="h3 mb-0 text-gray-800"><i class="fa fa-fw fa-address-card"></i>Import Students</h1>
	<br>	

    <div class="row justify-content-center">
        <div class="col-md-12 col-sm-12"> 
            {!! form($form) !!}
        </div>
    </div>
</div>
@endsection

@include('layouts.chosen')

