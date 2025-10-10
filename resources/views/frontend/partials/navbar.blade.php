    <header id="header" class="fixed-top ">
        <div class="container d-flex align-items-center justify-content-lg-between">
            <div class="d-flex align-items-center">
            <a href="/" class="logo-link me-3" >
                <img src="{{ asset('assets/img/logo.png') }}" alt="Arena Futsal Logo" class="logo-img">
            </a>
            <div class="brand-text">
                <h1 class="brand-title mb-0">ARENA FUTSAL</h1>
                <h3 class="brand-subtitle mb-0">Jakarta</h3>
            </div>
        </div>         
            <nav id="navbar" class="navbar order-last order-lg-0">
    <ul>
        {{-- Tambahkan class 'active' secara dinamis --}}
        <li><a class="nav-link scrollto {{ request()->is('/') ? 'active' : '' }}" href="{{ url('/') }}">Beranda</a></li>
        <li><a class="nav-link scrollto {{ request()->is('gor*') ? 'active' : '' }}" href="{{ url('/gor') }}">Sewa Lapangan</a></li>
        <li><a class="nav-link scrollto {{ request()->is('contact') ? 'active' : '' }}" href="{{ route('contact.index') }}">Kontak</a></li>
        
        @auth
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle {{ request()->is('profile*') ? 'active' : '' }}" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    Hi, {{ auth()->user()->fullname }}
                </a>
                <ul class="dropdown-menu-end rounded-3">
                    @if (auth()->user()->role_id == 3)
                        <li><a class="dropdown-item" href="{{ url('/profile') }}"><i class="bi bi-person-circle"></i> Profil Saya</a></li>
                        <li><a class="dropdown-item" href="{{ url('/profile/orders') }}"><i class="bi bi-card-list"></i> Pesanan</a></li>
                        @elseif (auth()->user()->role_id === 2)
                        <li><a class="dropdown-item" href="{{ url('/dashboardadmin') }}"><i class="bi bi-layout-text-sidebar-reverse"></i> My Dashboard</a></li>
                    @elseif (auth()->user()->role_id === 1)
                        <li><a class="dropdown-item" href="{{ url('/dashboarddev') }}"><i class="bi bi-layout-text-sidebar-reverse"></i> My Dashboard</a></li>
                    @endif
                    </li>
                    <hr class="mt-0">
                    <li>
                        <form class="ms-4 pb-1" action="{{ url('/logout') }}" method="post">
                            @csrf
                            <button type="submit" class="dropdown-item"><i class="bi bi-box-arrow-right"></i> Logout</button>
                        </form>
                    </li>
                </ul>
            </li>
        @else
            <li><a class="nav-link scrollto {{ request()->is('login') ? 'active' : '' }}" href="{{ url('/login') }}">Login</a></li>
            <li><a class="nav-link scrollto {{ request()->is('register') ? 'active' : '' }}" href="{{ url('/register') }}">Register</a></li>
        @endauth
    </ul>
    <i class="bi bi-list mobile-nav-toggle"></i>
</nav><!-- .navbar -->
        </div>
    </header><!-- End Header -->
