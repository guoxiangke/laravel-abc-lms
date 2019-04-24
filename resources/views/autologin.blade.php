@extends('layouts.app')

@section('title', '免密登陆')

@php 
    $user = Auth::user();
    $link = route('login.weixin');
    //$avatar = $user->getFirstMedia('avatar');
    //if(!$avatar){
    //    $avatar = public_path('favicon.gif');
    //}else{
    //    $avatar = $avatar->getPath();
    //}
    //$avatar = public_path('images/icons/51-512.jpg');
    //$avatarString = file_get_contents($avatar);
    $logoString = file_get_contents(public_path('images/icons/34-512.jpg'));

    $path = storage_path('app/public/loginqr/');
    if(!file_exists($path)){
        mkdir($path);
    }
    //$qrFileName = $user->id . '.png';
    $qrFileName = 'default0424.png';
    $loginQrPath = $path . $qrFileName;
    $publicQrPath = asset('storage/loginqr/'.$qrFileName);

    if(!file_exists($loginQrPath)){
        QrCode::format('png')
                    ->mergeString($logoString,.15)
                    ->size(500)
                    ->margin(2)
                    ->generate($link, $loginQrPath);
    }
@endphp

@section('content')
<div class="container">
    <h1><img class="icon-img" src="{{asset('images/icons/internet_security_login_fingerprint_scan-512.png')}}" alt=""> 免密登陆</h1>
    <div class="show-links">
        <a href="{{ route('home') }}" class="btn btn-outline-dark"><i class="fas fa-angle-left fa-large"></i> {{__('Go Back')}}</a>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-12 col-sm-12"> 

            <div class="col-md-4 pt-5 text-center bg-light">
                <h5 class="display-5">微信专属登陆码</h5>
                <p class="lead">长按收藏，一键登陆</p>
                <img src="{{$publicQrPath}}"  width="260px" loading="lazy">
                    <br>
                <p>微信绑定后方可使用</p>
            </div>
        </div>
    </div>
</div>
@endsection
