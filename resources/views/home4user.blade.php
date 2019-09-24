<div class="container-fluid">

  <!-- Page Heading -->
  <div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">{{__('Dashboard')}}</h1>

    @hasanyrole('student|teacher|agency|manager|admin')
    @else
      <a href="/student/register" class="d-none d-sm-inline-block btn btn-sm btn-danger shadow-sm"><i class="fas fa-user fa-sm"></i> 登记学生年级</a>
    @endhasanyrole
    
  </div>

  <!-- Content Row -->
  <div class="row">

  </div>

  <!-- Content Row -->

  <div class="row">

   
    <div class="col-lg-12 mb-12">

      <!-- Illustrations -->
      <div class="card shadow mb-12">
        <div class="card-header py-3">
          <h6 class="m-0 font-weight-bold text-primary">您好，欢迎使用👏</h6>
        </div>
        <div class="card-body">
          <div class="text-center">
            <img class="img-fluid px-3 px-sm-4 mt-3 mb-4" style="width: 25rem;" src="/vendor/sb-admin2/undraw_posting_photo.svg" alt="">
          </div>
          <p>{{ Auth::user()->profiles->first()->name }}，您好，欢迎您使用云课堂LMS v2.0!</p>
          <p>您可以点击左侧的菜单（或顶部的三横线）进入不同选项。</p>

        @hasanyrole('student|teacher|agency|manager|admin')
        @else
        解锁左侧菜单&rarr;
        <a class="btn btn-danger" href="/student/register">
          <i class="fas fa-user fa-sm fa-fw mr-2"></i>
          登记学生年级
        </a>
        @endhasanyrole
        </div>
      </div>
    </div>
  </div>
</div>