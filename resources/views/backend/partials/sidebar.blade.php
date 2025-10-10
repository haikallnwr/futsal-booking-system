<div id="sidebar" class="active">
    <div class="sidebar-wrapper active">
        <div class="sidebar-header position-relative">
            <div class="d-flex justify-content-between align-items-center">
                <div class="logo">
                    <a href="/"><img src="{{ asset('assets/img/logo/logo.svg') }}" alt="Logo" srcset=""></a>
                </div>
                
                <div class="sidebar-toggler  x">
                    <a href="#" class="sidebar-hide d-xl-none d-block"><i class="bi bi-x bi-middle"></i></a>
                </div>
            </div>
        </div>
        <div class="sidebar-menu">
            <ul class="menu">
                @if(Auth::user()->role_id == 1) {{-- DEVELOPER --}}
                    <li class="sidebar-title">Developer</li>
                    <li class="sidebar-item {{ Request::is('dashboarddev') ? 'active' : '' }}">
                        <a href="{{ route('dev.index') }}" class="sidebar-link">
                            <i class="bi bi-grid-fill"></i>
                            <span>Dashboard</span>
                        </a>
                    </li>
                    <li class="sidebar-item {{ Request::is('dashboarddev/contact*') ? 'active' : '' }}">
                        <a href="{{ route('dev.contact.index') }}" class='sidebar-link'>
                            <i class="bi bi-envelope-fill"></i>
                            <span>Contact</span>
                        </a>
                    </li>
                    <li class="sidebar-item {{ Request::is('dashboarddev/users*') ? 'active' : '' }}">
                        <a href="{{ route('dev.users.index') }}" class='sidebar-link'>
                            <i class="bi bi-people-fill"></i>
                            <span>Users</span>
                        </a>
                    </li>
                    <li class="sidebar-item {{ Request::is('dashboarddev/gors*') && !Request::is('dashboarddev/gors/*/fields*') ? 'active' : '' }}">
                        <a href="{{ route('dev.gors.index') }}" class='sidebar-link'>
                            <i class="bi bi-shop"></i>
                            <span>Gor</span>
                        </a>
                    </li>
                    {{-- Lapangan bisa diakses via GOR, jadi link utama sidebar mungkin tidak perlu --}}
                    {{-- <li class="sidebar-item {{ Request::is('dashboarddev/gors/*/fields*') ? 'active' : '' }}">
                         <a href="#" class='sidebar-link disabled-link'>
                            <i class="bi bi-dribbble"></i>
                            <span>Lapangan</span>
                        </a>
                    </li> --}}
                    <li class="sidebar-item {{ Request::is('dashboarddev/schedules*') ? 'active' : '' }}">
                        <a href="{{ route('dev.schedules.index') }}" class='sidebar-link'>
                            <i class="bi bi-calendar-week-fill"></i>
                            <span>Schedules</span>
                        </a>
                    </li>
                    <li class="sidebar-item {{ Request::is('dashboarddev/orders*') ? 'active' : '' }}">
                        <a href="{{ route('dev.orders.index') }}" class='sidebar-link'>
                            <i class="bi bi-cart-check-fill"></i>
                            <span>Orders</span>
                        </a>
                    </li>

                @elseif(Auth::user()->role_id == 2) {{-- ADMIN GOR --}}
                    <li class="sidebar-title">Admin GOR Menu</li>
                    <li class="sidebar-item {{ Request::is('dashboardadmin') ? 'active' : '' }}">
                        <a href="{{ route('admin.dashboard') }}" class="sidebar-link"> {{-- Asumsi nama rute dashboard admin adalah 'admin.dashboard' --}}
                            <i class="bi bi-grid-fill"></i>
                            <span>Dashboard</span>
                        </a>
                    </li>
                    <li class="sidebar-item {{ Request::is('dashboardadmin/gor*') ? 'active' : '' }}">
                        <a href="{{ route('admin.gor.edit') }}" class='sidebar-link'>
                            <i class="bi bi-shop-window"></i>
                            <span>My GOR Details</span>
                        </a>
                    </li>
                    <li class="sidebar-item {{ Request::is('dashboardadmin/fields*') ? 'active' : '' }}">
                        <a href="{{ route('admin.fields.index') }}" class='sidebar-link'>
                            <i class="bi bi-dribbble"></i>
                            <span>My Fields</span>
                        </a>
                    </li>
                    <li class="sidebar-item {{ Request::is('dashboardadmin/orders*') ? 'active' : '' }}">
                        <a href="{{ route('admin.orders.index') }}" class='sidebar-link'>
                            <i class="bi bi-journal-text"></i>
                            <span>Bookings</span>
                        </a>
                    </li>
                    <li class="sidebar-item {{ Request::is('dashboardadmin/schedules*') ? 'active' : '' }}">
                        <a href="{{ route('admin.schedules.index') }}" class='sidebar-link'>
                            <i class="bi bi-calendar3-week"></i>
                            <span>My Schedules</span>
                        </a>
                    </li>
                @endif

                <hr>
                <li class="sidebar-item">
                    <a href="/" class='sidebar-link'>
                        <i class="bi bi-house-door-fill"></i>
                        <span>Kunjungi Website</span>
                    </a>
                </li>
                 <li class="sidebar-item">
                    <form action="/logout" method="post" id="logout-form-sidebar">
                         @csrf
                        <a href="#" onclick="document.getElementById('logout-form-sidebar').submit(); return false;" class='sidebar-link'>
                            <i class="bi bi-box-arrow-right"></i>
                            <span>Logout</span>
                        </a>
                    </form>
                </li>
            </ul>
        </div>
    </div>
    </div>
</div>
