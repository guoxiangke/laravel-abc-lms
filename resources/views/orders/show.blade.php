@extends('layouts.app')

@section('title', $order->title)

@section('scripts')
<script>
    var orderId =  "{{ 'Order_' . $order->id }}";
    var default_events = @json($events);
</script>
@endsection

@section('content')
<div class="container-fluid">
  <h1>{{$order->title}}</h1>

  <div class="show-links">
      <a href="{{ route('orders.index') }}" class="btn btn-outline-dark"><i class="fas fa-angle-left fa-large"></i> {{__('Go Back')}}</a>
  </div>
  <div class="table-responsive">
    <table class="table">
        <thead>
          <tr>
              <th>#</th>
              <th scope="col">总课时</th>
              <th scope="col">已上(含今天)</th>
              <th scope="col">老师请假</th>
              <th scope="col">学生请假</th>
              <th scope="col">旷课作废</th>
              <th scope="col">老师异常</th>
              <th scope="col">过期时间</th>
              <th scope="col">教材</th>
          </tr>
        </thead>
        <tbody>
              <tr>
                <td data-label="#">
                  <a target="_blank" href="{{ route('classRecords.index') }}">{{$order->id}}</a>
                </td>
                <td data-label="Period">{{$order->period}}</td>
                <td data-label="已上(含今天)">{{$order->classDoneRecords()->count()}}</td>
                <td data-label="老师请假">{{$order->classRecordsAolBy('teacher')->count()}}</td>
                <td data-label="学生请假">{{$order->classRecordsAolBy('student')->count()}}</td>
                <td data-label="旷课作废">{{$order->classRecordsAolBy('absent')->count()}}</td>
                <td data-label="老师异常">{{$order->classRecordsAolBy('exception')->count()}}</td>
                <td data-label="过期时间">{{$order->expired_at->format('Y.m.d')}}</td>
                <td data-label="教材">{{$order->book->name}}</td>
              </tr>
        </tbody>
      </table>
  </div>

  <abc-calendar></abc-calendar>
</div>
@endsection
