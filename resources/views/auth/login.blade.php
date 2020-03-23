@extends('sb-admin2.app')

@section('title', __('Login'))

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Login') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <div class="form-group row border-bottom">
                            <div class="col-md-12  col-lg-8 pb-3 mx-auto">
                                <a class="btn-block btn btn-lg btn-success" href="{{route('login.weixin')}}"><i class="fab fa-weixin fa-large"></i>
                                    {{ __('Login with Wechat') }}
                                </a>
                                <a class="btn-block btn btn-lg btn-primary" href="{{route('login.facebook')}}"><i class="fab fa-facebook fa-large"></i>
                                    {{ __('Login with Facebook') }}
                                </a>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="email" class="col-sm-4 col-form-label text-md-right">{{ __('LoginName') }}</label>
                            <div class="col-md-6">
                                <input id="username" type="text" class="form-control{{ $errors->has('username') ? ' is-invalid' : '' }}" name="username" value="{{ old('username') }}" required autofocus >

                                @if ($errors->has('username'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('username') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Password') }}</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" required>

                                @if ($errors->has('password'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="email" class="col-sm-4 col-form-label text-md-right">{{ __('Captcha') }}</label>
                            <div class="col-md-6">
                                <img 
                                    id="captcha"
                                    src="{{ captcha_src() }}" 
                                    alt="{{ __('Captcha') }}" 
                                    title="{{__('Captcha Refrsh')}}"
                                    border="0" 
                                    data-captcha-config="default"
                                >
                                <input 
                                    type="text" 
                                    class="form-control{{ $errors->has('captcha') ? ' is-invalid' : '' }}" 
                                    required autofocus autocomplete="off"
                                    name="captcha" 
                                    placeholder="{{__('Addition Result')}}">
                                @if ($errors->has('captcha'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('captcha') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-6 offset-md-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                                    <span class="form-check-label pr-4" for="remember">
                                        {{ __('Remember Me') }}
                                    </span>
                                    <span>
                                        @if (Route::has('password.request'))
                                            <a class="inline" href="{{ route('password.request') }}">
                                                {{ __('Forgot Your Password?') }}
                                            </a>
                                        @endif
                                </span>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row mb-0 mx-auto  pb-3">
                            <button type="submit" class="btn btn-lg btn-primary col-md-12  col-lg-8 mx-auto" >
                                    {{ __('Login') }}
                                </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@guest
    @section('scripts')
        @include('captcha')
    @endsection
@endguest

