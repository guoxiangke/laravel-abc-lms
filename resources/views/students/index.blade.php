@extends('layouts.app')

@section('title', 'Students')

@section('content')

<div class="container">
	<h1>Students</h1>
	
	<div class="show-links">
		<button type="button" class="btn btn-outline-primary"><a href="{{ route('students.create') }}">Create</a></button>
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
					@foreach($students as $student)
					    <tr>
					      <th scope="row" data-label="Id"><a href="#{{$student->id}}">{{$student->id}}</a></th>
					      <td data-label="Name">{{$student->user->profile->name}}</td>
					      <td data-label="Sex">{{ App\Models\Profile::SEXS[$student->user->profile->sex] }}</td>
					      <td data-label="Birthday">{{$student->user->profile->birthday->format('m/d')}}</td>
					      <td data-label="Grade">{{ App\Models\Student::GRADES[$student->grade] }}</td>
					      <td data-label="Telephone">{{$student->user->profile->telephone}}</td>
					      <td data-label="PayType">{{!is_null($student->user->paymethod)?App\Models\PayMethod::TYPES[$student->user->paymethod->type]:'-'}}</td>
					      <td data-label="PayNo">{{!is_null($student->user->paymethod)?$student->user->paymethod->number:'-'}}</td>
					      <td data-label="Recommender">{{ !is_null($student->recommender)?$student->recommender->profile->name:'No' }}</td>
					      <td data-label="Email">{{$student->user->email}}</td>
					      <td data-label="Agency">{{ !is_null($student->agency)?$student->agency->profile->name:'No' }}</td>
					      <td data-label="Email">{{$student->user->email}}</td>
					      <td data-label="Action"><a href="{{ route('students.edit', $student->id) }}">Edit</a></td>
					    </tr>
					@endforeach
				  </tbody>
				</table>
			</div>
			{{ $students->onEachSide(1)->links() }}
        </div>
    </div>
</div>
@endsection
