@extends('sb-admin2.app')

@section('title', __('Teachers'))

@section('content')
<div class="container">
	<h1 class="h3 mb-0 text-gray-800">{{__('Teachers')}}</h1>
	<div class="show-links">
    	<a href="{{ route('home') }}" class="btn btn-outline-dark"><i class="fas fa-angle-left"></i> {{__('Go Back')}}</a>
		<a href="{{ route('teachers.create') }}" class="btn btn-outline-primary">{{__('Create')}}</a>
		<button class="btn btn-light">本页记录数量：{{count($teachers)}}</button>
		@include('shared.search')
	</div>

    <div class="row justify-content-center">
        <div class="col-md-12 col-sm-12 p-0"> 
        	<div class="table-responsive">
			  <table class="table">
				  <thead>
				    <tr>
				    	<th scope="col"># Records</th>
						<th scope="col">Name</th>
						<th scope="col">PMI</th>
						<th scope="col">Rate</th>
						<th scope="col">辞退/激情/守时/网络/嘈杂/基督</th>
						<th scope="col">Sex</th>
						<th scope="col">Birthday</th>
						<th scope="col">推荐人</th>
				    </tr>
				  </thead>
				  <tbody>
					@foreach($teachers as $teacher)
						@php
							$birthday = false;
							$profile = $teacher->user->profiles->first();
							if($profile){
								$birthday = $profile->birthday;
							}
							$recommend = $profile->recommend;
							$school = $teacher->school;
						@endphp
					    <tr id="{{$teacher->id}}">
					      <th scope="row">
					      	<a href="#{{$teacher->id}}"></a>
					      	<a href="{{ route('classRecords.indexbyTeacher', $teacher->id) }}" class="btn btn-sm btn-outline-dark" ><i class="fas fa-list-ul"></i></a>
							<a href="{{$teacher->extra_attributes->timesheet?:''}}" target="_blank" class="btn btn-sm btn-outline-{{$teacher->extra_attributes->timesheet?'success':'dark disabled'}}" ><i class="fas fa-calendar-alt"></i></a>
					      	<a href="https://www.messenger.com/t/{{$teacher->extra_attributes->messenger?:''}}" target="_blank" class="btn btn-sm btn-outline-{{$teacher->extra_attributes->messenger?'success':'dark disabled'}}" ><i class="fab fa-facebook-messenger"></i></a>
					      	<a href="{{ route('teachers.edit', $teacher->id) }}" class="btn btn-sm btn-outline-dark text-uppercase" ><i class="fas fa-user-edit"></i></a>
						  	
					      	@if($school)
					      		{{$school->name}}
					      	@else
					      		@if($teacher->paymethod)
					      			<a class="btn btn-sm btn-outline-primary" target="_blank" href="https://www.paypal.com/myaccount/transfer/homepage/external/summary?recipient={{$teacher->paymethod->number}}">
					      				<i class="fab fa-paypal"></i>
					      			</a>
					      		@else
						      		<button class="btn btn-sm btn-outline-dark" >
						      			<i class="fab fa-paypal"></i>
						      		</button>
					      		@endif
					      	@endif
						  </th>
					      <td data-label="Name">
					      	<a href="{{ route('teachers.show', $teacher->id) }}">
					      		{{$profile?$profile->name:'-'}}
					      	</a>
					      </td>
					      <td data-label="PMI">
					      	<a target="_blank" href="{{$teacher->zhumu}}">{{$teacher->pmi}}</a>
					      </td>
					      <td data-label="Rate"  class="text-right">{{$teacher->price??'-'}}</td>
					      
					      <td data-label="辞退/激情/守时/网络/嘈杂">
					      	<button class="btn btn-sm btn-{{$teacher->active?'outline-primary':'dark'}}">R</button>
					      	<button class="btn btn-sm btn-{{$teacher->extra_attributes->passion?'outline-primary':'dark'}}">P</button>
					      	<button class="btn btn-sm btn-{{$teacher->extra_attributes->ontime?'outline-primary':'dark'}}">T</button>
					      	<button class="btn btn-sm btn-{{$teacher->extra_attributes->network?'outline-primary':'dark'}}">W</button>
					      	<button class="btn btn-sm btn-{{$teacher->extra_attributes->noisy?'outline-primary':'dark'}}">E</button>
					      	<button class="btn btn-sm btn-{{$teacher->extra_attributes->christ?'primary':'outline-dark'}}"><i class="fas fa-cross"></i></button>
					      </td>
					      <td data-label="Sex">
					      	<button class="btn btn-sm btn-{{$profile->sex==1?'':'outline-'}}primary">{{$profile->sex==1?'M':'F'}}</button>
					      	
							
					      </td>
					      <td data-label="Birthday">
					      	{{ $birthday ? $birthday->format('Y-m-d') : '-' }}
					      </td>
					      <td data-label="Referrer">
					      	@if($recommend)
					      	<a href="#{{$recommend->teacher->id}}">
					      		{{ $recommend->profiles->first()->name}}
					      	</a>
					      	@else
					      	 - - 
					      	@endif
					      </td>
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
