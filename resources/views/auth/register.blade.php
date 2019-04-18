@extends('layouts.app')

@section('title', __('Register'))

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Register') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('register') }}">
                        @csrf

                        <div class="form-group row">
                            <label for="name" class="required col-md-4 col-form-label text-md-right">{{ __('Name') }}</label>

                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" name="name" value="{{ old('name') }}"  required autofocus>

                                @if ($errors->has('name'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="sex" class="required col-md-4 col-form-label text-md-right">{{ __('Sex') }}</label>

                            <div class="col-md-6">
                                <select id="sex" class="form-control{{ $errors->has('sex') ? ' is-invalid' : '' }}" name="sex" required autofocus  selected="{{ old('sex') }}">
                                    <option value="0" selected>{{ __('Female') }}</option>
                                    <option value="1">{{ __('Male') }}</option>
                                </select>


                                @if ($errors->has('profile_sex'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('profile_sex') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="birthday" class="required col-md-4 col-form-label text-md-right">{{ __('Birthday') }}</label>

                            <div class="col-md-6">
                                <input id="birthday" type="date" class="form-control{{ $errors->has('birthday') ? ' is-invalid' : '' }}" name="birthday" value="{{ old('birthday') }}" required autofocus>

                                @if ($errors->has('birthday'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('birthday') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="telephone" class="required col-md-4 col-form-label text-md-right">{{ __('Telephone') }}</label>
                            <div class="col-md-6">
                                <input id="telephone" type="tel" class="form-control{{ $errors->has('telephone') ? ' is-invalid' : '' }}" name="telephone-ui" required autofocus placeholder="Your phone number" value="{{ old('telephone-ui') }}">

                                <input type="hidden" id="telephone-with-dial-code" name="telephone" required value="{{ old('telephone') }}"/>
                            </div>
                        </div>
                        <div class="form-group row">
                            @if ($errors->has('telephone'))
                            <label for="telephone-error" class="invisible col-md-4 col-form-label text-md-right">telephone error</label>
                                <div class="col-md-6">
                                    <span class="invalid-feedback" style="display: block;" role="alert">
                                        <strong>{{ $errors->first('telephone') }}</strong>
                                    </span>
                                </div>
                            @endif
                        </div>

                        <div class="form-group row">
                            <label for="email" class="required col-md-4 col-form-label text-md-right">{{ __('E-Mail Address') }}</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" required placeholder="E-Mail">

                                @if ($errors->has('email'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password" class="required col-md-4 col-form-label text-md-right">{{ __('Password') }}</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" required >

                                @if ($errors->has('password'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password-confirm" class="required col-md-4 col-form-label text-md-right">{{ __('Confirm Password') }}</label>

                            <div class="col-md-6">
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required>
                            </div>
                        </div>


                        <div class="form-group row">
                            <div class="col-md-6 offset-md-4">
                                <img 
                                    id="captcha"
                                    src="{{ captcha_src() }}" 
                                    alt="验证码" 
                                    title="刷新"
                                    border="0" 
                                    data-captcha-config="default"
                                >
                                <input type="text" class="form-control{{ $errors->has('captcha') ? ' is-invalid' : '' }}" required autofocus autocomplete="off" name="captcha" placeholder="{{ __('Captcha') }}" placeholder="Captcha">
                            </div>
                            @if ($errors->has('captcha'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('captcha') }}</strong>
                                </span>
                            @endif
                        </div>

                        <input type="hidden" name="recommend_uid" required value="{{isset($uid)?$uid:1}}"/>

                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Register') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@include('captcha')

@section('styles')
<link href="{{ asset('vendor/telephone-input/css/intlTelInput.min.css') }}" rel="stylesheet">
<style>
    .intl-tel-input{width: 100%;}
    .intl-tel-input.allow-dropdown .selected-flag, .intl-tel-input.separate-dial-code .selected-flag{
        width: 100px;
    }
</style>
@endsection

@section('scripts')
<script src="{{ asset('vendor/telephone-input/js/intlTelInput.min.js') }}"></script>
<script>
    window.onload = function () {
        var input = document.querySelector("#telephone");
        window.intlTelInput(input, {
          // allowDropdown: false,
          // autoHideDialCode: false,
          // autoPlaceholder: "off",
          // dropdownContainer: document.body,
          // excludeCountries: ["us"],
          // formatOnDisplay: false,
          // geoIpLookup: function(callback) {
          //   $.get("http://ipinfo.io", function() {}, "jsonp").always(function(resp) {
          //     var countryCode = (resp && resp.country) ? resp.country : "";
          //     callback(countryCode);
          //   });
          // },
          // hiddenInput: "full_number",
          initialCountry: "cn",
          // localizedCountries: { 'de': 'Deutschland' },
          // nationalMode: false,
          onlyCountries: ['cn', 'gb', 'us', 'ca', 'ph'],
          // placeholderNumberType: "MOBILE",
          // preferredCountries: ['cn', 'jp'],
          separateDialCode: true,
          // utilsScript: "build/js/utils.js",
        });

        $('form').submit(function(){
            $('#telephone-with-dial-code').val(
                parseInt($('.selected-dial-code').html()) + $('#telephone').val()
            );
        });

    }
</script>
@endsection
