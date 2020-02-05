@extends('layouts.app')

@section('title', __('Videos'))

@section('content')
<div class="container">
	<h1>Videos</h1>
  <div class="show-links">
    <a href="{{ route('home') }}" class="btn btn-outline-dark"><i class="fas fa-angle-left fa-large"></i> {{__('Go Back')}}</a>
    <a href="{{ route('videos.create') }}" class="btn btn-outline-primary">{{__('Create')}}</a>
    <button class="btn btn-light">本页记录数量：{{count($videos)}}</button>
  </div>

    <div class="col-md-12 col-sm-12 p-0"> 
        <div class="table-responsive">
          <table class="table">
              <thead>
                <tr>
                	<th scope="col">Order</th>
                	<th scope="col">Druing</th>
                	<th scope="col">Share/Status</th>
                	<th scope="col">path</th>
                  <th scope="col">Cutter</th>
                </tr>
              </thead>
              <tbody>
                @foreach($videos as $video)
                    <tr id={{$video->id}}>
                      <td data-label="classRecord" class="text-left"><a href="{{route('orders.show',$video->classRecord->order->id)}}"> {{$video->classRecord->order->title}}</a></td>
                      <td data-label="start_time"><a target="_blank" href="{{route('videos.cut',$video->classRecord->id)}}">{{$video->start_time}}-{{$video->end_time}}</a></td>
                      <td data-label="Share">
                        @if($video->deleted_at)
                        Inactive
                        @else
                          <a target="_blank" class="btn btn-sm btn-success" href="/videos/{{ $video->hashid() }}">Share</a>
                        @endif
  
                      </td>
                      <td data-label="path"><a href="{{route('classRecords.show',$video->class_record_id)}}">{{$video->path}}</a></td>
                      <td data-label="Share"> {{$video->user->getShowName()}} </td>
                    </tr>
                @endforeach
              </tbody>
          </table>
        </div>
        {{ $videos->onEachSide(1)->links() }}
    </div>
</div>
@endsection
