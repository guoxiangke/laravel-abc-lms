@extends('sb-admin2.app')

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
    $avatar = public_path('images/icons/WechatIMG2.png');
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
                    ->size(500)
                    ->margin(2)
                    ->generate($link, $loginQrPath);
    }

@endphp

@section('content')
<div class="container">
    <h1 class="h3 mb-0 text-gray-800"><i class="fas fa-fw fa-share-alt"></i> {{__('Referrals')}}</h1>
    <br>

    <div class="row justify-content-center">
        <div class="col-md-12 col-sm-12"> 


            <div class="col-md-4 pt-5  text-center bg-light">
                <h5 class="display-5">微信专属推荐码</h5>
                <img src="{{$publicQrPath}}"  width="260px" loading="lazy">
                <p class="lead">长按收藏，微信分享</p>
                <p>推荐及优惠政策请询问课程顾问</p>
                <p>或复制分享以下地址给小伙伴<br/>{{$link}}</p>
            </div>

        </div>
    </div>
</div>
@endsection
