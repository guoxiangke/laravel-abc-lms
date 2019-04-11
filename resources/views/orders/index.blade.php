@extends('layouts.app')

@section('title', __('Orders'))

@section('content')
<div class="container">
	<h1>{{__('Orders')}}</h1>
  <div class="show-links">
      <a href="{{ route('home') }}" class="btn btn-outline-dark"><i class="fas fa-angle-left fa-large"></i> {{__('Go Back')}}</a>
      <a href="{{ route('orders.create') }}" class="btn btn-outline-primary">{{__('Create')}}</a>
  </div>

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
                <!-- <th scope="col">Product</th> -->
                <th scope="col">Price</th>
                <th scope="col">Period</th>
                <th scope="col">已上</th>
                <th scope="col">ExpireAt</th>
                <th scope="col">#</th>
              </tr>
            </thead>
            <tbody>
              @foreach($orders as $order)
                  <tr id={{$order->id}}>
                    <td data-label="#">
                      <a href="{{ route('orders.show', $order->id) }}" class="fas fa-calendar-alt fa-lg" alt="上课日历" title="上课日历"></a>


                      <a href="{{ route('classRecords.index') }}" class="fas fa-list fa-lg" alt="上课记录" title="上课记录"></a>
                      
                      <a href="{{ route('rrules.create', $order) }}" class="fas fa-calendar-times fa-lg" alt="创建计划" title="创建计划"></a>

                      <a href="{{ route('rrules.show', $order) }}" class="fas fa-calendar-check fa-lg" alt="Plans" title="Plans"></a>

                      <a href="{{ route('orders.edit', $order->id) }}"  class="fas fa-edit fa-lg"></a>
                    </td>
                    <td data-label="Student">{{$order->user->profiles->first()->name}}</td>
                    <td data-label="Teacher">{{$order->teacher->profiles->first()->name}}</td>
                    <td data-label="Agency">{{$order->agency->profiles->first()->name}}</td>
                    <td data-label="Book">{{$order->book->name}}</td>
                    <!-- <td data-label="Price">{{$order->product->name}}</td> -->
                    <td data-label="Price">{{$order->price}}</td>
                    <td data-label="Period">{{$order->period}}</td>
                    <td data-label="已上">{{$order->classDoneRecords()->count()}}</td>
                    <td data-label="ExpireAt">{{$order->expired_at->format('y/m/d')}}</td>
                  </tr>
              @endforeach
            </tbody>
          </table>
      </div>
      {{ $orders->onEachSide(1)->links() }}
  </div>
</div>
@endsection
