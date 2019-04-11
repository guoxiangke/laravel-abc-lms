@extends('layouts.app')

@section('title', __('ClassRecords'))

@section('content')
<div class="container">
  <h1>{{__('ClassRecords')}}</h1>
  
  <div class="show-links">
      <a href="{{ route('orders.index') }}" class="btn btn-outline-dark"><i class="fas fa-angle-left fa-large"></i> {{__('Go Back')}}</a>
  </div>
  
  <div class="col-md-12 col-sm-12">
      <div class="table-responsive">
        <table class="table">
            <thead>
              <tr>
                <th scope="col">#</th>
                <th scope="col">Student</th>
                <th scope="col">Teacher</th>
                <th scope="col">Class Time</th>
                <th scope="col">exception</th>
              </tr>
            </thead>
            <tbody>
              @foreach($classRecords as $classRecord)
                  <tr>
                    <th scope="row">
                      <a class="btn btn-sm btn-info text-uppercase" href="{{ route('classRecords.show', $classRecord->id) }}">{{$classRecord->id}}</a>
                      @if(!$classRecord->remark)
                        <a class="btn btn-sm btn-warning text-uppercase" href="{{ route('classRecords.edit', $classRecord->id) }}">!Evaluation</a>
                        <a class="btn btn-sm btn-primary text-uppercase" href="https://zoom.us/j/{{ $classRecord->teacher->teacher->zoom->pmi }}">Zoom</a>
                      @endif

                      @if(!$mp3Url = $classRecord->getFirstMediaUrl('mp3'))
                      <a class="btn btn-sm btn-warning text-uppercase" href="{{ route('classRecords.edit', $classRecord->id) }}">!Mp3</a>
                      @endif

                      @if(!$mp4Url = $classRecord->getFirstMediaUrl('mp4'))
                      <a class="btn btn-sm btn-warning text-uppercase" href="{{ route('classRecords.edit', $classRecord->id) }}">!Mp4</a>
                      @endif
                          
                    </th>
                    <td data-label="Student">{{$classRecord->user->profiles->first()->name}}</td>
                    <td data-label="老师">{{$classRecord->teacher->profiles->first()->name}}</td>
                    <td data-label="ClassAt">{{$classRecord->generated_at->format('m/d H:i 周N')}}</td>
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
