@extends('layouts.app')

@section('title', $order->title)

@section('scripts')
<script src="{{ asset('js/app.js') }}" defer></script>
<script>
    var orderId =  "{{ 'Order_' . $order->id }}";
    var default_events = @json($events);
</script>
@endsection

@section('content')
<div class="container">
  <h1>{{$order->title}}</h1>

  <div class="show-links">
      <a href="{{route('orders.index') }}" class="btn btn-outline-dark"><i class="fas fa-angle-left fa-large"></i> {{__('Go Back')}}</a>
      <a href="{{route('classRecords.indexbyOrder', $order) }}" class="btn btn-outline-dark">View in ClassRecords</a>
  </div>

  <ul class="list-group list-group-flush">
      @foreach($order->rrules as $rrule)
        @php
        switch ($rrule->status) {
            case 1:
                $bg = 'bg-success';
                break;
            case 0:
                $bg = 'bg-warning';
                break;
            case 2:
                $bg = 'bg-danger';
                break;
            default:
                break;
        }
        switch ($rrule->type) {
            case 1: //上课计划
                $type = '上课';
                break;
            case 0: //请假计划
                $type = '请假';
                break;
        }
        @endphp
        <li class="list-group-item  border-primary {{$bg}}">规则状态: {{\App\Models\Rrule::STATUS[$rrule->status]}}</li>
        <li class="list-group-item">规则时段: {{$rrule->start_at->format('Y.m.d')}} ～ {{$order->expired_at->format('Y.m.d')}}</li>
        <li class="list-group-item">{{$type}}时间: {{$rrule->start_at->format('H:i')}}</li>
        <li class="list-group-item">{{$type}}计划：{{$rrule->toText()}}
          <a href="{{route('rrules.edit', $rrule->id) }}" class="btn btn-sm btn-outline-danger">Edit</a>   
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
