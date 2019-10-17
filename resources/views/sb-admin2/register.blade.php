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

  <title>{{ __('Register') }}</title>

  <!-- Scripts -->
  <script src="{{ asset('js/app.js') }}"></script>
  <!-- Styles -->
  <link href="{{ asset('css/app.css') }}" rel="stylesheet">
  <link href="/vendor/sb-admin2/sb-admin-2.min.css" rel="stylesheet">
  <style>
    .btn-toggle {
      margin: 0 4rem;
      padding: 0;
      position: relative;
      border: none;
      height: 1.5rem;
      width: 3rem;
      border-radius: 1.5rem;
      color: #6b7381;
      background: #bdc1c8;
    }
    .btn-toggle:focus,
    .btn-toggle.focus,
    .btn-toggle:focus.active,
    .btn-toggle.focus.active {
      outline: none;
    }
    .btn-toggle:before,
    .btn-toggle:after {
      line-height: 1.5rem;
      width: 4rem;
      text-align: center;
      font-weight: 600;
      font-size: 0.75rem;
      text-transform: uppercase;
      letter-spacing: 2px;
      position: absolute;
      bottom: 0;
      transition: opacity 0.25s;
    }
    .btn-toggle:before {
      content: "{{ __('Male') }}";
      left: -4rem;
    }
    .btn-toggle:after {
      content: "{{ __('Female') }}";
      right: -4rem;
      opacity: 0.5;
    }
    .btn-toggle > .handle {
      position: absolute;
      top: 0.1875rem;
      left: 0.1875rem;
      width: 1.125rem;
      height: 1.125rem;
      border-radius: 1.125rem;
      background: #fff;
      transition: left 0.25s;
    }
    .btn-toggle.active {
      transition: background-color 0.25s;
    }
    .btn-toggle.active > .handle {
      left: 1.6875rem;
      transition: left 0.25s;
    }
    .btn-toggle.active:before {
      opacity: 0.5;
    }
    .btn-toggle.active:after {
      opacity: 1;
    }
    .btn-toggle.btn-sm:before,
    .btn-toggle.btn-sm:after {
      line-height: -0.5rem;
      color: #fff;
      letter-spacing: 0.75px;
      left: 0.4125rem;
      width: 2.325rem;
    }
    .btn-toggle.btn-sm:before {
      text-align: right;
    }
    .btn-toggle.btn-sm:after {
      text-align: left;
      opacity: 0;
    }
    .btn-toggle.btn-sm.active:before {
      opacity: 0;
    }
    .btn-toggle.btn-sm.active:after {
      opacity: 1;
    }
    .btn-toggle.btn-xs:before,
    .btn-toggle.btn-xs:after {
      display: none;
    }
    .btn-toggle:before,
    .btn-toggle:after {
      color: #6b7381;
    }
    .btn-toggle.active {
      background-color: #4e73df;
    }
    .btn-toggle.btn-secondary {
      color: #6b7381;
      background: #bdc1c8;
    }
    .btn-toggle.btn-secondary:before,
    .btn-toggle.btn-secondary:after {
      color: #6b7381;
    }
    .btn-toggle.btn-secondary.active {
      background-color: #4e73df;
    }
  </style>
</head>
<body class="bg-gradient-primary">

  <div class="container">

    <div class="card o-hidden border-0 shadow-lg my-5">
      <div class="card-body p-0">
        <!-- Nested Row within Card Body -->
        <div class="row">
          <div class="col-lg-5 d-none d-lg-block bg-register-image-replace"></div>
          <div class="col-lg-7">
            <div class="p-5">
              <div class="text-center">
                <h1 class="h4 text-gray-900 mb-4">{{ __('Register') }}</h1>
              </div>

              <div class="errors">
                <ul class="bg-danger text-white shadow">
                @foreach($errors->all() as $error)
                  <li>{{$error}}</li>
                @endforeach
                </ul>
              </div>
              <form method="POST" class="user" action="{{ route('register') }}">
                @csrf
                <div class="form-group row">
                  <div class="col-sm-6 mb-3 mb-sm-0">
                    <input type="text" name="name" value="{{ old('name') }}" class="form-control form-control-user{{ $errors->has('name') ? ' is-invalid' : '' }}" id="name"  required autofocus placeholder="{{ __('Your Name') }}">
                  </div>
                  <div class="col-sm-6 m-auto">
                    <label for="sex" class="col-form-label d-inline">{{ __('Sex') }}</label>
                    <button id="switchSex" type="button" class="btn btn-toggle active" data-toggle="button" aria-pressed="true" autocomplete="off">
                      <div class="handle"></div>
                    </button>
                    <input type="number" value="0"  id="sex" name="sex" hidden>
                  </div>
                </div>

                <div class="form-group row">
                  <div class="col-sm-6 mb-3 mb-sm-0">
                    <input id="birthday" type="date" class="form-control form-control-user{{ $errors->has('birthday') ? ' is-invalid' : '' }}" name="birthday" value="{{ old('birthday') }}" required autofocus placeholder="{{ __('Birthday') }}">
                  </div>
                  <div class="col-sm-6">
                    <input id="telephone" type="tel" class="form-control form-control-user{{ $errors->has('telephone') ? ' is-invalid' : '' }}" name="telephone-ui" required autofocus value="{{ old('telephone-ui') }}"  placeholder="{{ __('Telephone') }}">
                    <input type="hidden" id="telephone-with-dial-code" name="telephone" required value="{{ old('telephone') }}"/>
                  </div>
                </div>

                <div class="form-group">
                  <input id="email" type="email" class="form-control form-control-user{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" required autofocus placeholder="{{ __('E-Mail Address') }}">
                </div>

                <div class="form-group row">
                  <div class="col-sm-6 mb-3 mb-sm-0">
                    <input id="password" type="password" class="form-control form-control-user{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" required autofocus placeholder="{{ __('Password') }}">
                  </div>
                  <div class="col-sm-6">
                    <input id="password-confirm" type="password" class="form-control form-control-user" name="password_confirmation" required placeholder="{{ __('Confirm Password') }}">
                    
                  </div>
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
                      ><span style="font-size: .5em;"><-{{ __('Captcha Refresh') }}</span>
                  </div>
                  <div class="col-sm-6">
                    <input type="text" class="form-control form-control-user{{ $errors->has('captcha') ? ' is-invalid' : '' }}" required autofocus autocomplete="off" name="captcha" placeholder="{{__('Addition Result')}}" placeholder="Captcha">
                  </div>
                </div>
                <input type="hidden" name="recommend_uid" required value="{{isset($uid)?$uid:1}}"/>
                <button type="submit" class="btn btn-primary btn-user btn-block">
                        {{ __('Register') }}
                </button>
              </form>
              <hr>
              <div class="text-center">
                <a class="small" href="/password/reset">{{ __('Forgot Your Password?') }}</a>
              </div>
              <div class="text-center">
                <a class="small" href="/login">{{ __('Already have an account? Login!') }}</a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

  </div>

  <!-- Custom scripts for all pages-->
  <script src="/vendor/sb-admin2/sb-admin-2.min.js"></script>
  
  <link href="{{ asset('vendor/telephone-input/css/intlTelInput.min.css') }}" rel="stylesheet">
  <style>
    .intl-tel-input{width: 100%;}
    .intl-tel-input.allow-dropdown .selected-flag, .intl-tel-input.separate-dial-code .selected-flag{
        width: 100px;
    }
    .intl-tel-input.separate-dial-code .selected-flag{
      background: none;
    }
    .intl-tel-input.separate-dial-code .selected-dial-code{
      font-size: .5em;
    }
    .intl-tel-input.allow-dropdown .selected-flag, .intl-tel-input.separate-dial-code .selected-flag{
      width: 79px;
    }
    .intl-tel-input .selected-flag .iti-flag{
      margin-left: 4px;
    }
    .bg-register-image-replace {
        background: url(/images/6df31f5a-b4fb-4a42-9d8e-2e2a0f38fe77.png);
        background-position: center;
        background-size: cover;
    }
  </style>
  <script src="{{ asset('vendor/telephone-input/js/intlTelInput.min.js') }}"></script>
  <script>
      window.onload = function () {
          var input = document.querySelector("#telephone");
          window.intlTelInput(input, {
            initialCountry: "cn",
            onlyCountries: ['cn', 'gb', 'us', 'ca', 'ph'],
            separateDialCode: true,
          });

          $('form').submit(function(){
              $('#telephone-with-dial-code').val(
                  $('.selected-dial-code').html() + $('#telephone').val()
              );
          });


          $('#captcha').on('click',function(){
              var captcha = $(this);
              var url = "/captcha/" + captcha.data('captcha-config') + '/?' + Math.random();
              captcha.attr('src',url);
          });

          $('#switchSex').on('click', function(){
            if($('#sex').val()==0){
              $('#sex').val(1);
            }else{
              $('#sex').val(0);
            }
          })

      }
  </script>
</body>
</html>