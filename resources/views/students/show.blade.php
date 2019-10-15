@extends('layouts.app')

@section('content')
<div class="container">
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
						<th>QQ/Wechat</th>
						<th>推荐人</th>
						<th>微信绑定</th>
				    </tr>
				  </thead>
				  <tbody>
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
					      	<a href="{{ route('classRecords.indexbyStudent', $student->user_id) }}" class="btn btn-sm btn-outline-dark text-uppercase">上课记录</a>
							<a href="{{ route('orders.create') }}?trail=1&user_id={{$student->user->id}}&agency={{$profile->recommend_uid}}" class="btn btn-sm btn-outline-dark text-uppercase">试听</a>
					  	  </th>
					      <td data-label="姓名">
					      		{{$profile->name}}
					      </td>
					      <td data-label="性别">{{ App\Models\Profile::SEXS[$profile->sex] }}</td>
					      <td data-label="生日">
					      	{{$birthday?$birthday->format('y-m-d'):'-'}}
					      </td>
					      <td data-label="年级">{{ App\Models\Student::GRADES[$student->grade] }}</td>
					      <td data-label="QQ/Wechat">
					      	{{ $contact ?  str_replace("+86","",$contact->number) : '-' }}
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
				  </tbody>
			  </table>
			</div>
			@include('shared.remark', ['model' => $student])
        </div>
    </div>
</div>
@endsection
