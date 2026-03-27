@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <nav class="col-md-2 d-md-block bg-white sidebar" style="min-height: 100vh; box-shadow: 2px 0 5px rgba(0,0,0,0.05);">
            <div class="position-sticky pt-3">
                <div class="text-center mb-4 pb-2 border-bottom">
                    <i class="fas fa-user-cog fa-3x text-orange"></i>
                    <h6 class="mt-2 text-muted">Admin Panel</h6>
                </div>
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" 
                           href="{{ route('admin.dashboard') }}">
                            <i class="fas fa-tachometer-alt me-2"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}" 
                           href="{{ route('admin.users') }}">
                            <i class="fas fa-users me-2"></i> Kelola Users
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.facilities.*') ? 'active' : '' }}" 
                           href="{{ route('admin.facilities') }}">
                            <i class="fas fa-building me-2"></i> Kelola Fasilitas
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.sla.*') ? 'active' : '' }}" 
                           href="{{ route('admin.sla') }}">
                            <i class="fas fa-clock me-2"></i> Aturan SLA
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.audit') ? 'active' : '' }}" 
                           href="{{ route('admin.audit') }}">
                            <i class="fas fa-history me-2"></i> Audit Trail
                        </a>
                    </li>
                </ul>
                <hr>
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link text-muted" href="{{ route('profile') }}">
                            <i class="fas fa-user me-2"></i> Profil
                        </a>
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
        </nav>
        
        <main class="col-md-10 ms-sm-auto px-md-4">
            @yield('admin-content')
        </main>
    </div>
</div>
@endsection

@push('styles')
<style>
    .sidebar .nav-link {
        color: #2C3E50;
        padding: 10px 15px;
        border-radius: 10px;
        margin: 2px 0;
        transition: all 0.3s ease;
    }
    .sidebar .nav-link:hover {
        background-color: #FFF4E6;
        color: #FF6B35;
    }
    .sidebar .nav-link.active {
        background: linear-gradient(135deg, #FF6B35 0%, #FF8C42 100%);
        color: white;
    }
    .text-orange { color: #FF6B35; }
</style>
@endpush