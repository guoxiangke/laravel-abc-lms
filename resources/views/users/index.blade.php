@extends('layouts.app')

@section('title', __('Users'))

@section('content')
<div class="container">
    <h1><i class="fa fa-users"></i>{{__('Users')}}</h1>
  
  <div class="show-links">
    <a href="{{ route('home') }}" class="btn btn-outline-dark"><i class="fas fa-angle-left fa-large"></i> {{__('Go Back')}}</a>
    <a href="{{ route('roles.index') }}" class="btn btn-outline-primary">Roles</a>
    <a href="{{ route('permissions.index') }}" class="btn btn-outline-primary">{{__('Permissions')}}</a>
    <button class="btn btn-light">本页记录数量：{{count($users)}}</button>
  </div>

  <div class="col-md-12 col-sm-12"> 
      <div class="table-responsive">
        <table class="table">
            <thead>
              <tr>
                  <th>#</th>
                  <th>Name</th>
                  <th>Email</th>
                  <th>Created</th>
              </tr>
            </thead>
            <tbody>
              @foreach($users as $user)
                  <tr id="{{$user->id}}">
                    <th scope="row">
                      {{$user->id}}
                    </th>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->created_at->format('Y-m-d H:i') }}</td>
                  </tr>
              @endforeach
            </tbody>
          </table>
      </div>
      {{ $users->onEachSide(1)->links() }}
  </div>
</div>
@endsection
