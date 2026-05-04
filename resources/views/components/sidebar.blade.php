<div class="position-sticky pt-3">
    <div class="text-center mb-4 pb-2 border-bottom">
        @if(Auth::user()->isAdmin())
            <i class="fas fa-user-cog fa-3x text-orange"></i>
            <h6 class="mt-2 text-muted">Admin Panel</h6>
        @elseif(Auth::user()->isSupervisor())
            <i class="fas fa-chart-line fa-3x text-orange"></i>
            <h6 class="mt-2 text-muted">Supervisor Panel</h6>
        @elseif(Auth::user()->isTeknisi())
            <i class="fas fa-wrench fa-3x text-orange"></i>
            <h6 class="mt-2 text-muted">Teknisi Panel</h6>
        @endif
    </div>
    
    <ul class="nav flex-column">
        @if(Auth::user()->isAdmin())
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
                    <i class="fas fa-tachometer-alt me-2"></i> Dashboard
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}" href="{{ route('admin.users.index') }}">
                    <i class="fas fa-users me-2"></i> Kelola Users
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.facilities.*') ? 'active' : '' }}" href="{{ route('admin.facilities.index') }}">
                    <i class="fas fa-building me-2"></i> Kelola Fasilitas
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.sla.*') ? 'active' : '' }}" href="{{ route('admin.sla.index') }}">
                    <i class="fas fa-clock me-2"></i> Aturan SLA
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.audit.*') ? 'active' : '' }}" href="{{ route('admin.audit.index') }}">
                    <i class="fas fa-history me-2"></i> Audit Trail
                </a>
            </li>
        @elseif(Auth::user()->isSupervisor())
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('supervisor.dashboard') ? 'active' : '' }}" href="{{ route('supervisor.dashboard') }}">
                    <i class="fas fa-tachometer-alt me-2"></i> Dashboard
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('supervisor.monitoring.*') ? 'active' : '' }}" href="{{ route('supervisor.monitoring.index') }}">
                    <i class="fas fa-chart-line me-2"></i> Monitoring Kinerja
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('supervisor.escalation.*') ? 'active' : '' }}" href="{{ route('supervisor.escalation.index') }}">
                    <i class="fas fa-arrow-up me-2"></i> Eskalasi Laporan
                </a>
            </li>
            <!-- Add other supervisor links here -->
        @elseif(Auth::user()->isTeknisi())
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('teknisi.dashboard') ? 'active' : '' }}" href="{{ route('teknisi.dashboard') }}">
                    <i class="fas fa-tachometer-alt me-2"></i> Dashboard
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('teknisi.tasks.*') ? 'active' : '' }}" href="{{ route('teknisi.tasks.index') }}">
                    <i class="fas fa-tasks me-2"></i> Daftar Tugas
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('teknisi.reports.*') ? 'active' : '' }}" href="#">
                    <i class="fas fa-chart-line me-2"></i> Laporan Saya
                </a>
            </li>
        @endif
    </ul>
    
    <hr>
    <ul class="nav flex-column">
        <li class="nav-item">
            {{-- <a class="nav-link text-muted" href="{{ route('profile') }}">
                <i class="fas fa-user me-2"></i> Profil
            </a> --}}
        </li>
        <li class="nav-item">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="nav-link text-muted bg-transparent border-0 w-100 text-start">
                    <i class="fas fa-sign-out-alt me-2"></i> Logout
                </button>
            </form>
        </li>
    </ul>
</div>
