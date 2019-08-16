@extends('layouts.app')

@section('title', __('Students'))

@section('content')

<div class="container">
	<h1>{{__('Students')}}</h1>
	
	<div class="show-links">
    	<a href="{{ route('home') }}" class="btn btn-outline-dark"><i class="fas fa-angle-left fa-large"></i> {{__('Go Back')}}</a>
		<a href="{{ route('students.create') }}" class="btn btn-outline-primary">{{__('Create')}}</a>
		<button class="btn btn-light">本页记录数量：{{count($students)}}</button>
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
						<th>微信绑定</th>
				    </tr>
				  </thead>
				  <tbody>
					@foreach($students as $student)
				      @php
				      	$profile = $student->user->profiles->first();
				      	if($profile){
				      		$birthday = $profile->birthday;
							
				      		$recommend = $profile->recommend;
				      		//dd( $profile->toArray());

				      		$contact = $profile->contacts->first();
				      		$social = $student->user->socials->first();
				      	}
					    @endphp

						@if($profile)
					    <tr id={{$student->id}}>
					      <th scope="row" data-label="Id">
					      	<a href="{{ route('students.edit', $student->id) }}" class="btn btn-sm btn-outline-dark text-uppercase">Edit</a>
					      	<a href="{{ route('classRecords.indexbyStudent', $student->id) }}" class="btn btn-sm btn-outline-dark text-uppercase">上课记录</a>
					  	  </th>
					      <td data-label="Name">{{$profile->name}}</td>
					      <td data-label="Sex">{{ App\Models\Profile::SEXS[$profile->sex] }}</td>
					      <td data-label="Birthday">
					      	{{$birthday?$birthday->format('Y-m-d'):'-'}}
					      </td>
					      <td data-label="Grade">{{ App\Models\Student::GRADES[$student->grade] }}</td>
					      <td data-label="登陆手机">{{$profile->telephone}}</td>
					      <td data-label="QQ/Wechat">
					      	{{ $contact ?  $contact->number : '-' }}
					      </td>
					      <td data-label="推荐人">{{
					      	$recommend ? $profile->recommend->name : '-' }}</td>
					      <td data-label="action">
					      	@if($social)
					      	{{$social->name}} 
						      	@can('delete', $social)
					              {{ Form::open(['method' => 'DELETE', 'route' => ['socials.destroy', $social->id]]) }}
					                  {{ Form::submit(__('Unbind'), ['class' => 'btn btn-sm btn-delete btn-outline-danger']) }}
					              {{ Form::close() }}
					            @endcan
					      	@else
					      	--
					      	@endif
					      </td>
					      
					    </tr>
					    @else
					    <tr id={{$student->id}}>
					      <th scope="row" data-label="Id">
					      	<a href="{{ route('students.edit', $student->id) }}" class="btn btn-sm btn-outline-dark text-uppercase">Edit</a>
					      	<a href="{{ route('classRecords.indexbyStudent', $student->id) }}" class="btn btn-sm btn-outline-dark text-uppercase">上课记录</a>
					  	  </th>
						  <td>error!</td>
						  <td>No profile!</td>
					    </tr>
					    @endif
					@endforeach
				  </tbody>
				</table>
			</div>
			{{ $students->onEachSide(1)->links() }}
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    window.onload = function () {
        $('.btn-delete').click(function(e){
          e.preventDefault();
          if (confirm('Are you sure?')) {
              $(this).parent('form').submit();
          }
        });

    }
</script>
@endsection
