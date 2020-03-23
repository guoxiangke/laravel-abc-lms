@extends('sb-admin2.app')
@section('content')
<div class="container">
    {!! $chart->container() !!}
</div>
@endsection

@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.1/Chart.min.js" charset="utf-8"></script>
{!! $chart->script() !!}
@endsection
