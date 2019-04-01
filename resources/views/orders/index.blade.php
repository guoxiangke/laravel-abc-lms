@extends('layouts.app')

@section('title', 'Orders')

@section('content')
<div class="container">
	<h1>Orders</h1>
  
  <button type="button" class="btn btn-outline-primary"><a href="{{ route('orders.create') }}">Create</a></button>

  <div class="col-md-12 col-sm-12"> 
      <div class="table-responsive">
        <table class="table">
            <thead>
              <tr>
              	<th scope="col">#</th>
                <th scope="col">Student</th>
              	<th scope="col">Teacher</th>
              	<th scope="col">Agency</th>
              	<th scope="col">Book</th>
                <th scope="col">Procut</th>
                <th scope="col">Price</th>
                <th scope="col">Period</th>
                <th scope="col">已上</th>
                <th scope="col">ExpireAt</th>
                <th scope="col">Calendar</th>
                <th scope="col">ClassRecords/todo</th>
                <th scope="col">Action</th>
              </tr>
            </thead>
            <tbody>
              @foreach($orders as $order)
                  <tr>
                    <th scope="row">{{$order->id}}</th>
                    <td data-label="Student">{{$order->user->profile->name}}</td>
                    <td data-label="Teacher">{{$order->teacher->profile->name}}</td>
                    <td data-label="Agency">{{$order->agency->profile->name}}</td>
                    <td data-label="Book">《{{$order->book->name}}》</td>
                    <td data-label="Price">{{$order->product->name}}</td>
                    <td data-label="Price">{{$order->price}}</td>
                    <td data-label="Period">{{$order->period}}</td>
                    <td data-label="已上">{{$order->classDoneRecords()->count()}}</td>
                    <td data-label="ExpireAt">{{$order->expired_at->format('y/m/d')}}</td>
                    <td data-label="Calendar"><a href="{{ route('orders.show', $order->id) }}">Calendar</a></td>
                    <td data-label="Remark">
                      <a target="_blank" href="{{ route('classRecords.index') }}">classRecords</a>
                    </td>
                    <td data-label="Action"><a href="{{ route('orders.edit', $order->id) }}">Edit</a></td>
                  </tr>
              @endforeach
            </tbody>
          </table>
      </div>
      {{ $orders->onEachSide(1)->links() }}
  </div>
</div>
@endsection
