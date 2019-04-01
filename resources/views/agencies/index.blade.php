@extends('layouts.app')

@section('title', 'Agencies')

@section('content')
<div class="container">
	<h1>Agencies</h1>

	<button type="button" class="btn btn-outline-primary"><a href="{{ route('agencies.create') }}">Create</a></button>
	
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
					@foreach($agencies as $agency)
					    <tr>
					      <th scope="row"><a href="#{{$agency->id}}">{{$agency->id}}</a></th>
					      <td data-label="Name">{{$agency->user->profile->name}}</td>
					      <td data-label="Email">{{$agency->user->email}}</td>
					      <td data-label="Sex">{{ App\Models\Profile::SEXS[$agency->user->profile->sex] }}</td>
					      <td data-label="Birthday">{{$agency->user->profile->birthday->format('m/d')}}</td>
					      <td data-label="Telephone">{{$agency->user->profile->telephone}}</td>
					      <td data-label="PayType">{{!is_null($agency->user->paymethod)?App\Models\PayMethod::TYPES[$agency->user->paymethod->type]:'-'}}</td>
					      <td data-label="PayNo">{{!is_null($agency->user->paymethod)?$agency->user->paymethod->number:'-'}}</td>
					      <td data-label="推荐人">{{ $agency->reference?$agency->reference->profile->name:'No' }}</td>
					      <td data-label="Action"><a href="{{ route('agencies.edit', $agency->id) }}">Edit</a></td>
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
