@extends('sb-admin2.app')

@section('title', __('Permissions'))

@section('content')
<div class="container">
    <h1><i class="fa fa-key"></i>{{__('Permissions')}}</h1>
  
  <div class="show-links">
    <a href="{{ route('home') }}" class="btn btn-outline-dark"><i class="fas fa-angle-left fa-large"></i> {{__('Go Back')}}</a>
    <a href="{{ route('users.index') }}" class="btn btn-outline-primary">{{__('Users')}}</a>
    <a href="{{ route('roles.index') }}" class="btn btn-outline-primary">{{__('Roles')}}</a>
    <a href="{{ route('permissions.index') }}" class="btn btn-primary">{{__('Permissions')}}</a>
    <button class="btn btn-light">本页记录数量：{{count($permissions)}}</button>
    <a href="{{ route('permissions.create') }}" class="btn btn-warning">Create a Permission</a>
  </div>

  <div class="col-md-12 col-sm-12 p-0"> 
      <div class="table-responsive">
        <table class="table">
            <thead>
              <tr>
                <th>#</th>
                <th>Permissions</th>
                <th>Operation</th>
              </tr>
            </thead>
            <tbody>
              @foreach($permissions as $permission)
                  <tr id="{{$permission->id}}">
                    <th scope="row">
                      {{$permission->id}}
                    </th>
                    <td>{{ $permission->name }}</td>
                    <td>
                        <a href="{{ URL::to('permissions/'.$permission->id.'/edit') }}" class="btn btn-info btn-sm" style="margin-right: 3px;">Edit</a>

                        {!! Form::open(['method' => 'DELETE', 'route' => ['permissions.destroy', $permission->id] ,'class' => ['d-inline']]) !!}
                        {!! Form::submit('Delete', ['class' => 'btn btn-outline-danger btn-sm']) !!}
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
