<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.html">
        <div class="sidebar-brand-icon rotate-n-15">
            <i class="fas fa-door-open"></i>
        </div>
        <div class="sidebar-brand-text mx-3">Space Now</div>
    </a>

    <!-- Divider -->
    <hr class="sidebar-divider my-0">

    <!-- Nav Item - Dashboard -->
    <li class="nav-item {{ Request::is('/') ? 'active' : '' }}">
        <a class="nav-link" href="/">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dashboard</span></a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Heading -->
    <div class="sidebar-heading">
        Interface
    </div>

    <!-- Nav Item - Pages Collapse Menu -->
    @if($user && $user->role === 'admin')
    <li class="nav-item {{ Request::is('floor*') || Request::is('room*') || Request::is('users*') ? 'active' : '' }}">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTwo"
            aria-expanded="true" aria-controls="collapseTwo">
            <i class="fas fa-fw fa-cog"></i>
            <span>Master Data</span>
        </a>
        <div id="collapseTwo" class="collapse {{ Request::is('floor*') || Request::is('room*') || Request::is('users*') ? 'show' : '' }}"
            aria-labelledby="headingTwo" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Data</h6>
                <a class="collapse-item {{ Request::is('users*') ? 'active' : '' }}" href="/users">User</a>
                <a class="collapse-item {{ Request::is('floor*') ? 'active' : '' }}" href="/floor">Floor</a>
                <a class="collapse-item {{ Request::is('room*') ? 'active' : '' }}" href="/room">Room</a>
            </div>
        </div>
    </li>
    @endif

    <!-- Nav Item - Utilities Collapse Menu -->
    <li class="nav-item">
        <a class="nav-link collapsed {{ request()->is('booking') || request()->is('booking-history') || request()->is('approval') ? 'active' : '' }}"
        href="#" 
        data-toggle="collapse" 
        data-target="#collapseUtilities"
        aria-expanded="{{ request()->is('booking') || request()->is('booking-history') || request()->is('approval') ? 'true' : 'false' }}"
        aria-controls="collapseUtilities">
            <i class="fas fa-fw fa-building"></i>
            <span>Room Book</span>
        </a>
        <div id="collapseUtilities" 
            class="collapse {{ request()->is('booking') || request()->is('booking-history') || request()->is('approval') ? 'show' : '' }}"
            aria-labelledby="headingUtilities"
            data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Room Book:</h6>
                <a class="collapse-item {{ request()->is('booking') ? 'active' : '' }}" href="/booking">Bookings</a>
                <a class="collapse-item {{ request()->is('booking-history') ? 'active' : '' }}" href="/booking-history">History</a>
                @if($user && $user->role === 'admin')
                <a class="collapse-item {{ request()->is('approval') ? 'active' : '' }}" href="/approval">Approval</a>
                @endif
            </div>
        </div>
    </li>


    <!-- Divider -->
    <hr class="sidebar-divider">

  
    <!-- Sidebar Toggler (Sidebar) -->
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>


</ul>