@extends('layouts.app')

@section('title', __('Referrals'))

@php 
    $user = Auth::user();
    $link = route('register.recommend',['user'=>$user]);
    //$avatar = $user->getFirstMedia('avatar');
    //if(!$avatar){
    //    $avatar = public_path('favicon.gif');
    //}else{
    //    $avatar = $avatar->getPath();
    //}
    $avatar = public_path('icons/51-512.jpg');
    $avatarString = file_get_contents($avatar);

    $path = storage_path('app/public/referrals/');
    if(!file_exists($path)){
        mkdir($path);
    }
    $qrFileName = $user->id . '.png';
    $loginQrPath = $path . $qrFileName;
    $publicQrPath = asset('storage/referrals/'.$qrFileName);

    if(!file_exists($loginQrPath)){
        QrCode::format('png')
                    ->mergeString($avatarString,.18)
                    ->size(300)
                    ->margin(2)
                    ->generate($link, $loginQrPath);
    }

@endphp

@section('content')
<div class="container">
    <h1><img width="30px" src="{{asset('icons/63-512.png')}}" alt=""> {{__('Referrals')}}</h1>
    <div class="show-links">
        <a href="{{ route('home') }}" class="btn btn-outline-dark"><i class="fas fa-angle-left fa-large"></i> {{__('Go Back')}}</a>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-12 col-sm-12"> 


            <div class="col-md-4 pt-5  text-center bg-light">
                <h5 class="display-5">微信专属推荐码</h5>
                <p class="lead">长按收藏，微信分享</p>
                <img src="{{$publicQrPath}}"  width="260px" loading="lazy">
                <p>推荐及优惠政策请询问课程顾问</p>
            </div>

        </div>
    </div>
</div>
@endsection
