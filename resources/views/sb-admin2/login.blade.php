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

  <title>{{ __('Login') }}</title>

  <!-- Scripts -->
  <script src="{{ asset('js/app.js') }}"></script>
  <!-- Styles -->
  <link href="{{ asset('css/app.css') }}" rel="stylesheet">
  <link href="/vendor/sb-admin2/sb-admin-2.min.css" rel="stylesheet">
  <style>
    .bg-login-image-replace {
        background: url(/images/6df31f5a-b4fb-4a42-9d8e-2e2a0f38fe77.png);
        background-position: center;
        background-size: cover;
    }
  </style>
</head>

<body class="bg-gradient-primary">

  <div class="container">

    <!-- Outer Row -->
    <div class="row justify-content-center">

      <div class="col-xl-10 col-lg-12 col-md-9">

        <div class="card o-hidden border-0 shadow-lg my-5">
          <div class="card-body p-0">
            <!-- Nested Row within Card Body -->
            <div class="row">
              <div class="col-lg-6 d-none d-lg-block bg-login-image-replace"></div>
              <div class="col-lg-6">
                <div class="p-5">
                  <div class="text-center">
                    <h1 class="h4 text-gray-900 mb-4">{{ __('Welcome Back!') }}</h1>
                  </div>
                  <div class="errors">
                    <ul class="bg-danger text-white shadow">
                    @foreach($errors->all() as $error)
                      <li>{{$error}}</li>
                    @endforeach
                    </ul>
                  </div>
                  <form class="user" method="POST" action="{{ route('login') }}">
                    @csrf
                    <div class="form-group">
                      <input id="username" type="text" class="form-control form-control-user{{ $errors->has('username') ? ' is-invalid' : '' }}" aria-describedby="emailHelp" placeholder="{{ __('LoginName') }}" name="username" value="{{ old('username') }}" required autofocus >
                    </div>
                    <div class="form-group">
                      <input id="password" type="password" class="form-control form-control-user{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" required placeholder="{{ __('Password') }}">
                    </div>
                    <div class="form-group row">

                      <div class="col-sm-6 mb-3 mb-sm-0 pl-4">
                        <img 
                              id="captcha"
                              src="{{ captcha_src() }}" 
                              alt="验证码" 
                              title="{{ __('Captcha Refresh') }}"
                              border="0" 
                              data-captcha-config="default"
                          >
                      </div>
                      <div class="col-sm-6">
                        <input type="text" class="form-control form-control-user{{ $errors->has('captcha') ? ' is-invalid' : '' }}" required autofocus autocomplete="off" name="captcha" placeholder="{{__('Addition Result')}}" placeholder="Captcha">
                      </div>
                    </div>
                    
                    <div class="form-group  pl-4">
                      <div class="custom-control custom-checkbox small">
                        <input type="checkbox" class="custom-control-input" id="customCheck" name="remember" {{ old('remember') ? 'checked' : '' }}>
                        <label class="custom-control-label" for="customCheck">{{ __('Remember Me') }}</label>
                      </div>
                    </div>

                    <button type="submit" class="btn btn-primary btn-user btn-block" >
                        {{ __('Login') }}
                    </button>
                    <hr>
                    <a href="{{route('login.weixin')}}" class="btn btn-success btn-user btn-block">
                     <i class="fab fa-weixin fa-large"></i> {{ __('Login with Wechat') }}
                    </a>
                    <a href="{{route('login.facebook')}}" class="btn btn-facebook btn-user btn-block">
                      <i class="fab fa-facebook-f fa-fw"></i> {{ __('Login with Facebook') }}
                    </a>
                  </form>
                  <hr>
                  <div class="text-center">
                    <a class="small" href="/password/reset">{{ __('Forgot Your Password?') }}</a>
                  </div>
                  <div class="text-center">
                    <a class="small" href="/register">{{ __('Create an Account!') }}</a>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

      </div>

    </div>

  </div>
@include('captcha')
</body>

</html>