@extends('layouts.app')

@section('title', __('Teachers'))

@section('content')
<div class="container">
	<h1>{{__('Teachers')}}</h1>
	<div class="show-links">
    	<a href="{{ route('home') }}" class="btn btn-outline-dark"><i class="fas fa-angle-left fa-large"></i> {{__('Go Back')}}</a>
		<a href="{{ route('teachers.create') }}" class="btn btn-outline-primary">{{__('Create')}}</a>
		<button class="btn btn-light">本页记录数量：{{count($teachers)}}</button>
		@include('shared.search')
	</div>

    <div class="row justify-content-center">
        <div class="col-md-12 col-sm-12"> 
        	<div class="table-responsive">
			  <table class="table">
				  <thead>
				    <tr>
				    	<th scope="col"># Records</th>
						<th scope="col">Name</th>
						<th scope="col">PMI</th>
						<th scope="col">Zoom</th>
						<th scope="col">phone/Password</th>
						<th scope="col">Sex</th>
						<th scope="col">Birthday</th>
						<th scope="col">School</th>
						<th scope="col">Referrer</th>
						<th scope="col">Rate</th>
				    </tr>
				  </thead>
				  <tbody>
					@foreach($teachers as $teacher)
					    <tr id="{{$teacher->id}}">
					      <th scope="row">
					      	<a href="{{ route('teachers.edit', $teacher->id) }}" class="btn btn-sm btn-outline-dark text-uppercase" >Edit</a>
					      	<a href="{{ route('classRecords.indexbyTeacher', $teacher->id) }}" class="btn btn-sm btn-outline-dark" ><i class="fas fa-list-ul fa-large"></i></a>
						  </th>
					      @php
					      	$birthday = false;
					      	$profile = $teacher->user->profiles->first();
					      	if($profile){
						      	$birthday = $profile->birthday;
						    }
						    $recommend = $profile->recommend;
							$school = $teacher->school; 
					      @endphp
					      <td data-label="Records">{{$profile?$profile->name:'-'}}
					      	
					      </td>
					      <td data-label="PMI"><a target="_blank" href="https://zoom.us/j/{{$teacher->zoom?$teacher->zoom->pmi:'-'}}">{{$teacher->zoom?$teacher->zoom->pmi:''}}</a></td>
					      <td data-label="ZoomEmail"><a target="_blank" href="/zooms/{{$teacher->zoom?$teacher->zoom->id :'#' }}/edit">{{$teacher->zoom? explode('@',$teacher->zoom->email)[0] :''}}</a></td>
					      <td data-label="ZoomPassword">
					      	{{$profile?$profile->telephone:'-'}}<br/>
					      	{{$teacher->zoom?$teacher->zoom->password:'-'}}
					      </td>
					      <td data-label="SEX">{{ $profile?App\Models\Profile::SEXS[$profile->sex]:'-' }}</td>
					      <td data-label="Birthday">
					      	{{ $birthday ? $birthday->format('Y-m-d') : '-' }}
					      </td>
					      <td data-label="School">{{ $school ? $school->name : 'FreeLancer' }}</td>
					      <td data-label="Referrer">{{ $recommend ? $profile->recommend->name : '-' }}</td>
					      <td data-label="Rate">{{$teacher->price??'-'}}</td>
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

@section('scripts')
	@include('scripts.search')
@endsection
