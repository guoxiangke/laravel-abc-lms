@extends('sb-admin2.app')

@section('title', __('Home'))

@section('content')
    @hasanyrole('manager|admin')
        @include('dashboard.admin')      
    @else
    	@include('dashboard.user')
    @endrole
@endsection