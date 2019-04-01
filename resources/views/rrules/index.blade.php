@extends('layouts.app')

@section('title', 'Rrules')

@section('content')
<div class="container">
	<h1>Rrules</h1>

  <button type="button" class="btn btn-outline-primary"><a href="{{ route('rrules.create') }}">Create</a></button>

    <div class="col-md-12 col-sm-12"> 
        <div class="table-responsive">
          <table class="table">
              <thead>
                <tr>
                	<th scope="col">#</th>
                	<th scope="col">order</th>
                	<th scope="col">type</th>
                	<th scope="col">start_at</th>
                  <th scope="col">string</th>
                  <th scope="col">Action</th>
                </tr>
              </thead>
              <tbody>
                @foreach($rrules as $rrule)
                    <tr>
                      <th scope="row">{{$rrule->id}}</th>
                      <td data-label="order">{{$rrule->order->title}}</td>
                      <td data-label="type">{{ \App\Models\Rrule::TYPES[$rrule->type] }}</td>
                      <td data-label="start_at">{{$rrule->start_at->format('m/d H:i')}}</td>
                      <td data-label="string">{{$rrule->toText()}}</td>
                      <td data-label="Action">
                        <a href="{{ route('rrules.edit', $rrule->id) }}">Edit</a>
                      </td>
                    </tr>
                @endforeach
              </tbody>
          </table>
        </div>
        {{ $rrules->onEachSide(1)->links() }}
    </div>
</div>
@endsection
