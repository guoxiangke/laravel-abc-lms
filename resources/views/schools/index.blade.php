@extends('layouts.app')

@section('title', 'Schools')

@section('content')
<div class="container">
	<h1>Schools</h1>
  
  <div class="show-links">
    <a href="{{ route('home') }}" class="btn btn-outline-dark"><i class="fas fa-angle-left fa-large"></i> Go Back</a>
    <a href="{{ route('schools.create') }}" class="btn btn-outline-primary">Create</a>
  </div>

  <div class="col-md-12 col-sm-12"> 
      <div class="table-responsive">
        <table class="table">
            <thead>
              <tr>
                  <th>#</th>
                  <th>Name</th>
                  <th>Email</th>
                  <th>Sex</th>
                  <th>Tel</th>
                  <th>Contact</th>
                  <th>PayMent</th>
              </tr>
            </thead>
            <tbody>
              @foreach($schools as $school)
                  <tr id="{{$school->id}}">
                    <th scope="row">
                      <a href="{{ route('schools.edit', $school->id) }}" class="btn btn-sm btn-outline-dark text-uppercase" >Edit</a>
                    </th>
                    <td data-label="Name">{{$school->name}}</td>
                    <td data-label="Email">{{$school->user->email}}</td>
                    @php
                      $profile = $school->user->profiles->first();
                      $contact = null;
                      $paymethod = null;
                      if($profile){
                        $contact = $profile->contacts->first();
                        $paymethod = $school->user->paymethod;
                      }
                    @endphp
                    <td data-label="Sex">{{ $profile?App\Models\Profile::SEXS[$profile->sex]:'-' }}</td>
                    <td data-label="Tel">{{$profile?$profile->telephone:'-'}}</td>
                    <td data-label="Contact">{{ $contact ? App\Models\Contact::TYPES[$contact->type] . ":" . $contact->number : '-' }} </td>

                    <td data-label="PayMent">{{$paymethod?App\Models\PayMethod::TYPES[$paymethod->type] . ":" . $paymethod->number  :'-'}}</td>
                  </tr>
              @endforeach
            </tbody>
          </table>
      </div>
      {{ $schools->onEachSide(1)->links() }}
  </div>
</div>
@endsection
