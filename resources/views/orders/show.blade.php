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
      <a href="{{ route('orders.edit', $order->id)}}" class="btn btn-outline-primary">{{__('Edit')}}</a>
  </div>

  <ul class="list-group list-group-flush">
      @foreach($order->rrules as $rrule)
        <li class="list-group-item  border-primary">开始日期: {{$rrule->start_at->format('Y.m.d')}}</li>
        <li class="list-group-item">过期日期: {{$order->expired_at->format('Y.m.d')}}</li>
        <li class="list-group-item">上课时间: {{$rrule->start_at->format('H:i')}}</li>
        <li class="list-group-item">上课计划：{{$rrule->toText()}}
          <a href="{{route('classRecords.indexbyOrder', $order) }}" class="btn btn-sm btn-outline-dark">Class Records</a>   
        </li>
      @endforeach
  </ul>
  <div class="table-responsive">
    <table class="table">
        <thead>
          <tr>
              <th scope="col">总课时</th>
              <th scope="col">已上(含今天)</th>
              <th scope="col">老师请假</th>
              <th scope="col">学生请假</th>
              <th scope="col">旷课作废</th>
              <th scope="col">老师异常</th>
              <th scope="col">教材</th>
          </tr>
        </thead>
        <tbody>
              <tr id={{$order->id}}>
                <td data-label="Period">{{$order->period}}</td>
                <td data-label="已上(含今天)">{{$order->classDoneRecords()->count()}}</td>
                <td data-label="老师请假">{{$order->classRecordsAolBy('teacher')->count()}}</td>
                <td data-label="学生请假">{{$order->classRecordsAolBy('student')->count()}}</td>
                <td data-label="旷课作废">{{$order->classRecordsAolBy('absent')->count()}}</td>
                <td data-label="老师异常">{{$order->classRecordsAolBy('exception')->count()}}</td>
                <td data-label="教材">{{$order->book->publisher}} | {{$order->book->name}}</td>
              </tr>
        </tbody>
      </table>
  </div>

  <abc-calendar></abc-calendar>
</div>
@endsection
