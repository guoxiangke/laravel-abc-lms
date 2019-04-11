@extends('layouts.app')

@section('title', 'Edit ClassRecord')

@section('content')
<div class="container">
    <h1>Edit ClassRecord</h1>
    <?php
        $goBackLink = route('classRecords.index');
        if(Auth::user()->hasRole('teacher'))
        {
            $goBackLink = route('classRecords.indexByRole');
        }
    ?>
    <div class="show-links">
        <a href="{{ $goBackLink }}" class="btn btn-outline-dark"><i class="fas fa-angle-left fa-large"></i> {{__('Go Back')}}</a>
    </div>
    

    <div class="row justify-content-center">
        <div class="col-md-12 col-sm-12"> 
            {!! form($form) !!}
        </div>
    </div>
</div>
@endsection
