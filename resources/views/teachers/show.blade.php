@extends('sb-admin2.app')

@php
	$birthday = false;
	$profile = $teacher->user->profiles->first();
	if($profile){
	  	$birthday = $profile->birthday;
	}
	$recommend = $profile->recommend;
	$school = $teacher->school;
@endphp

@section('title', $profile?$profile->name:'-')


@section('content')
<div class="container">
	<h1 class="h3 mb-0 text-gray-800">{{$profile?$profile->name:'-'}}</h1>

    <div class="show-links">
        <a href="{{ route('teachers.index') }}" class="btn btn-outline-dark"><i class="fas fa-angle-left fa-large"></i> {{__('Go Back')}}</a>
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
                        <th scope="col">Rate</th>
                        <th scope="col">辞退/激情/守时/网络/嘈杂/基督</th>
                        <th scope="col">Sex</th>
                        <th scope="col">Birthday</th>
                        <th scope="col">Pay</th>
                        <th scope="col">推荐人</th>
                    </tr>
                  </thead>
                  <tbody>
                        <tr id="{{$teacher->id}}">
                          <th scope="row">
                            <a href="#{{$teacher->id}}"></a>
                            <a href="{{ route('classRecords.indexbyTeacher', $teacher->id) }}" class="btn btn-sm btn-outline-dark" ><i class="fas fa-list-ul"></i></a>
                            <a href="{{$teacher->extra_attributes->timesheet?:''}}" target="_blank" class="btn btn-sm btn-outline-{{$teacher->extra_attributes->timesheet?'success':'dark disabled'}}" ><i class="fas fa-calendar-alt"></i></a>
                            <a href="https://www.messenger.com/t/{{$teacher->extra_attributes->messenger?:''}}" target="_blank" class="btn btn-sm btn-outline-{{$teacher->extra_attributes->messenger?'success':'dark disabled'}}" ><i class="fab fa-facebook-messenger"></i></a>
                            <a href="{{ route('teachers.edit', $teacher->id) }}" class="btn btn-sm btn-outline-dark text-uppercase" ><i class="fas fa-user-edit"></i></a>
                          </th>
                          <td data-label="Name">
                            {{$profile?$profile->name:'-'}}
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
                          <td data-label="School">
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
                  </tbody>
                </table>
            </div>

        <div class="comment mt-5">
          <h3>Remarks: </h3>
          @comments(['model' => $teacher])
        </div>

        </div>
    </div>
</div>
@endsection
