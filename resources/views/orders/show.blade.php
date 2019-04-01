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
    <button type="button" class="btn btn-outline-primary"><a href="{{ route('orders.index') }}">Go Back</a></button>
    <br>
    <br>
<div class="table-responsive">
  <table class="table">
      <thead>
        <tr>
            <th scope="col">#</th>
            <th scope="col">Student</th>
            <th scope="col">Teacher</th>
            <th scope="col">Agency</th>
            <th scope="col">Period</th>
            <th scope="col">已上(含今天)</th>
            <th scope="col">老师请假</th>
            <th scope="col">学生请假</th>
            <th scope="col">旷课作废</th>
            <th scope="col">老师异常</th>
            <th scope="col">ExpireAt</th>
            <th scope="col">Book</th>
            <th scope="col">Procut</th>
            <th scope="col">Price</th>
        </tr>
      </thead>
      <tbody>
            <tr>
              <td data-label="#">
                <a target="_blank" href="{{ route('classRecords.index') }}">RecordsByOrder</a>
              </td>
              <td data-label="Student">{{$order->user->profile->name}}</td>
              <td data-label="Teacher">{{$order->teacher->profile->name}}</td>
              <td data-label="Agency">{{$order->agency->profile->name}}</td>
              <td data-label="Period">{{$order->period}}</td>
              <td data-label="已上(含今天)">{{$order->classDoneRecords()->count()}}</td>
              <td data-label="老师请假">{{$order->classRecordsAolBy('teacher')->count()}}</td>
              <td data-label="学生请假">{{$order->classRecordsAolBy('student')->count()}}</td>
              <td data-label="旷课作废">{{$order->classRecordsAolBy('absent')->count()}}</td>
              <td data-label="老师异常">{{$order->classRecordsAolBy('exception')->count()}}</td>
              <td data-label="ExpireAt">{{$order->expired_at->format('y/m/d')}}</td>
              <td data-label="Book">《{{$order->book->name}}》</td>
              <td data-label="Price">{{$order->product->name}}</td>
              <td data-label="Price">{{$order->price}}</td>
            </tr>
      </tbody>
    </table>
</div>

<abc-calendar></abc-calendar>
</div>
@endsection
