@extends('layouts.app')

@section('content')
<h1>{{$social->name}}</h1>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12 col-sm-12">
            Type: {{ App\Models\Social::TYPES[$social->type] }} <br>
            Name: {{ $social->name }} <br>
            @if($social->avatar)
                <img src="{{ $social->avatar }}" alt="" width="35px;" > <br>
            @endif

            @can('delete', $social)
              <div class="mt-3  mb-1">
              {{ Form::open(['method' => 'DELETE', 'route' => ['socials.destroy', $social->id]]) }}
                  {{ Form::submit(__('Unbind'), ['class' => 'btn btn-sm btn-confirm btn-danger']) }}
              {{ Form::close() }}
              </div>
            @endcan
        </div>
    </div>
</div>
@endsection