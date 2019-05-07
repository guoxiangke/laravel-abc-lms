@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12 col-sm-12">
          @foreach($socials as $social)
            
            Type: {{ App\Models\Social::TYPES[$social->type] }} <br>
            NickName: {{ $social->name }} <br>
            @if($social->avatar)
                <img src="{{ $social->avatar }}" alt="" width="35px;" > <br>
            @endif

            @can('delete', $social)
              <div class="mt-3  mb-1">
              {{ Form::open(['method' => 'DELETE', 'route' => ['socials.destroy', $social->id]]) }}
                  {{ Form::submit(__('Unbind'), ['class' => 'btn btn-sm btn-delete btn-danger']) }}
              {{ Form::close() }}
              </div>
            @endcan
            <hr>
          @endforeach
          
          {{ $socials->onEachSide(1)->links() }}
        </div>
    </div>
</div>
@endsection
@section('scripts')
<script>
    window.onload = function () {
        $('.btn-delete').click(function(e){
          e.preventDefault();
          if (confirm('Are you sure?')) {
              $(this).parent('form').submit();
          }
        });

    }
</script>
@endsection
