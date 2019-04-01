@extends('layouts.app')

@section('title', 'Zooms')

@section('content')
<div class="container">
	<h1>Zooms</h1>

  <button type="button" class="btn btn-outline-primary"><a href="{{ route('zooms.create') }}">Create</a></button>

    <div class="col-md-12 col-sm-12"> 
        <div class="table-responsive">
          <table class="table">
              <thead>
                <tr>
                	<th scope="col">#</th>
                	<th scope="col">Email</th>
                	<th scope="col">Password</th>
                	<th scope="col">PMI</th>
                	<th scope="col">Used By</th>
                	<th scope="col">Action</th>
                </tr>
              </thead>
              <tbody>
                @foreach($zooms as $zoom)
                    <tr>
                      <th scope="row"><a href="#{{$zoom->id}}">{{$zoom->id}}</a></th>
                      <td data-label="Email">{{$zoom->email}}</td>
                      <td data-label="Password">{{$zoom->password}}</td>
                      <td data-label="PMI">{{$zoom->pmi}}</td>
                      <td data-label="Used">{{$zoom->teacher?$zoom->teacher->user->profile->name:'unused'}}</td>
                      <td data-label="Action"><a href="{{ route('zooms.edit', $zoom->id) }}">Edit</a></td>
                    </tr>
                @endforeach
              </tbody>
          </table>
        </div>
        {{ $zooms->onEachSide(1)->links() }}
    </div>
</div>
@endsection
