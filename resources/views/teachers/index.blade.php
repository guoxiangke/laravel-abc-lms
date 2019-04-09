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
				    	<th scope="col">Action</th>
						<th scope="col">Name</th>
						<th scope="col">Email</th>
						<th scope="col">ZoomEmail</th>
						<th scope="col">ZoomPassword</th>
						<th scope="col">Sex</th>
						<th scope="col">Birthday</th>
						<th scope="col">telephone</th>
						<th scope="col">School</th>
						<th scope="col">PayMethod</th>
				    </tr>
				  </thead>
				  <tbody>
					@foreach($teachers as $teacher)
					    <tr>
					      <th scope="row">
					      	<a href="{{ route('teachers.edit', $teacher->id) }}">Edit {{$teacher->id}}</a>
					      </th>
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

                    	  <td data-label="PayMent">{{$paymethod?App\Models\PayMethod::TYPES[$paymethod->type] . ":" . $paymethod->number  :'-'}}</td>
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
