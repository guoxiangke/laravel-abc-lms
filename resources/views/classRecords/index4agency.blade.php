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
                <th scope="col">学生</th>
              	<th scope="col">老师</th>
                <th scope="col">上课时间</th>
              	<th scope="col">课程状态</th>
                @if(!isset($roleName))
                <th scope="col">order_id</th>
                @endif
              </tr>
            </thead>
            <tbody>
              @foreach($classRecords as $classRecord)
                  <tr>
                    <th scope="row">
                      <a class="btn btn-sm btn-info text-uppercase" href="{{ route('classRecords.show', $classRecord->id) }}">查看详情{{$classRecord->id}}</a>
                    </th>
                    <td data-label="学生">{{$classRecord->user->profiles->first()->name}}</td>
                    <td data-label="老师">{{$classRecord->teacher->profiles->first()->name}}</td>
                    <td data-label="上课时间">{{$classRecord->generated_at->format('m.d H:i')}}</td>
                    <td data-label="课程状态">{{\App\Models\ClassRecord::EXCEPTION_TYPES[$classRecord->exception]}}
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
