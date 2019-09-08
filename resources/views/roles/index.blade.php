@extends('layouts.app')

@section('title', __('Roles'))

@section('content')
<div class="container">
    <h1><i class="fa fa-key"></i>{{__('Roles')}}</h1>
  
  <div class="show-links">
    <a href="{{ route('home') }}" class="btn btn-outline-dark"><i class="fas fa-angle-left fa-large"></i> {{__('Go Back')}}</a>
    <a href="{{ route('users.index') }}" class="btn btn-outline-primary">{{__('Users')}}</a>
    <a href="{{ route('roles.index') }}" class="btn btn-primary">{{__('Roles')}}</a>
    <a href="{{ route('permissions.index') }}" class="btn btn-outline-primary">{{__('Permissions')}}</a>
    <button class="btn btn-light">本页记录数量：{{count($roles)}}</button>
    <a href="{{ route('roles.create') }}" class="btn btn-warning">Create a Role</a>
  </div>

  <div class="col-md-12 col-sm-12 p-0"> 
      <div class="table-responsive">
        <table class="table">
            <thead>
              <tr>
                <th>#</th>
                <th>Role</th>
                <th>Permissions</th>
                <th>Operation</th>
              </tr>
            </thead>
            <tbody>
              @foreach($roles as $role)
                  <tr id="{{$role->id}}">
                    <th scope="row">
                      {{$role->id}}
                    </th>
                    <td>{{ $role->name }}</td>
                    <td>{{ str_replace(array('[',']','"'),'', $role->permissions()->pluck('name')) }}</td>
                    <td>
                        <a href="{{ URL::to('roles/'.$role->id.'/edit') }}" class="btn btn-outline-info btn-sm">Edit</a>
                        {!! Form::open(['method' => 'DELETE', 'route' => ['roles.destroy', $role->id] ,'class' => ['d-inline']]) !!}
                        {!! Form::submit('Delete', ['class' => 'btn btn-outline-danger  btn-sm']) !!}
                        {!! Form::close() !!}
                    </td>
                  </tr>
              @endforeach
            </tbody>
          </table>
      </div>
  </div>
</div>
@endsection
