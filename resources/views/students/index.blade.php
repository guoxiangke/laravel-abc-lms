@extends('layouts.app')

@section('title', 'Students')

@section('content')

<div class="container">
	<h1>Students</h1>
	
	<div class="show-links">
    	<a href="{{ route('home') }}" class="btn btn-outline-dark"><i class="fas fa-angle-left fa-large"></i> Go Back</a>
		<a href="{{ route('students.create') }}" class="btn btn-outline-primary">Create</a>
	</div>

    <div class="row justify-content-center">
        <div class="col-md-12 col-sm-12"> 
        	<div class="table-responsive">
			  <table class="table">
				  <thead>
				    <tr>
				    	<th>Id</th>
						<th>Name</th>
						<th>Sex</th>
						<th>Birthday</th>
						<th>Grade</th>
						<th>登陆手机</th>
						<th>QQ/Wechat</th>
						<th>支付方式</th>
						<th>推荐人</th>
						<th>Action</th>
				    </tr>
				  </thead>
				  <tbody>
					@foreach($students as $student)
					    <tr>
					      <th scope="row" data-label="Id"><a href="#{{$student->id}}">{{$student->id}}</a></th>
					      <td data-label="Name">{{$student->user->profiles->first()->name}}</td>
					      <td data-label="Sex">{{ App\Models\Profile::SEXS[$student->user->profiles->first()->sex] }}</td>
					      <td data-label="Birthday">
					      	@php
					      		$birthday = $student->user->profiles->first()->birthday;
					      		$paymethod = $student->user->paymethod;
					      		$profile = $student->user->profiles->first();
					      		$recommend = $profile->recommend;
					      		$contact = $profile->contacts->first();
					      	@endphp
					      	{{$birthday?$birthday->format('m/d'):'-'}}
					      </td>
					      <td data-label="Grade">{{ App\Models\Student::GRADES[$student->grade] }}</td>
					      <td data-label="登陆手机">{{$profile->telephone}}</td>
					      <td data-label="QQ/Wechat">
					      	{{ $contact ?  $contact->number : '-' }}
					      </td>
					      <td data-label="PayType">{{ $paymethod?App\Models\PayMethod::TYPES[$paymethod->type] . ":" . $paymethod->number :'-'}}</td>
					      <td data-label="推荐人">{{  
					      	$recommend ? $recommend->profiles->first()->name : '-' }}</td>
					      
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
