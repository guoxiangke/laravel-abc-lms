@extends('layouts.app')

@section('title', __('ClassRecords'))

@section('content')
<div class="container">
  <h1><img width="30px" src="{{asset('icons/37-512.png')}}" alt=""> {{__('ClassRecords')}}</h1>
  
  <div class="show-links">
      <a href="{{ route('home') }}" class="btn btn-outline-dark"><i class="fas fa-angle-left fa-large"></i> {{__('Go Back')}}</a>
  </div>
  
  <div class="col-md-12 col-sm-12">
      <div class="table-responsive">
        <table class="table">
            <thead>
              <tr>
              	<th scope="col">#</th>
              	<th scope="col">老师</th>
                <th scope="col">上课时间</th>
              	<th scope="col">课程状态</th>
                <th scope="col">请假</th>
              </tr>
            </thead>
            <tbody>
              @foreach($classRecords as $classRecord)
                  <tr id={{$classRecord->id}}>
                    <th scope="row">
                      @if($classRecord->remark)
                        <a class="btn btn-sm btn-warning text-uppercase" href="{{ route('classRecords.show', $classRecord->id) }}">课程回顾</a>
                      @else
                        <a class="btn btn-sm btn-light text-uppercase" href="#">暂无评估</a>
                      @endif
                    </th>
                    <td data-label="老师">{{$classRecord->teacher->profiles->first()->name}}</td>
                    <td data-label="上课时间">{{$classRecord->generated_at->format('m.d H:i  周N')}}</td>
                    <td class="exception" data-label="课程状态">{{\App\Models\ClassRecord::EXCEPTION_TYPES_STU[$classRecord->exception]}}
                    </td>


                    <td data-label="Flag">
                      @if($classRecord->exception==0
                        && $aolCount < 2
                        && $classRecord->generated_at->isToday())
                        <a  data-type="aol" data-exception="1" label="请假" title="点击请假" class="post-action btn btn-{{$classRecord->exception==1?'warning':'outline-danger'}} btn-sm" href="{{ route('classRecords.flagException',[$classRecord->id, 1]) }}">请假</a>
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
