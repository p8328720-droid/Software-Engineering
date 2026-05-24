@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-12">
            <div class="card bg-gradient-orange text-white border-0">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="mb-0">Halo, {{ Auth::user()->name ?? 'Mahasiswa' }}!</h4>
                            <p class="mb-0 mt-2 opacity-75">SiRUKA - Sistem Informasi Rusak Kampus</p>
                        </div>
                        <div><i class="fas fa-user-graduate fa-3x opacity-50"></i></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row mb-4">
        <div class="col-12">
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
        </div>
    </div>
    
    @yield('mahasiswa-content')
</div>
@endsection

@push('styles')
<style>
.bg-gradient-orange {
    background: linear-gradient(135deg, #FF6B35 0%, #FF8C42 100%);
}
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
@endpush