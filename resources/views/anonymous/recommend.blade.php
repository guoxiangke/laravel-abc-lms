<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
  <meta name="description" content="">
  <meta name="author" content="">

  <title>【福利】免费领青少儿英语原版动画、儿歌，一对一外教体验课</title>

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
    .bg-gradient-warning {
      background-color:#FFF683;
      background-image: none;

      position: relative;
      height: 100%;
    }
    .img-section{
      max-width: 100%;
    }
    .bottomText {
      position: absolute;
      width: 100%;
      z-index: 20;
      text-align: center;
      color: #333;
      bottom: 0.2rem;
    }
    .bottom {
        z-index: 1;
        bottom: 0rem;
    }
    .qrcodeCard {
        background-image: url(/images/recommend/qrcode_card.png);
        background-repeat: no-repeat;
        background-size: cover;
    }
    .qrcodeSection{
      padding: 15px;
    }
    .courseQRCodeImage{
      position: relative;    
      width: 50%;
      left: 150px;
      top: 4px;
    }
  </style>
</head>

<body class="bg-gradient-warning">

  <div class="container-fluid">

    <!-- Outer Row -->
    <div class="row justify-content-center pt-4">
      
      <img class="img-section section1 pb-2" src="/images/recommend/section1.png">
      <img class="img-section section2" src="/images/recommend/section2.png">
      <div class="btns pt-4" style="display: block;">
        <img class="img-section section3" src="/images/recommend/section3.png">

        <img class="img-section btn btn1 imgEvent" data-qr="monika" coursetype="SPEAKING" src="/images/recommend/btn1.png">
        <img class="img-section btn btn2 imgEvent" data-qr="anna" coursetype="SONG" src="/images/recommend/btn2.png">
        
      </div>
      <div class="qrcodeCard" id="qrcodeCard" style="display: none;">
        <div class="qrcodeSection flexCenter">
          <img class="courseQRCodeImage imgEvent" id="courseQRCodeImage" src="/images/monika.jpeg">
        </div>
      </div>

      <img class="img-section bottom" src="/images/recommend/bottom.png">
      <img class="img-section bottomRepick imgEvent" src="/images/recommend/repick.png" style="display: none;">
      <div class="bottomText">名额有限，仅限20分钟内领取</div>

    </div>

  </div>
  <script>
      window.onload = function () {
          $('.imgEvent').on('click',function(){
              var that = $(this);
              that.hide();
              var url = "/images/" + that.data('qr') + '.jpeg';
              $('#courseQRCodeImage').attr('src',url);
              $('#qrcodeCard').show();
          });
      }
  </script>
</body>

</html>