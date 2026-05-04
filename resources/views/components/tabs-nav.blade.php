<ul class="nav nav-tabs">
    <li class="nav-item">
        <a class="nav-link {{ request()->routeIs('mahasiswa.dashboard') ? 'active' : '' }}" 
           href="{{ route('mahasiswa.dashboard') }}">
            <i class="fas fa-tachometer-alt me-1"></i> Dashboard
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ request()->routeIs('mahasiswa.reports.create') ? 'active' : '' }}" 
           href="{{ route('mahasiswa.reports.create') }}">
            <i class="fas fa-plus-circle me-1"></i> Buat Laporan
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ request()->routeIs('mahasiswa.reports.index') ? 'active' : '' }}" 
           href="{{ route('mahasiswa.reports.index') }}">
            <i class="fas fa-list me-1"></i> Daftar Laporan
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ request()->routeIs('mahasiswa.tracking') ? 'active' : '' }}" 
           href="{{ route('mahasiswa.tracking') }}">
            <i class="fas fa-map-marker-alt me-1"></i> Tracking
        </a>
    </li>
</ul>

<style>
.nav-tabs {
    border-bottom: 2px solid #FFE4CC;
}
.nav-tabs .nav-link {
    color: #6c757d;
    border: none;
    padding: 10px 20px;
    border-radius: 10px 10px 0 0;
    font-weight: 500;
}
.nav-tabs .nav-link:hover {
    color: #FF6B35;
    background: #FFF4E6;
}
.nav-tabs .nav-link.active {
    color: #FF6B35;
    background: white;
    border-bottom: 3px solid #FF6B35;
}
</style>
