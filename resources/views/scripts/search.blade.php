@section('scripts1')
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
