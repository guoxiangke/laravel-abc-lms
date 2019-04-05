@extends('layouts.app')

@section('title', 'Teachers')

@section('content')
<div class="container">
	<h1>Teachers</h1>
	<div class="show-links">
    	<a href="{{ route('home') }}" class="btn btn-outline-dark"><i class="fas fa-angle-left fa-large"></i> Go Back</a>
		<a href="{{ route('teachers.create') }}" class="btn btn-outline-primary">Create</a>
	</div>

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
					      <td>{{$teacher->user->profiles->first()->name}}</td>
					      <td>{{$teacher->user->email}}</td>
					      <td>{{$teacher->zoom->email}}</td>
					      <td>{{$teacher->zoom->password}}</td>
					      <td>{{ App\Models\Profile::SEXS[$teacher->user->profiles->first()->sex] }}</td>
							<?php
								$birthday =$teacher->user->profiles->first()->birthday; 
								$school =$teacher->school; 
								$paymethod = $teacher->user->paymethod; 
							?>
					      <td data-label="Birthday">
					      	{{ $birthday ? $birthday->format('y/m/d') : '-' }}
					      </td>
					      <td>{{$teacher->user->profiles->first()->telephone}}</td>
					      <td>{{ $school ? $school->name : 'FreeLancer' }}</td>
					      <td>{{$paymethod ? App\Models\PayMethod::TYPES[$paymethod->type] : '-'}}</td>
					      <td>{{$paymethod ? $paymethod->number : '-'}}</td>
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
