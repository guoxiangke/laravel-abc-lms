@extends('layouts.app')

@section('title', __('Students'))

@section('content')

<div class="container">
	<h1>我的{{__('Students')}}</h1>
	
	<div class="show-links">
    	<a href="{{ route('home') }}" class="btn btn-outline-dark"><i class="fas fa-angle-left fa-large"></i> {{__('Go Back')}}</a>
	</div>

    <div class="row justify-content-center">
        <div class="col-md-12 col-sm-12"> 
        	<div class="table-responsive">
			  <table class="table">
				  <thead>
				    <tr>
				    	<th>#</th>
						<th>Name</th>
						<th>Sex</th>
						<th>Birthday</th>
				    </tr>
				  </thead>
				  <tbody>
					@foreach($students as $profile)
					    <tr id={{$profile->id}}>
					      <th scope="row" data-label="Id"><a href="{{ route('classRecords.indexbyStudent', $profile->student->id) }}" class="btn btn-sm btn-outline-dark text-uppercase">上课情况</a></th>
					      @php
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
