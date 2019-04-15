@extends('layouts.app')

@section('title', __('Home'))

@section('content')

<section class="section section-top section-full">

  
  <div class="bg-cover" style="background-image: url(./images/42.jpg);"></div>

  
  <div class="bg-overlay"></div>

  
  <div class="bg-triangle bg-triangle-light bg-triangle-bottom bg-triangle-left"></div>
  <div class="bg-triangle bg-triangle-light bg-triangle-bottom bg-triangle-right"></div>

  
  <div class="container">
    <div class="row justify-content-center align-items-center">
      <div class="col-md-8 col-lg-7">

        
        <p class="font-weight-medium text-center text-lg  text-muted  text-uppercase text-white animate" data-toggle="animation" data-animation="fadeUp" data-animation-order="0" data-animation-trigger="load">
          课程记录随意回放
        </p>
        
        
        <h1 class="text-white text-center mb-4 animate" data-toggle="animation" data-animation="fadeUp" data-animation-order="1" data-animation-trigger="load">
          大象教育云课堂 LMS
        </h1>

        
        <p class="lead text-white  text-2lg   text-center mb-5 animate" data-toggle="animation" data-animation="fadeUp" data-animation-order="2" data-animation-trigger="load">
          立即成为会员，加入在线学习行列
        </p>

        
        <p class="text-center mb-0 animate" data-toggle="animation" data-animation="fadeUp" data-animation-order="3" data-animation-trigger="load">
          <a href="{{ route('login.weixin') }}" target="_blank" class="btn btn-success text-white">
            微信登陆
          </a>
        </p>

      </div>
    </div> 
  </div> 

</section>
@endsection

@section('styles')
<style>
    
.bg-slider {
    position: absolute;
    z-index: auto;
    top: 0;
    right: 0;
    bottom: 0;
    left: 0
}

.bg-slider .slider {
    width: 100%;
    height: 100%
}

.bg-slider .slider-item {
    width: inherit;
    height: inherit;
    padding: 0
}

.bg-slider .slider .flickity-viewport {
    height: 100%!important
}

.bg-video {
    position: absolute;
    top: 0;
    right: 0;
    bottom: 0;
    left: 0;
    overflow: hidden
}

.bg-video-media {
    position: absolute;
    top: 50%;
    left: 50%;
    min-width: 100%;
    min-height: 100%;
    transform: translate(-50%,-50%)
}

@supports not (-ms-ime-align:auto) {
    @supports ((-o-object-fit: cover) or (object-fit:cover)) {
        .bg-video-media {
            top:0;
            left: 0;
            width: 100%;
            min-width: none;
            height: 100%;
            min-height: none;
            transform: none;
            -o-object-fit: cover;
            object-fit: cover
        }
    }
}

.bg-cover {
    background-repeat: no-repeat;
    background-position: 50%;
    background-size: cover
}

.bg-cover,.bg-overlay,.bg-overlay:before {
    position: absolute;
    top: 0;
    right: 0;
    bottom: 0;
    left: 0
}

.bg-overlay:before {
    content: "";
    opacity: .55;
    background-color: #212529
}

.bg-triangle {
    position: absolute;
    top: 0;
    right: 0;
    bottom: 0;
    left: 0;
    background-repeat: no-repeat;
    background-position: 0 100%;
    background-size: 100% auto
}

.bg-triangle-left {
    background-image: url("data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' width='100' height='100'><path d='M0 100 V 90 L 10 100 Z' fill='%23212529' fill-opacity='0.03'></path><path d='M0 90 V 80 L 10 90 Z' fill='%23212529' fill-opacity='0.045'></path><path d='M10 100 V 90 L 20 100 Z' fill='%23212529' fill-opacity='0.025'></path><path d='M0 90 H 10 V 100 Z' fill='%23212529' fill-opacity='0.06'></path></svg>")
}

.bg-triangle-left.bg-triangle-light {
    background-image: url("data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' width='100' height='100'><path d='M0 100 V 90 L 10 100 Z' fill='white' fill-opacity='0.03'></path><path d='M0 90 V 80 L 10 90 Z' fill='white' fill-opacity='0.045'></path><path d='M10 100 V 90 L 20 100 Z' fill='white' fill-opacity='0.025'></path><path d='M0 90 H 10 V 100 Z' fill='white' fill-opacity='0.06'></path></svg>")
}

.bg-triangle-right {
    background-image: url("data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' width='100' height='100'><path d='M100 100 V 90 L 90 100 Z' fill='%23212529' fill-opacity='0.045'></path><path d='M100 90 V 80 L 90 90 Z' fill='%23212529' fill-opacity='0.015'></path><path d='M90 100 V 90 L 80 100 Z' fill='%23212529' fill-opacity='0.03'></path><path d='M90 100 V 90 H 100 Z' fill='%23212529' fill-opacity='0.06'></path></svg>")
}

.bg-triangle-right.bg-triangle-light {
    background-image: url("data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' width='100' height='100'><path d='M100 100 V 90 L 90 100 Z' fill='white' fill-opacity='0.045'></path><path d='M100 90 V 80 L 90 90 Z' fill='white' fill-opacity='0.015'></path><path d='M90 100 V 90 L 80 100 Z' fill='white' fill-opacity='0.03'></path><path d='M90 100 V 90 H 100 Z' fill='white' fill-opacity='0.06'></path></svg>")
}

.bg-triangle-top {
    transform: scaleY(-1)
}

.bg-incline {
    position: absolute;
    top: 0;
    right: 0;
    bottom: 0;
    left: 0;
    background-repeat: no-repeat;
    background-position: 100% 0,100% 0,calc(80% - 100vmax) top,calc(90% - 50vmax) top;
    background-size: 20% 100%,10% 100%,500vmax 500vmax,500vmax 500vmax
}

@media (-ms-high-contrast:none) {
    .bg-incline {
        background-size: 0 0
    }
}

.bg-incline-dark {
    background-image: linear-gradient(90deg,rgba(33,37,41,.015) 0,rgba(33,37,41,.015)),linear-gradient(90deg,rgba(33,37,41,.015) 0,rgba(33,37,41,.015)),linear-gradient(to top left,rgba(33,37,41,.015) 50%,transparent 0),linear-gradient(to top left,rgba(33,37,41,.015) 50%,transparent 0)
}

.bg-incline-light {
    background-image: linear-gradient(90deg,hsla(0,0%,100%,.05) 0,hsla(0,0%,100%,.05)),linear-gradient(90deg,hsla(0,0%,100%,.05) 0,hsla(0,0%,100%,.05)),linear-gradient(to top left,hsla(0,0%,100%,.05) 50%,transparent 0),linear-gradient(to top left,hsla(0,0%,100%,.05) 50%,transparent 0)
}

.bg-incline-left {
    transform: scaleX(-1)
}

.bg-incline-top {
    transform: scaleY(-1)
}

.bg-incline-top.bg-incline-left {
    transform: scale(-1)
}

.text-white.text-muted {
    color: hsla(0,0%,100%,.65)!important
}


.section .container {
    position: relative;
    z-index: 1
}

.section-top {
    padding-top: 10.8125rem
}

@media (min-width: 768px) {
    .section-top {
        padding-top:13.3125rem
    }
}



.text-xs {
    font-size: .75rem!important
}

.text-sm {
    font-size: .8125rem!important
}

.text-lg {
    font-size: 1rem!important
}
.text-2lg {
    font-size: 2rem!important
}

#app nav{
    position: fixed;
    top: 0;
    right: 0;
    left: 0;
    z-index: 1030;
}
#app nav .navbar-brand {
    color: #fff;
}
#app nav .navbar-nav .nav-link {
    color: rgba(255, 255, 255, 0.7);
}
.navbar-laravel{
    background: none;
    box-shadow: 0 2px 4px rgba(255, 255, 255, 0.1);
}
</style>
@endsection
