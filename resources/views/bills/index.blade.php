@extends('sb-admin2.app')

@section('title', __('Bills'))

@section('content')
<div class="container">
	<h1 class="h3 mb-0 text-gray-800"><i class="fab fa-fw fa-cc-visa"></i>{{__('Bills')}}</h1>
  <div class="show-links">
    @php
      $filters = Request::get('filter');
      $status = null;
      if(isset($filters['status'])){
        $status = $filters['status'];
        if($status == 0) {
          $status = 2;
        }
      }

      
      $type = null;
      if(isset($filters['type'])){
        $type = $filters['type'];
        if($type == 0) {
          $type = 2;
        }
      }
      $isActive = false;
      if(is_null($type) && is_null($status)){
        $isActive = true;
      }
    @endphp
    
    <a href="{{ route('bills.index') }}" class="btn btn-{{$isActive?'':'outline-'}}primary">Index</a>
    <a href="{{ route('bills.index') }}?filter[type]=1" class="btn btn-{{$type==1?'':'outline-'}}primary">支出</a>
    <a href="{{ route('bills.index') }}?filter[type]=0" class="btn btn-{{$type==2?'':'outline-'}}primary">收入</a>
    <a href="{{ route('bills.index') }}?filter[status]=0" class="btn btn-{{$status==2?'':'outline-'}}primary">Append</a>
    <a href="{{ route('bills.create') }}" class="btn btn-outline-primary">{{__('Create')}}</a>
    <button class="btn btn-light mt-1">本页记录数量：{{count($bills)}}</button>
    @include('shared.search')

  </div>
    <div class="col-md-12 col-sm-12 p-0"> 
        <div class="table-responsive">
          <table class="table">
              <thead>
                <tr>
                	<th scope="col">#</th>
                	<th scope="col">type</th>
                	<th scope="col">user</th>
                  <th scope="col">method</th>
                	<th scope="col">price</th>
                  <th scope="col">Date</th>
                  <th scope="col">remark</th>
                </tr>
              </thead>
              <tbody>
                @foreach($bills as $bill)
                    <tr id={{$bill->id}}>
                      <th scope="row">
                        <a href="{{ route('bills.edit', $bill->id) }}" class="btn btn-sm btn-outline-dark text-uppercase">Edit</a>
                      </th>
                      <td data-label="type">
                        <a href="{{ route('bills.show', $bill->id) }}">
                        @if($bill->type == 1)
                        <i class="fab fa-cc-visa" title="{{App\Models\Bill::TYPES[$bill->type]}}"></i>
                        @else
                        <i class="fas fa-funnel-dollar"></i>
                        @endif
                        </a>
                        @if($bill->status ==1)
                        <i class="fas fa-check-square"></i>
                        @else
                        <i class="fas fa-times-circle"></i>
                        @endif
                      </td>
                      <td data-label="user">
                        @php
                          $profile = $bill->user->profiles->first();
                        @endphp

                        @if($bill->order)
                          <a href="{{ route('orders.edit', $bill->order->id) }}" class="btn btn-sm btn-outline-dark text-uppercase">
                          {{$profile?$profile->name:"--"}}
                          </a>
                        @else
                          {{$profile?$profile->name:"--"}}
                          @if($bill->user->isTeacher())
                            @if($bill->user->teacher->paymethod)
                            <a class="" target="_blank" href="https://www.paypal.com/myaccount/transfer/homepage/external/summary?recipient={{$bill->user->teacher->paymethod->number}}">
                            <i class="fab fa-paypal"></i>
                            </a>
                            @endif
                          @endif
                        @endif
                      </td>
                      <td data-label="paymethod">{{App\Models\PayMethod::TYPES[$bill->paymethod_type]}}</td>
                      <td data-label="price">{{App\Models\Bill::CURRENCIES[$bill->currency]}}{{$bill->price}}</td>
                      <td data-label="Date">{{$bill->created_at->format('md')}}</td>
                      <td data-label="remark"> <div class="remark">{{$bill->remark}}</div></td>
                    </tr>
                @endforeach
              </tbody>
          </table>
        </div>
        {{ $bills->onEachSide(1)->links() }}
    </div>
</div>
<style>
.remark {
    height: 50px;
    overflow-y: auto;
}
</style>
@endsection

@section('scripts')
<script type="text/javascript">
(function($) {
  $( document ).ready(function() {

    $('#reset').click(function(e){
    e.preventDefault();
    return window.location = window.location.pathname;
    });

    $('#studentName').keypress(function (e) {
    if (e.which == 13) {
      $('#search').trigger('click');
      return false;    //<---- Add this line
    }
    });


    $('#search').click(function(e){
    e.preventDefault();
    var regex = /[a-z]{2}/gi; //pinyin
    $input = $('#studentName').val();

    if(regex.test($input)){
      return window.location = window.location.pathname + "?filter[user.name]="+$input;
    }else{
      return window.location = window.location.pathname + "?filter[user.profiles.name]="+$input;
    }
    });
  });
})(jQuery);
</script>
@endsection