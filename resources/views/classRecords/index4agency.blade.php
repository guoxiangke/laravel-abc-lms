@extends('layouts.app')

@section('title', __('ClassRecords'))

@section('content')
<div class="container">
  <h1><img class="icon-img" src="{{asset('images/icons/37-512.png')}}" alt=""> {{__('ClassRecords')}}</h1>
  
  <div class="show-links">
      <a href="{{ route('home') }}" class="btn btn-outline-dark"><i class="fas fa-angle-left fa-large"></i> {{__('Go Back')}}</a>
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
      {{ $classRecords->onEachSide(1)->links() }}
  </div>
</div>
@endsection
