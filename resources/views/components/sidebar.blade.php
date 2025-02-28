@php
    use Illuminate\Support\Facades\Auth;
@endphp

<div class="sidebar bg-dark">
    <div class="d-flex flex-column h-100">
        <div class="sidebar-header p-3 text-center">
            <img src="{{ asset('images/Logo_Humas_Polri.svg.png') }}" alt="Humas Polri" class="sidebar-logo mb-3">
            <h5 class="text-white">HUMAS POLDA SULTRA</h5>
        </div>

        <div class="sidebar-menu flex-grow-1 px-3">
            <ul class="nav flex-column">
                <li class="nav-item mb-2">
                    <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                        <i class="bi bi-speedometer2 me-2"></i>
                        Dashboard
                    </a>
                </li>
                
                @if(auth()->user()->isAdmin())
                    {{-- <li class="nav-item mb-2">
                        <a class="nav-link {{ request()->routeIs('attendance.index') ? 'active' : '' }}" 
                           href="{{ route('attendance.index') }}">
                            <i class="bi bi-calendar-check me-2"></i>
                            Data Absensi
                        </a>
                    </li> --}}
                    <li class="nav-item mb-2">
                        <a class="nav-link {{ request()->routeIs('users.*') ? 'active' : '' }}" 
                           href="{{ route('users.index') }}">
                            <i class="bi bi-people me-2"></i>
                            Manajemen Pengguna
                        </a>
                    </li>
                @else
                    <li class="nav-item mb-2">
                        <a class="nav-link {{ request()->routeIs('attendance.daily') ? 'active' : '' }}" 
                           href="{{ route('attendance.daily') }}">
                            <i class="bi bi-clock me-2"></i>
                            Absen Harian
                        </a>
                    </li>
                @endif
            </ul>
        </div>

        <div class="sidebar-footer p-3">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-outline-light w-100">
                    <i class="bi bi-box-arrow-right me-2"></i>
                    Logout
                </button>
            </form>
        </div>
    </div>
</div>

<style>
.sidebar {
    width: 280px;
    min-height: 100vh;
    position: fixed;
    left: 0;
    top: 0;
    box-shadow: 0 0 15px rgba(0,0,0,0.1);
}

.sidebar-logo {
    width: 80px;
    height: 80px;
    object-fit: contain;
}

.nav-link {
    color: rgba(255,255,255,0.8);
    border-radius: 8px;
    padding: 10px 15px;
    transition: all 0.3s ease;
}

.nav-link:hover, .nav-link.active {
    color: #fff;
    background: rgba(255,255,255,0.1);
}

.sidebar-footer {
    border-top: 1px solid rgba(255,255,255,0.1);
}
</style> 