@extends('sb-admin2.app')

@section('title', __('Rrules'))

@section('content')
<div class="container">
	<h1>{{__('Rrules')}}</h1>
  
    <div class="show-links">
        <a href="{{ route('orders.index') }}" class="btn btn-outline-dark"><i class="fas fa-angle-left fa-large"></i> {{__('Go Back')}}</a>
        <button class="btn btn-light">本页记录数量：{{count($rrules)}}</button>
    </div>

    <div class="col-md-12 col-sm-12 p-0"> 
        <div class="table-responsive">
          <table class="table">
              <thead>
                <tr>
                	<th scope="col">#</th>
                	<th scope="col">order</th>
                	<th scope="col">type</th>
                	<th scope="col">start_at</th>
                  <th scope="col">string</th>
                </tr>
              </thead>
              <tbody>
                @foreach($rrules as $rrule)
                    <tr id={{$rrule->id}}>
                      <th scope="row">
                        <a href="{{ route('rrules.edit', $rrule->id) }}" class="btn btn-sm btn-outline-dark text-uppercase">Edit</a></th>
                      <td data-label="order">{{$rrule->order->title}}</td>
                      <td data-label="type">{{ \App\Models\Rrule::TYPES[$rrule->type] }}</td>
                      <td data-label="start_at">{{$rrule->start_at->format('m/d H:i')}}</td>
                      <td data-label="string">{{$rrule->toText()}}</td>
                    </tr>
                @endforeach
              </tbody>
          </table>
        </div>
        {{ $rrules->onEachSide(1)->links() }}
    </div>
</div>
@endsection
