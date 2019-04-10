@extends('layouts.app')

@section('title', __('ClassRecords'))

@section('content')
<div class="container">
  <h1>{{__('ClassRecords')}}</h1>
  
  <div class="show-links">
      <a href="{{ route('home') }}" class="btn btn-outline-dark"><i class="fas fa-angle-left fa-large"></i> Go Back</a>
  </div>

  <div class="col-md-12 col-sm-12">
      <div class="table-responsive">
        <table class="table">
            <thead>
              <tr>
              	<th scope="col">#</th>
                <th scope="col">Student</th>
              	<th scope="col">Teacher</th>
                <th scope="col">Agency</th>
                <th scope="col">ClassAt</th>
              	<th scope="col">exception</th>
              </tr>
            </thead>
            <tbody>
              @foreach($classRecords as $classRecord)
                  <tr>
                    <th scope="row">
                      {{$classRecord->id}}
                      <a class="btn btn-sm btn-outline-dark text-uppercase" href="{{ route('classRecords.show', $classRecord->id) }}">
                        View
                      </a>
                      <a class="btn btn-sm btn-outline-dark text-uppercase" href="{{ route('classRecords.edit', $classRecord->id) }}">
                        Edit
                      </a>
                    </th>
                    <td data-label="Teacher">{{$classRecord->user->profiles->first()->name}}</td>
                    <td data-label="student">{{$classRecord->teacher->profiles->first()->name}}</td>
                    <td data-label="agency">{{$classRecord->agency->profiles->first()->name}}</td>
                    <td data-label="ClassAt">{{$classRecord->generated_at->format('n/j H:i 周N')}}</td>
                    <td data-label="exception">{{\App\Models\ClassRecord::EXCEPTION_TYPES[$classRecord->exception]}}
                    </td>
                  </tr>
              @endforeach
            </tbody>
        </table>
      </div>
      {{ $classRecords->onEachSide(1)->links() }}
  </div>
</div>
@endsection
