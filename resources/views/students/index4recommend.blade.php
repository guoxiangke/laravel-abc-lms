@extends('sb-admin2.app')

@section('title', __('Recommends'))

@section('content')

<div class="container">

    <h1 class="h3 mb-0 text-gray-800"><i class="fab fa-fw fa-slideshare"></i> {{__('Recommends')}}</h1>
    <br>

    <div class="row justify-content-center">
        <div class="col-md-12 col-sm-12 p-0"> 
        	<div class="table-responsive">
			  <table class="table">
				  <thead>
				    <tr>
				    	<th>#</th>
						<th>姓名</th>
						<th>性别</th>
						<th>生日</th>
				    </tr>
				  </thead>
				  <tbody>
					@foreach($profiles as $profile)
					    <tr id="{{$profile->id}}">
						@if($profile->user->isStudent())
					      <th scope="row" data-label="Id">
					      	<a href="{{ route('classRecords.indexbyStudent', $profile->user_id) }}" class="btn btn-sm btn-outline-dark text-uppercase">上课情况</a>
					      </th>
						@else
							<th>暂无试听，请保持跟进<br>
								手机：{{$profile->telephone}} <br>
								扫码日期：{{$profile->created_at->format('Y.m.d')}} 
							</th>
						@endif

					      @php
				      		$birthday = $profile->birthday;
					      @endphp
					      <td data-label="Name">{{$profile->name}}</td>
					      <td data-label="Sex">{{ App\Models\Profile::SEXS[$profile->sex] }}</td>
					      <td data-label="Birthday">
					      	{{$birthday?$birthday->format('m-d'):'-'}}
					      </td>
					      
					    </tr>
					@endforeach
				  </tbody>
				</table>
			</div>
			{{ $profiles->onEachSide(1)->links() }}
        </div>
    </div>
</div>
@endsection
