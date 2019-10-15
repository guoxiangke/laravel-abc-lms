@extends('layouts.app')

@section('title', __('Bills'))

@section('content')
<div class="container">
	<h1 class="h3 mb-0 text-gray-800"><i class="fab fa-fw fa-cc-visa"></i>{{__('Bills')}}</h1>
  <div class="show-links">
    <a href="{{ route('bills.create') }}" class="btn btn-outline-primary">{{__('Create')}}</a>
  </div>
    <div class="col-md-12 col-sm-12 p-0"> 
        <div class="table-responsive">
          <table class="table">
              <thead>
                <tr>
                	<th scope="col">#</th>
                	<th scope="col">type</th>
                	<th scope="col">user</th>
                	<th scope="col">price</th>
                  <th scope="col">Date</th>
                  <th scope="col">paymethod</th>
                  <th scope="col">status</th>
                  <th scope="col">remark</th>
                </tr>
              </thead>
              <tbody>
                @foreach($bills as $bill)
                    <tr id={{$bill->id}}>
                      <th scope="row"><a href="{{ route('bills.edit', $bill->id) }}" class="btn btn-sm btn-outline-dark text-uppercase">Edit</a></th>
                      <td data-label="type">
                        <a href="{{ route('bills.show', $bill->id) }}">
                          {{App\Models\Bill::TYPES[$bill->type]}}
                        </a>
                      </td>
                      <td data-label="user">
                        @php
                        $profile = $bill->user->profiles->first();
                        @endphp
                        @if($bill->order)
                        <a href="{{ route('orders.edit', $bill->order->id) }}" class="btn btn-sm btn-outline-dark text-uppercase">
                        {{$profile?$profile->name:"-1-"}}
                        </a>
                        @else
                        {{$profile?$profile->name:"-1-"}}
                        @endif
                      </td>
                      <td data-label="price">{{App\Models\Bill::CURRENCIES[$bill->currency]}}{{$bill->price}}</td>
                      <td data-label="Date">{{$bill->created_at->format('md')}}</td>
                      <td data-label="paymethod">{{App\Models\PayMethod::TYPES[$bill->paymethod_type]}}</td>
                      <td data-label="status">{{App\Models\Bill::STATUS[$bill->status]}}</td>
                      <td data-label="remark">{{$bill->remark}}</td>
                    </tr>
                @endforeach
              </tbody>
          </table>
        </div>
        {{ $bills->onEachSide(1)->links() }}
    </div>
</div>
@endsection
