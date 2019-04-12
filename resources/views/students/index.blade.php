@extends('layouts.app')

@section('title', __('Students'))

@section('content')

<div class="container">
	<h1>{{__('Students')}}</h1>
	
	<div class="show-links">
    	<a href="{{ route('home') }}" class="btn btn-outline-dark"><i class="fas fa-angle-left fa-large"></i> {{__('Go Back')}}</a>
		<a href="{{ route('students.create') }}" class="btn btn-outline-primary">{{__('Create')}}</a>
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
						<th>推荐人</th>
				    </tr>
				  </thead>
				  <tbody>
					@foreach($students as $student)
					    <tr id={{$student->id}}>
					      <th scope="row" data-label="Id">
					      	<a href="{{ route('students.edit', $student->id) }}" class="btn btn-sm btn-outline-dark text-uppercase">Edit</a>
					      	<a href="{{ route('classRecords.indexbyStudent', $student->id) }}" class="btn btn-sm btn-outline-dark text-uppercase">所有上课记录</a>
					  </th>
					      @php
					      	$profile = $student->user->profiles->first();
				      		$birthday = $profile->birthday;
							
				      		$recommend = $profile->recommend;
				      		//dd( $profile->toArray());

				      		$contact = $profile->contacts->first();
					      @endphp
					      <td data-label="Name">{{$profile->name}}</td>
					      <td data-label="Sex">{{ App\Models\Profile::SEXS[$profile->sex] }}</td>
					      <td data-label="Birthday">
					      	{{$birthday?$birthday->format('m/d'):'-'}}
					      </td>
					      <td data-label="Grade">{{ App\Models\Student::GRADES[$student->grade] }}</td>
					      <td data-label="登陆手机">{{$profile->telephone}}</td>
					      <td data-label="QQ/Wechat">
					      	{{ $contact ?  $contact->number : '-' }}
					      </td>
					      <td data-label="推荐人">{{
					      	$recommend ? $profile->recommend->name : '-' }}</td>
					      
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
