@extends('layouts.app')

@section('title', 'Teachers')

@section('content')
<div class="container">
	<h1>Teachers</h1>

	<button type="button" class="btn btn-outline-primary"><a href="{{ route('teachers.create') }}">Create</a></button>

    <div class="row justify-content-center">
        <div class="col-md-12 col-sm-12"> 
        	<div class="table-responsive">
			  <table class="table">
				  <thead>
				    <tr>
				    	@foreach($tableHeader as $value)
				    	<th scope="col">{{ $value }}</th>
				    	@endforeach
				    </tr>
				  </thead>
				  <tbody>
					@foreach($teachers as $teacher)
					    <tr>
					      <th scope="row"><a href="#{{$teacher->id}}">{{$teacher->id}}</a></th>
					      <td>{{$teacher->user->profile->name}}</td>
					      <td>{{$teacher->user->email}}</td>
					      <td>{{$teacher->zoom->email}}</td>
					      <td>{{$teacher->zoom->password}}</td>
					      <td>{{ App\Models\Profile::SEXS[$teacher->user->profile->sex] }}</td>
					      <td data-label="Birthday">{{$teacher->user->profile->birthday->format('y/m/d')}}</td>
					      <td>{{$teacher->user->profile->telephone}}</td>
					      <td>{{ $teacher->school? $teacher->school->name : 'FreeLancer' }}</td>
					      <td>{{$teacher->user->paymethod?App\Models\PayMethod::TYPES[$teacher->user->paymethod->type]:'-'}}</td>
					      <td>{{$teacher->user->paymethod?$teacher->user->paymethod->number:'-'}}</td>
					      <td><a href="{{ route('teachers.edit', $teacher->id) }}">Edit</a></td>
					    </tr>
					@endforeach
				  </tbody>
				</table>
			</div>
			{{ $teachers->onEachSide(1)->links() }}
        </div>
    </div>
</div>
@endsection
