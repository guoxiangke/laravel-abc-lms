@extends('layouts.app')

@section('title', __('Books'))

@section('content')
<div class="container">
	<h1>{{__('Books')}}</h1>
  <div class="show-links">
    <a href="{{ route('home') }}" class="btn btn-outline-dark"><i class="fas fa-angle-left fa-large"></i> {{__('Go Back')}}</a>
    <a href="{{ route('books.create') }}" class="btn btn-outline-primary">{{__('Create')}}</a>
  </div>

    <div class="col-md-12 col-sm-12 p-0"> 
        <div class="table-responsive">
          <table class="table">
              <thead>
                <tr>
                	<th scope="col">#</th>
                	<th scope="col">Name</th>
                	<th scope="col">type</th>
                	<th scope="col">publisher</th>
                	<th scope="col">page</th>
                  <th scope="col">path</th>
                </tr>
              </thead>
              <tbody>
                @foreach($books as $book)
                    <tr id={{$book->id}}>
                      <th scope="row"><a href="{{ route('books.edit', $book->id) }}" class="btn btn-sm btn-outline-dark text-uppercase">Edit</a></th>
                      <td data-label="Email">{{$book->name}}</td>
                      <td data-label="Password">{{$book->type}}</td>
                      <td data-label="PMI">{{$book->publisher}}</td>
                      <td data-label="PMI">{{$book->page}}</td>
                      <td data-label="PMI">{{$book->path}}</td>
                    </tr>
                @endforeach
              </tbody>
          </table>
        </div>
        {{ $books->onEachSide(1)->links() }}
    </div>
</div>
@endsection
