@extends('sb-admin2.app')

@section('title', __('Agencies'))

@section('content')
<div class="container">
	<h1>{{ __('Agencies') }}</h1>

	<div class="show-links">
    	<a href="{{ route('home') }}" class="btn btn-outline-dark"><i class="fas fa-angle-left fa-large"></i> {{__('Go Back')}}</a>
		<a href="{{ route('agencies.create') }}" class="btn btn-outline-primary">{{__('Create')}}</a>
		<button class="btn btn-light">本页记录数量：{{count($agencies)}}</button>
		@include('shared.search')
	</div>
	
    <div class="row justify-content-center">
        <div class="col-md-12 col-sm-12 p-0"> 
        	<div class="table-responsive">
			  <table class="table">
				  <thead>
				    <tr>
				    	<th>#</th>
						<th>User</th>
						<th>Name</th>
						<th>Sex</th>
						<th>Birthday</th>
						<th>Telephone</th>
						<th>PayMethod</th>
						<th>Recommend</th>
				    </tr>
				  </thead>
				  <tbody>
					@foreach($agencies as $agency)
						@php
							$profile = $agency->user->profiles->first();
							$paymethod = $agency->user->paymethod;
						@endphp
					    <tr id={{$agency->id}}>
					      <th scope="row">
					      	<a href="{{ route('classRecords.indexByAgency', $agency->id) }}" class="btn btn-sm btn-outline-dark" ><i class="fas fa-list-ul"></i></a>
					      	<a href="{{ route('agencies.edit', $agency->id) }}" class="btn btn-sm btn-outline-dark text-uppercase" ><i class="fas fa-user-edit"></i></a>
					      </th>
					      <td data-label="Name">
					      	<a href="{{ route('agencies.show', $agency->id) }}">{{$profile->name}}</a></td>
					      <td data-label="Email">{{$agency->name?:'-'}}</td>
					      <td data-label="Sex">{{ App\Models\Profile::SEXS[$profile->sex] }}</td>
					      <td data-label="Birthday">{{$profile->birthday?$profile->birthday->format('m/d'):'-'}}</td>
					      <td data-label="Telephone">{{$profile->telephone}}</td>
					      <td data-label="PayMethod">{{$paymethod?App\Models\PayMethod::TYPES[$paymethod->type] . ': '.$paymethod->number  :'-'}}</td>
					      <td data-label="Recommend">{{ $profile->recommend_uid?$profile->recommend->name:'-' }}</td>
					    </tr>
					@endforeach
				  </tbody>
				</table>
			</div>
			{{ $agencies->onEachSide(1)->links() }}
        </div>
    </div>
</div>
@endsection

@section('scripts')
	@include('scripts.search')
@endsection
