@extends('layouts.app')

@section('title', __('voteTypes'))

@section('content')
<div class="container">
	<h1>{{__('voteTypes')}}</h1>
  
    <div class="show-links">
        <a href="{{ route('voteTypes.create') }}" class="btn btn-warning">{{__('Create')}}</a>
        <button class="btn btn-light">本页记录数量：{{count($voteTypes)}}</button>
    </div>

    <div class="col-md-12 col-sm-12 p-0"> 
        <div class="table-responsive">
          <table class="table">
              <thead>
                <tr>
                	<th scope="col">#</th>
                	<th scope="col">name</th>
                	<th scope="col">description</th>
                  <th scope="col">type/values</th>
                  <th scope="col">On</th>
                </tr>
              </thead>
              <tbody>
                @foreach($voteTypes as $voteType)
                    <tr id={{$voteType->id}}>
                      <th scope="row">
                        <a href="{{ route('voteTypes.edit', $voteType->id) }}" class="btn btn-sm btn-outline-dark text-uppercase">Edit</a></th>
                      <td data-label="name">{{$voteType->name}}</td>
                      <td data-label="description">{{$voteType->description}}</td>
                      <td data-label="Type">{{$voteType->type}}</td>
                      <td data-label="on">{{$voteType->votable_type}}</td>
                    </tr>
                @endforeach
              </tbody>
          </table>
        </div>
        {{ $voteTypes->onEachSide(1)->links() }}
    </div>
</div>
@endsection
