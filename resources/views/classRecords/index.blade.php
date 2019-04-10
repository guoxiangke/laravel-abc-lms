@extends('layouts.app')

@section('title', __('ClassRecords'))

@section('content')
<div class="container">
  <h1>{{__('ClassRecords')}} {{isset($roleName)? ' - ' . $userName : ''}}</h1>
  
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
                @if(!isset($roleName))
                <th scope="col">order_id</th>
                @endif
              </tr>
            </thead>
            <tbody>
              @foreach($classRecords as $classRecord)
                  <tr>
                    <th scope="row">
                      
                      <a class="btn btn-sm btn-info text-uppercase" href="{{ route('classRecords.show', $classRecord->id) }}">{{$classRecord->id}}</a>
                      @if(isset($roleName))
                          @if($roleName === 'teacher')
                            @if(!$classRecord->remark)
                              <a class="btn btn-sm btn-warning text-uppercase" href="{{ route('classRecords.edit', $classRecord->id) }}">!Evaluation</a>
                              <a class="btn btn-sm btn-primary text-uppercase" href="https://zoom.us/j/{{ $classRecord->teacherModel->zoom->pmi }}">Zoom</a>
                            @endif

                            @if(!$mp3Url = $classRecord->getFirstMediaUrl('mp3'))
                            <a class="btn btn-sm btn-warning text-uppercase" href="{{ route('classRecords.edit', $classRecord->id) }}">!Mp3</a>
                            @endif

                            @if(!$mp4Url = $classRecord->getFirstMediaUrl('mp4'))
                            <a class="btn btn-sm btn-warning text-uppercase" href="{{ route('classRecords.edit', $classRecord->id) }}">!Mp4</a>
                            @endif

                          @endif
                          
                        @if($roleName === 'student')
                          @if($classRecord->remark)
                            <a class="btn btn-sm btn-warning text-uppercase" href="{{ route('classRecords.show', $classRecord->id) }}">课程回顾</a>
                          @else
                          <a class="btn btn-sm btn-primary text-uppercase" href="https://zoom.us/j/{{ $classRecord->teacherModel->zoom->pmi }}">加入课堂</a>
                          @endif
                        @elseif($roleName === 'agency')
                         <a class="btn btn-sm btn-warning text-uppercase" href="{{ route('classRecords.show', $classRecord->id) }}">查看课程</a>
                        @endif
                      @else
                      <a class="btn btn-sm btn-outline-dark text-uppercase" href="{{ route('classRecords.show', $classRecord->id) }}">
                        View
                      </a>
                      <a class="btn btn-sm btn-outline-dark text-uppercase" href="{{ route('classRecords.edit', $classRecord->id) }}">
                        Edit
                      </a>
                      @endif
                    </th>
                    <td data-label="Teacher">{{$classRecord->user->profiles->first()->name}}</td>
                    <td data-label="student">{{$classRecord->teacher->profiles->first()->name}}</td>
                    <td data-label="agency">{{$classRecord->agency->profiles->first()->name}}</td>
                    <td data-label="ClassAt">{{$classRecord->generated_at->format('n/j H:i 周N')}}</td>
                    <td data-label="exception">{{\App\Models\ClassRecord::EXCEPTION_TYPES[$classRecord->exception]}}
                    </td>
                    @if(!isset($roleName))
                    <td data-label="Order">
                      <a href="{{ route('classRecords.show',$classRecord->id) }}">
                        {{$classRecord->rrule->order->title}}
                      </a>
                    </td>
                    @endif
                  </tr>
              @endforeach
            </tbody>
        </table>
      </div>
      {{ $classRecords->onEachSide(1)->links() }}
  </div>
</div>
@endsection
