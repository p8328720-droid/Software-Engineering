{{-- layouts/mahasiswa.blade.php --}}
@extends('layouts.app')

@push('navbar')
    @include('partials.navbar')
@endpush

@php
    $navItems = [
        ['route' => 'mahasiswa.dashboard', 'icon' => 'fa-tachometer-alt', 'label' => 'Dashboard'],
        ['route' => 'mahasiswa.reports.create', 'icon' => 'fa-plus-circle', 'label' => 'Buat Laporan'],
        ['route' => 'mahasiswa.reports.index', 'icon' => 'fa-list', 'label' => 'Daftar Laporan'],
        ['route' => 'mahasiswa.tracking', 'icon' => 'fa-map-marker-alt', 'label' => 'Tracking'],
    ];
@endphp

@section('content')
    <div class="container">

        {{-- Welcome banner --}}
        <div class="row mb-4">
            <div class="col-12">
                <div class="card bg-gradient-orange text-white border-0">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h4 class="mb-0">Halo, {{ Auth::user()->name ?? 'Mahasiswa' }}!</h4>
                                <p class="mb-0 mt-2 opacity-75">SiRUKA - Sistem Informasi Rusak Kampus</p>
                            </div>
                            <i class="fas fa-user-graduate fa-3x opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Nav tabs --}}
        <div class="row mb-4">
            <div class="col-12">

                {{-- Desktop --}}
                <ul class="nav nav-tabs d-none d-md-flex">
                    @foreach($navItems as $item)
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs($item['route']) ? 'active' : '' }}"
                                href="{{ route($item['route']) }}">
                                <i class="fas {{ $item['icon'] }} me-1"></i> {{ $item['label'] }}
                            </a>
                        </li>
                    @endforeach
                </ul>

                {{-- Mobile: scroll with fade hints --}}
                <div class="d-md-none tab-scroll-container" id="tabScrollContainer">
                    <div class="tab-scroll-fade tab-scroll-fade-left" id="tabFadeLeft"></div>
                    <div class="tab-scroll-fade tab-scroll-fade-right" id="tabFadeRight"></div>
                    <ul class="nav nav-tabs tab-scroll-inner" id="tabScrollInner">
                        @foreach($navItems as $item)
                            <li class="nav-item flex-shrink-0">
                                <a class="nav-link {{ request()->routeIs($item['route']) ? 'active' : '' }}"
                                    href="{{ route($item['route']) }}">
                                    <i class="fas {{ $item['icon'] }} me-1"></i> {{ $item['label'] }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>

            </div>
        </div>

        @yield('mahasiswa-content')

    </div>
@endsection

@push('styles')
    <style>
        .bg-gradient-orange {
            background: linear-gradient(135deg, var(--primary-orange) 0%, var(--primary-orange-light) 100%);
        }

        /* ── Desktop tabs ── */
        .nav-tabs {
            border-bottom: 2px solid #e9ecef;
            gap: 4px;
        }

        .nav-tabs .nav-link {
            border: none;
            border-radius: 10px 10px 0 0;
            color: #6c757d;
            font-size: .875rem;
            font-weight: 500;
            padding: 9px 16px;
            transition: all .2s;
            white-space: nowrap;
        }

        .nav-tabs .nav-link:hover {
            color: var(--primary-orange);
            background: var(--light-orange);
        }

        .nav-tabs .nav-link.active {
            color: var(--primary-orange);
            background: var(--light-orange);
            border-bottom: 2px solid var(--primary-orange);
            font-weight: 600;
        }

        /* ── Mobile scroll container ── */
        .tab-scroll-container {
            position: relative;
        }

        .tab-scroll-inner {
            overflow-x: auto;
            overflow-y: visible;
            flex-wrap: nowrap;
            -webkit-overflow-scrolling: touch;
            scrollbar-width: none;
            padding-bottom: 1px;
        }

        .tab-scroll-inner::-webkit-scrollbar {
            display: none;
        }

        /* ── Fade hints ── */
        .tab-scroll-fade {
            position: absolute;
            top: 0;
            bottom: 2px;
            width: 40px;
            z-index: 2;
            pointer-events: none;
            transition: opacity .25s ease;
        }

        .tab-scroll-fade-left {
            left: 0;
            background: linear-gradient(to right, black, transparent);
            opacity: 0.1;
        }

        .tab-scroll-fade-right {
            right: 0;
            background: linear-gradient(to left, black, transparent);
            opacity: 0.1;
        }

        .tab-scroll-fade.hidden {
            opacity: 0 !important;
        }
    </style>
@endpush

@push('scripts')
    <script>
        (function () {
            const inner = document.getElementById('tabScrollInner');
            const fadeL = document.getElementById('tabFadeLeft');
            const fadeR = document.getElementById('tabFadeRight');
            if (!inner) return;

            function updateFades() {
                const atLeft = inner.scrollLeft <= 4;
                const atRight = inner.scrollLeft + inner.clientWidth >= inner.scrollWidth - 4;
                fadeL.classList.toggle('hidden', atLeft);
                fadeR.classList.toggle('hidden', atRight);
            }

            // Scroll tab aktif ke tengah saat load
            const activeTab = inner.querySelector('.nav-link.active');
            if (activeTab) {
                const offset = activeTab.offsetLeft - (inner.clientWidth / 2) + (activeTab.offsetWidth / 2);
                inner.scrollLeft = offset;
            }

            inner.addEventListener('scroll', updateFades, { passive: true });
            updateFades();
        })();
    </script>
@endpush