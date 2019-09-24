@extends('sb-admin2.app')

@section('title', __('Home'))

@section('content')
    @hasanyrole('manager|admin')
        @include('home4admin')      
    @else
        @include('home4user')    
    @endrole
@endsection