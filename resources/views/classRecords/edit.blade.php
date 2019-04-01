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

    <a href="{{ $goBackLink }}" class="btn btn-outline-dark btn-goback">Go Back</a>

    <div class="row justify-content-center">
        <div class="col-md-12 col-sm-12"> 
            {!! form($form) !!}
        </div>
    </div>
</div>
@endsection
