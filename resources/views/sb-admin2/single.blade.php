<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">
  <!-- CSRF Token -->
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <title>
      @if(isset($title)) {{ $title }}
      @else
        @yield('title')
      @endif
      | {{ config('app.name', 'Laravel') }}
  </title>
  
    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}"></script>
    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    @yield('styles')
    @yield('styles1')
    @yield('styles2')

  @env('production')
  <!-- Custom fonts for this template-->
  <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
  @endenv
  <!-- Custom styles for this template-->
  <link href="/vendor/sb-admin2/sb-admin-2.min.css" rel="stylesheet">

  <link rel="icon" href="{{ asset('favicon.png') }}" sizes="32x32">
  <link rel="icon" href="{{ asset('favicon.png') }}" sizes="192x192">
  <link rel="apple-touch-icon-precomposed" href="{{ asset('favicon.png') }}">
  <meta name="msapplication-TileImage" content="{{ asset('favicon.png') }}">
</head>

<body id="page-top"  class="bg-gradient-primary">

  <!-- Page Wrapper -->
  <div id="container">

    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">

      <!-- Main Content -->
      <div id="content">


        <div class="flash-message container"  role="alert">
          @foreach (['primary','secondary','success','danger','warning','info','light','dark'] as $msg)
            @if(Session::has('alert-' . $msg))
            <div class="row alert alert-{{ $msg }} close-it">
                    <div class="col-sm-11  col-md-11 col-lg-11 col-xl-11 col-11">
                        {{ Session::get('alert-' . $msg) }}
                        @if(session('alert-' . $msg . '-detail'))
                            <pre class="alert-pre border bg-light p-2"><code>{{ session('alert-' . $msg . '-detail') }}</code></pre>
                        @endif
                    </div>
                    <div class="col-sm-1  col-md-1 col-lg-1 col-xl-1 col-1">
                        <button type="button" class="close" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
            </div>
            @endif
          @endforeach
        </div>

        <!-- Begin Page Content -->
        @yield('content')
        <!-- /.container-fluid -->

      </div>
      <!-- End of Main Content -->

    </div>
    <!-- End of Content Wrapper -->

  </div>
  <!-- End of Page Wrapper -->

  <!-- Scroll to Top Button-->
  <a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
  </a>

@auth
  <!-- Logout Modal-->
  <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
          <button class="close" type="button" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
        <div class="modal-footer">
          <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
          <a class="btn btn-primary" href="{{ route('logout') }}" onclick="event.preventDefault();
                             document.getElementById('logout-form').submit();">{{ __('Logout') }}</a>
        </div>

        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
            @csrf
        </form>

      </div>
    </div>
  </div>
@endauth

    <script type="text/javascript">
        (function($) {
          $( document ).ready(function() {

            $('.submit-confirm').click(function(e){
              e.preventDefault();
              let msg = $(this).attr('data-confirm');
              msg = typeof(msg)=='undefined'?'Are you sure!':msg;
              if (confirm(msg)) {
                  $(this).parent('form').submit();
              }
            });
          });
        })(jQuery);
    </script>
  <!-- Bootstrap core JavaScript-->

  <!-- Custom scripts for all pages-->
  <script src="/vendor/sb-admin2/sb-admin-2.min.js"></script>
  
  @yield('scripts')
  @yield('script-su')
  @yield('scripts1')
  @yield('scripts2')
</body>

</html>