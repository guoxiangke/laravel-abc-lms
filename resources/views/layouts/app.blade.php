<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    @if(config('app.env') === 'productions')
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-129889764-2"></script>
    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());

      gtag('config', 'UA-129889764-2');
    </script>
    @endif
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, minimal-ui">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
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
    <script src="{{ asset('js/app.js') }}" defer></script>
    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    @yield('styles')
    <link rel="icon" href="{{ asset('favicon.png') }}" sizes="32x32">
    <link rel="icon" href="{{ asset('favicon.png') }}" sizes="192x192">
    <link rel="apple-touch-icon-precomposed" href="{{ asset('favicon.png') }}">
    <meta name="msapplication-TileImage" content="{{ asset('favicon.png') }}">
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-expand-xl navbar-light  navbar-togglable navbar-laravel">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    {{ config('app.name', 'Laravel') }}
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav mr-auto">
                            <li class="nav-item">
                                @auth
                                    <a class="nav-link" href="{{ route('home') }}">{{ __('Home') }}</a>
                                @endauth
                                
                            </li>
                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ml-auto">
                        <!-- Authentication Links -->
                        @guest
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                            </li>
                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                                </li>
                            @endif
                        @else
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }} <span class="caret"></span>
                                </a>

                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                        @hasanyrole('manager|admin')
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    Switch <span class="caret"></span>
                                </a>
                                
                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                    @foreach( \App\User::where('id','!=',auth()->user()->id)->get() as $user)
                                    <a class="dropdown-item" href="{{ route('sudo.su', $user->id) }}">
                                        {{$user->name}}
                                    </a>
                                    @endforeach
                                </div>
                            </li>
                        @else
                        @endhasanyrole
                    </ul>
                </div>
            </div>
        </nav>

        <main class="py-4">
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
            
            @yield('content')
        </main>
    </div>
    @include('sweetalert::alert')
    @yield('scripts')
    <script type="text/javascript">
        window.onload = function () {
            $('.close').on('click',function(){
                $(this).parents('.close-it').slideUp('slowly');
            });
            setTimeout(function(){ $('.close-it').slideUp('slowly')}, 5000);
        }
    </script>
</body>
</html>
