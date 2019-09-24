@extends('layouts.app')

@section('title', 'Edit ClassRecord')

@section('content')
<div class="container">
    <h1 class="h3 mb-0 text-gray-800"><i class="fas fa-fw fa-book-reader"></i> Edit ClassRecord</h1>
    <br>
    <?php
        $goBackLink = route('classRecords.indexbyOrder', $classRecord->order);
        if (Auth::user()->hasRole('teacher')) {
            $goBackLink = route('classRecords.indexByRole');
        }
    ?>
    <div class="show-links">
        <a href="{{ $goBackLink }}" class="btn btn-outline-dark"><i class="fas fa-angle-left fa-large"></i> {{__('Go Back')}}</a>
    </div>
    @can('delete', $classRecord)
      <div class="mt-3  mb-1">
      {{ Form::open(['method' => 'DELETE', 'route' => ['classRecords.destroy', $classRecord->id]]) }}
          {{ Form::submit('Delete', ['class' => 'btn btn-sm submit-confirm btn-danger']) }}
      {{ Form::close() }}
      </div>
    @endcan
    

    <div class="row justify-content-center">
        <div class="col-md-12 col-sm-12"> 
            {!! form($form) !!}
        </div>
    </div>
</div>
@endsection
@include('layouts.chosen')
