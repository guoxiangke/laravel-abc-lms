@extends('sb-admin2.app')

@section('title', __('ClassRecords'))

@section('content')
<div class="container">
  <h1 class="h3 mb-0 text-gray-800 mb-2"><i class="fas fa-fw fa-book-reader"></i> {{__('ClassRecords')}}</h1>
  <div class="show-links">
    @php
    $filters = Request::get('filter');
    $exception = -1;
    if(isset($filters['exception'])){
      $exception = $filters['exception'];
      if($exception == 0) {
        $exception = -2;
      }
    }
    @endphp
    <a href="{{ url()->current() }}" class="btn btn-{{$exception==-1?'':'outline-'}}primary mt-1 text-capitalize">ALL</a>
    <a href="{{ url()->current() }}?filter[exception]=0" class="btn btn-{{$exception==-2?'':'outline-'}}primary mt-1 text-capitalize">正常</a>
    <a href="{{ url()->current() }}?filter[exception]=3" class="btn btn-{{$exception==3?'':'outline-'}}primary mt-1 text-capitalize">旷课</a>
    <a href="{{ url()->current() }}?filter[exception]=1,2,4" class="btn btn-{{$exception==1?'':'outline-'}}primary mt-1 text-capitalize">请假</a>
    <button class="btn btn-light">本页记录数量：{{count($classRecords)}}</button>
  </div>
  
  <div class="col-md-12 col-sm-12 p-0">
      <div class="table-responsive">
        <table class="table">
            <thead>
              <tr>
                <th scope="col">学生</th>
                <th scope="col">上课时间</th>
              	<th scope="col">课程状态</th>
                <th>#</th>
              </tr>
            </thead>
            <tbody>
              @foreach($classRecords as $classRecord)
                  <tr id="{{$classRecord->id}}">
                    <td data-label="学生">{{$classRecord->user->profiles->first()->name}}</td>
                    <td data-label="上课时间">{{$classRecord->generated_at->format('m/d H:i 周N')}}</td>
                    <td data-label="课程状态">{{\App\Models\ClassRecord::EXCEPTION_TYPES[$classRecord->exception]}}
                    </td>

                    <th scope="row">
                      <a class="btn btn-sm text-uppercase btn-outline-dark" href="{{ route('classRecords.show', $classRecord->id) }}">查看</a>
                    </th>
                  </tr>
              @endforeach
            </tbody>
        </table>
      </div>
      {{ $classRecords->onEachSide(1)->appends(request()->input())->links() }}
  </div>
</div>
@endsection
