<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

  <!-- Sidebar - Brand -->
  <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{ route('dashboard') }}">
    <div class="sidebar-brand-icon rotate-n-0">
    <i class="fas fa-hotel"></i>
    </div>
    <div class="sidebar-brand-text mx-3">{{ Config::get('app.name') }}</div>
  </a>

  <!-- Divider -->
  <hr class="sidebar-divider my-0">

  <li class="nav-item <?=($segment1=='dashboard')?'active':''?>">
    <a class="nav-link" href="{{ route('dashboard') }}">
      <i class="fas fa-fw fa-tachometer-alt"></i>
      <span>Dashboard</span></a>
  </li>

  <li class="nav-item <?=($segment1=='users')?'active':''?>">
    <a class="nav-link" href="{{ route('customers-index') }}">
      <i class="fas fa-fw fa-user-tie"></i>
      <span>Customers</span></a>
  </li>
  
  <li class="nav-item <?=($segment1=='reviews')?'active':''?>">
    <a class="nav-link" href="{{ route('reviews-index') }}">
      <i class="fas fa-fw fa-star"></i>
      <span>Reviews</span></a>
  </li>

  


  <!-- Divider -->
  <hr class="sidebar-divider d-none d-md-block">

  <!-- Sidebar Toggler (Sidebar) -->
  <div class="text-center d-none d-md-inline">
    <button class="rounded-circle border-0" id="sidebarToggle"></button>
  </div>

</ul>