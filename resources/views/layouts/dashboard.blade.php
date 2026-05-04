@extends('layouts.app')

@section('content')
@if(Auth::user()->isMahasiswa())
    <div class="container">
        <div class="row mb-4">
            <div class="col-12">
                <div class="card bg-gradient-orange text-white border-0">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h4 class="mb-0">Halo, {{ Auth::user()->name }}!</h4>
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
                <x-tabs-nav />
            </div>
        </div>
        
        @yield('dashboard-content')
    </div>
@else
    <div class="container-fluid">
        <div class="row">
            <nav class="col-md-2 d-md-block bg-white sidebar" style="min-height: 100vh; box-shadow: 2px 0 5px rgba(0,0,0,0.05);">
                <x-sidebar />
            </nav>
            
            <main class="col-md-10 ms-sm-auto px-md-4">
                @yield('dashboard-content')
            </main>
        </div>
    </div>
@endif
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
    
    .bg-gradient-orange {
        background: linear-gradient(135deg, #FF6B35 0%, #FF8C42 100%);
    }
</style>
@endpush
