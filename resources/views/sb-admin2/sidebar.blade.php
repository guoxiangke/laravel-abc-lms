<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion @role('teacher') toggled @endrole @can('admin') toggled @endcan" id="accordionSidebar">

  <!-- Sidebar - Brand -->
  <a class="sidebar-brand d-flex align-items-center justify-content-center" href="/home">
    <div class="sidebar-brand-icon rotate-n-15">
      <i class="fas fa-graduation-cap"></i>
    </div>
    <div class="sidebar-brand-text mx-3">{{ config('app.name', 'Laravel') }}</div>
  </a>

  <!-- Divider -->
  <hr class="sidebar-divider my-0">

  <!-- Nav Item - Dashboard -->
<li class="nav-item {{url()->current()==route('home')?'active':''}}">
  <a class="nav-link" href="/home">
    <i class="fas fa-fw fa-tachometer-alt"></i>
    <span>{{__('Dashboard')}}</span>
  </a>
</li>


  @hasanyrole('agency|student|teacher')       
    @include('sb-admin2/menu/user')
  @endrole

  @can('admin')
    @include('sb-admin2/menu/admin')
  @endcan
  <!-- Divider -->
  <hr class="sidebar-divider d-none d-md-block">

  <!-- Sidebar Toggler (Sidebar) -->
  <div class="text-center d-none d-md-inline">
    <button class="rounded-circle border-0" id="sidebarToggle"></button>
  </div>

</ul>