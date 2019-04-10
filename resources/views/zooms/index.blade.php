@extends('layouts.app')

@section('title', 'Zooms')

@section('content')
<div class="container">
	<h1>Zooms</h1>
  <div class="show-links">
    <a href="{{ route('home') }}" class="btn btn-outline-dark"><i class="fas fa-angle-left fa-large"></i> Go Back</a>
    <a href="{{ route('zooms.create') }}" class="btn btn-outline-primary">Create</a>
  </div>

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
                </tr>
              </thead>
              <tbody>
                @foreach($zooms as $zoom)
                    <tr id={{$zoom->id}}>
                      <th scope="row"><a href="{{ route('zooms.edit', $zoom->id) }}" class="btn btn-sm btn-outline-dark text-uppercase">Edit</a></th>
                      <td data-label="Email">{{$zoom->email}}</td>
                      <td data-label="Password">{{$zoom->password}}</td>
                      <td data-label="PMI">{{$zoom->pmi}}</td>
                      @php
                        $profile = $zoom->teacher?$zoom->teacher->user->profiles->first():null;
                      @endphp
                      <td data-label="Used">{{!is_null($profile)?$profile->name:'unused'}}</td>
                    </tr>
                @endforeach
              </tbody>
          </table>
        </div>
        {{ $zooms->onEachSide(1)->links() }}
    </div>
</div>
@endsection
