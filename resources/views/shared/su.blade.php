@can('admin')
<form class="d-none d-sm-inline-block form-inline mr-auto ml-md-3 my-2 my-md-0 mw-100 navbar-search">
	<div class="input-group">

	<select name="su" id="su" class="form-control bg-light border-0">
	    <option value="0"></option>
	    @foreach( \App\User::where('id','!=',auth()->user()->id)->get() as $user)
	    <option value="{{$user->id}}">{{$user->name}}</option>
	    @endforeach
	</select>
	</div>
</form>
@section('script-su')
<script>
(function($) {
    $( document ).ready(function() {
        $('#su').change(function(e){
            e.preventDefault();
            if($(this).val()) {
                return window.location = '/dev/su/' + $(this).val();
            }
            
        });
    });
})(jQuery);
</script>
@endsection
@endcan