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
						<th scope="col">PMI</th>
						<th scope="col">ZoomEmail</th>
						<th scope="col">ZoomPassword</th>
						<th scope="col">Sex</th>
						<th scope="col">Birthday</th>
						<th scope="col">telephone</th>
						<th scope="col">School</th>
				    </tr>
				  </thead>
				  <tbody>
					@foreach($teachers as $teacher)
					    <tr id="{{$teacher->id}}">
					      <th scope="row">
					      	<a href="{{ route('teachers.edit', $teacher->id) }}" class="btn btn-sm btn-outline-dark text-uppercase" >Edit</a>
					      </th>
					      <td data-label="Name">{{$teacher->user->profiles->first()->name}}</td>
					      <td data-label="PMI">{{$teacher->zoom?$teacher->zoom->pmi:'-'}}</td>
					      <td data-label="ZoomEmail">{{$teacher->zoom?$teacher->zoom->email:'-'}}</td>
					      <td data-label="ZoomPassword">{{$teacher->zoom?$teacher->zoom->password:'-'}}</td>
					      <td data-label="SEX">{{ App\Models\Profile::SEXS[$teacher->user->profiles->first()->sex] }}</td>
							<?php
								$birthday =$teacher->user->profiles->first()->birthday; 
								$school =$teacher->school; 
								$paymethod = $teacher->user->paymethod; 
							?>
					      <td data-label="Birthday">
					      	{{ $birthday ? $birthday->format('y/m/d') : '-' }}
					      </td>
					      <td data-label="Tel">{{$teacher->user->profiles->first()->telephone}}</td>
					      <td data-label="School">{{ $school ? $school->name : 'FreeLancer' }}</td>
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
