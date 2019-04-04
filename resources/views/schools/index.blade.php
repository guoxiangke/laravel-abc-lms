@extends('layouts.app')

@section('title', 'Schools')

@section('content')
<div class="container">
	<h1>Schools</h1>
  
  <div class="show-links">
    <button type="button" class="btn btn-outline-primary"><a href="{{ route('schools.create') }}">Create</a></button>
  </div>

  <div class="col-md-12 col-sm-12"> 
      <div class="table-responsive">
        <table class="table">
            <thead>
              <tr>
                  @foreach($tableHeader as $title)
                  <th scope="col">{{ $title }}</th>
                  @endforeach
              </tr>
            </thead>
            <tbody>
              @foreach($schools as $school)
                  <tr>
                    <th scope="row"><a href="#{{$school->id}}">{{$school->id}}</a></th>
                    <td data-label="Name">{{$school->name}}</td>
                    <td data-label="Email">{{$school->user->email}}</td>
                    <td data-label="Sex">{{ App\Models\Profile::SEXS[$school->user->profile->sex] }}</td>
                    <td data-label="Tel">{{$school->user->profile->telephone}}</td>
                    <?php
                      $contact = $school->user->profile->contact;
                      $paymethod = $school->user->paymethod;
                    ?>
                    <td data-label="Contact">{{ $contact ? App\Models\Contact::TYPES[$contact->type] . ":" . $contact->number : '-' }} </td>

                    <td data-label="PayMent">{{$paymethod?App\Models\PayMethod::TYPES[$paymethod->type] . ":" . $paymethod->number  :'-'}}</td>
                    <td data-label="Action"><a href="{{ route('schools.edit', $school->id) }}">Edit</a></td>
                  </tr>
              @endforeach
            </tbody>
          </table>
      </div>
      {{ $schools->onEachSide(1)->links() }}
  </div>
</div>
@endsection
