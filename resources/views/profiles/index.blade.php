@extends('sb-admin2.app')
@section('title', __('Profile'))
@section('content')
<div class="container">

  <h1 class="h3 mb-0 text-gray-800"><i class="fa fa-fw fa-address-card"></i> {{__('Profiles')}}</h1>
  
  <div class="show-links">
    <a href="{{ route('students.create') }}" class="btn btn-outline-primary">创建学生</a>
    <button class="btn btn-light">本页记录数量：{{count($profiles)}}</button>
  </div>

  <div class="row justify-content-center">
      <div class="col-md-12 col-sm-12 p-0"> 
        <div class="table-responsive">
          <table class="table">
            <thead>
              <tr>
                <th>#</th>
                <th>Name</th>
                <th>Sex</th>
                <th>Birthday</th>
                <th>Telephone</th>
                <th>Student</th>
                <th>Recommender</th>
              </tr>
            </thead>
            <tbody>

            @foreach($profiles as $profile)
                <tr id={{$profile->id}}>
                  <th scope="row" data-label="Id">
                    <a href="{{ route('profiles.edit', $profile->id) }}" class="btn btn-sm btn-outline-dark text-uppercase">Edit</a>
                  </th> 
                  <td data-label="Name">{{$profile->name}}</td>
                  <td data-label="Sex">{{App\Models\Profile::SEXS[$profile->sex]}}</td>
                  <td data-label="Birthday">{{$profile->birthday?$profile->birthday->format('Y-m-d'):'-'}}</td>
                  <td data-label="Telephone">{{$profile->telephone}}</td>
                  
                  <td data-label="Student">
                    @if($profile->user->isAgency())
                    <button class="btn btn-sm btn-success">代理</button>
                    @elseif($profile->user->isTeacher())
                    <button class="btn btn-sm btn-success">老师</button>
                    @elseif($profile->user->isStudent())
                    <button class="btn btn-sm btn-outline-success">学生</button>
                    @else
                      <a href="{{ route('students.register') }}?user_id={{$profile->user_id}}" class="btn btn-sm btn-outline-danger">登记</a>
                    @endif
                  </td>
                  
                  <td data-label="Recommender">{{$profile->recommend?$profile->recommend->name:'-'}}</td>
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
