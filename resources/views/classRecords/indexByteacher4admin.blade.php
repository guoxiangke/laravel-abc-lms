@extends('sb-admin2.app')

@section('title', __('ClassRecords'))

@section('content')
<div class="container">
  <h1 class="h3 mb-0 text-gray-800"><i class="fas fa-fw fa-book-reader"></i> {{__('ClassRecords')}}</h1>
  <br>
  
  <div class="col-md-12 col-sm-12 p-0">
      <div class="row mb-2">        
        <div class="col-xl-3 col-lg-6">
          <div class="card card-stats mb-4 mb-xl-0">
            <div class="card-body text-success">
              <div class="row">
                <div class="col">
                  <h5 class="card-title text-uppercase mb-0">{{ $counts['month'] }}月份</h5>
                  <span class="h2 font-weight-bold mb-0">{{$counts['total']}}</span>
                </div>
                <div class="col-auto">
                  <div class="icon icon-shape bg-success text-white rounded-circle shadow">
                    <i class="fas fa-chart-pie"></i>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-xl-3 col-lg-6">
          <div class="card card-stats mb-4 mb-xl-0">
            <div class="card-body text-danger">
              <div class="row">
                <div class="col">
                  <h5 class="card-title mb-0">Absent</h5>
                  <span class="h2 font-weight-bold mb-0 ">{{$counts['absent']}}</span>
                </div>
                <div class="col-auto">
                  <div class="icon icon-shape bg-danger text-white rounded-circle shadow">
                    <i class="fas fa-chart-bar"></i>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-xl-3 col-lg-6">
          <div class="card card-stats mb-4 mb-xl-0">
            <div class="card-body  text-success">
              <div class="row">
                <div class="col">
                  <h5 class="card-title mb-0">AOL | Holiday</h5>
                  <span class="h2 font-weight-bold mb-0">{{$counts['aol']}} | {{$counts['holiday']}}</span>
                </div>
                <div class="col-auto">
                  <div class="icon icon-shape bg-success text-white rounded-circle shadow">
                    <i class="fas fa-chart-bar"></i>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-xl-3 col-lg-6">
          <div class="card card-stats mb-4 mb-xl-0">
            <div class="card-body text-danger">
              <div class="row">
                <div class="col">
                  <h5 class="card-title mb-0">Exception</h5>
                  <span class="h2 font-weight-bold mb-0">{{$counts['exception']}}</span>
                </div>
                <div class="col-auto">
                  <div class="icon icon-shape bg-danger text-white rounded-circle shadow">
                    <i class="fas fa-chart-bar"></i>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="card shadow">
        <div class="card-header border-0">
          <div class="row align-items-center">
            <div class="col">
              <h5 class="mb-0">ClassRecords of {{$teacher->user->profiles->first()->name}}</h5>
            </div>
            <div class="col text-right">
              <button class="btn btn-light mt-1">本页记录数量：{{count($classRecords)}}</button>
            </div>
          </div>
        </div>
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
                        <a target="_blank" class="btn btn-sm btn-success text-uppercase" href="https://zhumu.me/j/{{ $classRecord->teacher->teacher->pmi }}">Zoom</a>
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
                          <a  data-type="aol" data-exception="1" label="AOL" title="Click to AOL" class="post-action btn btn-{{$classRecord->exception==1?'warning':'outline-danger'}} btn-sm" href="{{ route('classRecords.flagException',[$classRecord->id, 1]) }}">AOL</a>
                          <a data-type="absent" data-exception="3" label="Absent" title="Click to Absent" class="post-action btn btn-{{$classRecord->exception==3?'warning':'outline-danger'}} btn-sm" href="{{ route('classRecords.flagException',[$classRecord->id, 3]) }}">Absent</a>
                          <a data-type="aol2" data-exception="2" label="Holidays" title="public holidays" class="post-action btn btn-{{$classRecord->exception==2?'warning':'outline-danger'}} btn-sm" href="{{ route('classRecords.flagException',[$classRecord->id, 2]) }}">Holidays</a>
                        @else
                        --
                        @endif
                      </td>
                    </tr>
                @endforeach
              </tbody>
          </table>
        </div>
      </div>
      {{ $classRecords->onEachSide(1)->links() }}
  </div>
</div>
@endsection

@section('styles')
<style>
.icon-shape {
    padding: 12px;
    text-align: center;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%
}

.icon-shape i,.icon-shape svg {
    font-size: 1.25rem
}
</style>
@endsection

@section('scripts')
  @include('classRecords.aol-script')
@endsection
