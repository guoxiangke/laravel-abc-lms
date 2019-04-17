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
                <th scope="col">Flag</th>
              </tr>
            </thead>
            <tbody>
              @foreach($classRecords as $classRecord)
                  <tr id="{{$classRecord->id}}">
                    <th scope="row">
                      @if(!$classRecord->remark && $classRecord->generated_at->isToday())
                        <a class="btn btn-sm btn-success text-uppercase" href="https://zoom.us/j/{{ $classRecord->teacher->teacher->zoom->pmi }}">Zoom</a>
                      @endif

                      <a class="btn btn-sm btn-{{$classRecord->remark?'success':'warning'}} text-uppercase" href="{{ route('classRecords.show', $classRecord->id) }}">Evaluation</a>
                      <a class="btn btn-sm btn-{{$classRecord->getFirstMedia('mp3')?'success':'warning'}} text-uppercase" href="{{ route('classRecords.show', $classRecord->id) }}">Mp3</a>
                      <a class="btn btn-sm btn-{{$classRecord->getFirstMedia('mp4')?'success':'warning'}} text-uppercase" href="{{ route('classRecords.show', $classRecord->id) }}">Mp4</a>
                          
                    </th>
                    <td data-label="Student">{{$classRecord->user->profiles->first()->name}}</td>
                    <td data-label="老师">{{$classRecord->teacher->profiles->first()->name}}</td>
                    <td data-label="ClassAt">{{$classRecord->generated_at->format('m/d H:i 周N')}}</td>
                    <td data-label="exception"  class="exception">{{\App\Models\ClassRecord::EXCEPTION_TYPES[$classRecord->exception]}}
                    </td>
                    <td data-label="Flag">
                      <a class="post-action btn btn-{{$classRecord->exception==1?'warning':'outline-danger'}} btn-sm" href="{{ route('classRecords.flagAOL',$classRecord->id) }}">AOL</a>
                      

                      <a class="post-action btn btn-{{$classRecord->exception==3?'warning':'outline-danger'}} btn-sm" href="{{ route('classRecords.flagAbsent',$classRecord->id) }}">Absent</a>

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
<script>
    window.onload = function () {
        $('.post-action').click(function(e){
          e.preventDefault();
          if (confirm("This action cannot be undone, Are you sure to flag?")) {
            var that = $(this);
            var actions = that.parent('td')
            var statusText = that.text();
            var target = actions.parent('tr').find('.exception');
             $.ajax({
              type:"GET",
              url:that.attr('href'),
              success: function(data) {
                if(data.success){
                  target.text(statusText);
                  that.removeClass('btn-outline-danger').addClass('btn-warning');
                  //that.fadeOut(5000);
                  //actions.text('')
                }
              },
            });
          }


        });
    }
</script>
@endsection
