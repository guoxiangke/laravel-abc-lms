@extends('layouts.app')

@section('title', __('ClassRecords'))

@section('content')
<div class="container">
  <h1>{{__('ClassRecords')}}</h1>
  
  <div class="show-links">
      <a href="{{ route('home') }}" class="btn btn-outline-dark"><i class="fas fa-angle-left fa-large"></i> {{__('Go Back')}}</a>
  </div>
  
  <div class="col-md-12 col-sm-12">
      <div class="table-responsive">
        <table class="table">
            <thead>
              <tr>
                <th scope="col">#</th>
                <th scope="col">Student</th>
                <th scope="col">Class Time</th>
              	<th scope="col">Status</th>
                <th scope="col">Flag</th>
              </tr>
            </thead>
            <tbody>
              @foreach($classRecords as $classRecord)
                  <tr id="{{$classRecord->id}}">

                    <th data-label="#" scope="row">

                      @if(!$classRecord->remark && $classRecord->generated_at->isToday())
                      <a target="_blank" class="btn btn-sm btn-success text-uppercase" href="https://zoom.us/j/{{ $classRecord->teacher->teacher->zoom->pmi }}">Zoom</a>
                      @endif

                      <a class="btn btn-sm btn-{{$classRecord->remark?'success':'warning'}} text-uppercase" href="{{ route('classRecords.'.($classRecord->remark?'show':'edit'), $classRecord->id) }}">Evaluation</a>
                      <a class="btn btn-sm btn-{{$classRecord->getFirstMedia('mp3')?'success':'warning'}} text-uppercase" href="{{ route('classRecords.'.($classRecord->getFirstMedia('mp3')?'show':'edit'), $classRecord->id) }}">Mp3</a>
                      <a class="btn btn-sm btn-{{$classRecord->getFirstMedia('mp4')?'success':'warning'}} text-uppercase" href="{{ route('classRecords.'.($classRecord->getFirstMedia('mp4')?'show':'edit'), $classRecord->id) }}">Mp4</a>
                          
                    </th>
                    <td data-label="Student">{{$classRecord->user->name}}</td>
                    <td data-label="ClassAt">{{$classRecord->generated_at->format('F j H:i D')}}</td>
                    <td class="exception" data-label="exception">
                      {{\App\Models\ClassRecord::EXCEPTION_TYPES_EN[$classRecord->exception]}}
                    </td>

                    <td data-label="Flag">
                      @if(!$classRecord->remark && $classRecord->exception!=4)
                        @if($classRecord->generated_at->diffInDays(Carbon\Carbon::now())<7)
                        <a  data-type="aol" data-exception="1" label="AOL" title="Click to AOL" class="post-action btn btn-{{$classRecord->exception==1?'warning':'outline-danger'}} btn-sm" href="{{ route('classRecords.flagException',[$classRecord->id, 1]) }}">AOL</a>
                        <a data-type="absent" data-exception="3" label="Absent" title="Click to Absent" class="post-action btn btn-{{$classRecord->exception==3?'warning':'outline-danger'}} btn-sm" href="{{ route('classRecords.flagException',[$classRecord->id, 3]) }}">Absent</a>
                          <a data-type="aol2" data-exception="2" label="Holidays" title="public holidays" class="post-action btn btn-{{$classRecord->exception==2?'warning':'outline-danger'}} btn-sm" href="{{ route('classRecords.flagException',[$classRecord->id, 2]) }}">Holidays</a>
                        @endif
                      @else
                      --
                      @endif
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



@section('scripts')
  @include('classRecords.aol-script')
@endsection
