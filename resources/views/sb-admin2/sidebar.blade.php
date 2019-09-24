<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion @role('teacher') toggled @endrole @can('admin') toggled @endcan" id="accordionSidebar">

  <!-- Sidebar - Brand -->
  <a class="sidebar-brand d-flex align-items-center justify-content-center" href="/home">
    <div class="sidebar-brand-icon rotate-n-15">
      <i class="fas fa-graduation-cap"></i>
    </div>
    <div class="sidebar-brand-text mx-3">{{ config('app.name', 'Laravel') }} <sup>2</sup></div>
  </a>

  <!-- Divider -->
  <hr class="sidebar-divider my-0">

  @hasanyrole('agency|student|teacher')       
    @include('sb-admin2/menu4user')
  @endrole

  @can('admin')
    @include('sb-admin2/menu4admin')
  @endcan
  <!-- Divider -->
  <hr class="sidebar-divider d-none d-md-block">

  <!-- Sidebar Toggler (Sidebar) -->
  <div class="text-center d-none d-md-inline">
    <button class="rounded-circle border-0" id="sidebarToggle"></button>
  </div>

</ul>