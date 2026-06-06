{{-- layouts/teknisi.blade.php — extends layouts.app --}}
@extends('layouts.app')

@section('title', 'Teknisi Panel')

{{-- ═══════════════════════════════════════════════════════════
Teknisi-specific CSS
═══════════════════════════════════════════════════════════ --}}
@push('styles')
    <style>
        /* ── Override body background for teknisi ── */
        body {
            background: #F8F9FA !important;
        }

        /* ── Hide app layout's default footer padding from main ── */
        main.py-4 {
            padding: 0 !important;
        }

        /* ── Mobile top bar ── */
        .mobile-topbar {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1030;
            height: 56px;
            background: white;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            align-items: center;
            justify-content: space-between;
            padding: 0 16px;
            border-bottom: 3px solid var(--primary-orange);
        }

        /* ── Hamburger ── */
        .hamburger-btn {
            display: flex;
            flex-direction: column;
            justify-content: center;
            gap: 5px;
            width: 36px;
            height: 36px;
            background: none;
            border: none;
            cursor: pointer;
            padding: 4px;
            border-radius: 8px;
            transition: background 0.2s;
        }

        .hamburger-btn:hover {
            background: var(--light-orange);
        }

        .hamburger-btn span {
            display: block;
            height: 2px;
            background: var(--dark-gray);
            border-radius: 2px;
            transition: all 0.3s ease;
            transform-origin: center;
        }

        .hamburger-btn.open span:nth-child(1) {
            transform: translateY(7px) rotate(45deg);
        }

        .hamburger-btn.open span:nth-child(2) {
            opacity: 0;
            transform: scaleX(0);
        }

        .hamburger-btn.open span:nth-child(3) {
            transform: translateY(-7px) rotate(-45deg);
        }

        /* ── Sidebar ── */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: 240px;
            height: 100vh;
            background: white;
            box-shadow: 2px 0 5px rgba(0, 0, 0, 0.05);
            overflow-y: auto;
            z-index: 1035;
            transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .sidebar .nav-link {
            color: var(--dark-gray);
            padding: 10px 15px;
            border-radius: 10px;
            margin: 2px 0;
            transition: all 0.3s;
        }

        .sidebar .nav-link:hover {
            background-color: var(--light-orange);
            color: var(--primary-orange);
        }

        .sidebar .nav-link.active {
            background: linear-gradient(135deg, var(--primary-orange) 0%, var(--primary-orange-light) 100%);
            color: white;
        }

        .sidebar .nav-link.active i {
            color: white !important;
        }

        /* ── Sidebar close button ── */
        .sidebar-close {
            display: none;
            position: absolute;
            top: 12px;
            right: 12px;
            background: none;
            border: none;
            font-size: 1.1rem;
            color: #6c757d;
            cursor: pointer;
            width: 32px;
            height: 32px;
            border-radius: 8px;
            align-items: center;
            justify-content: center;
            transition: background 0.2s, color 0.2s;
        }

        .sidebar-close:hover {
            background: var(--light-orange);
            color: var(--primary-orange);
        }

        /* ── Backdrop ── */
        .sidebar-backdrop {
            display: none;
            position: fixed;
            inset: 0;
            z-index: 1034;
            background: rgba(0, 0, 0, 0.45);
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .sidebar-backdrop.visible {
            opacity: 1;
        }

        /* ── Main content offset ── */
        .teknisi-main {
            margin-left: 240px;
            padding: 2rem;
            min-height: 100vh;
        }

        /* ── Responsive ── */
        @media (max-width: 767.98px) {
            .mobile-topbar {
                display: flex;
            }

            .sidebar {
                transform: translateX(-100%);
                box-shadow: 4px 0 20px rgba(0, 0, 0, 0.12);
            }

            .sidebar.open {
                transform: translateX(0);
            }

            .sidebar-close {
                display: flex;
            }

            .sidebar-backdrop.visible {
                display: block;
            }

            .teknisi-main {
                margin-left: 0;
                padding: 1rem;
                padding-top: 72px;
            }
        }

        /* ── Sidebar logout hover merah ── */
        .sidebar .nav-link.text-danger:hover {
            background-color: #FFF0EE;
            color: #dc3545 !important;
        }

        /* ── User card di bawah sidebar ── */
        .sidebar-user {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 12px 15px;
            margin: 8px 0 4px;
            border-radius: 12px;
            background: #FFF4E6;
            border: 1px solid #FFE4CC;
        }

        .sidebar-user img {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid #FF6B35;
            flex-shrink: 0;
        }

        .sidebar-user-name {
            font-size: 0.8rem;
            font-weight: 600;
            color: #2C3E50;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            max-width: 150px;
        }

        .sidebar-user-role {
            font-size: 0.7rem;
            color: #FF6B35;
            font-weight: 500;
        }

        /* ── Notification badge ── */
        .badge-notif {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 20px;
            height: 20px;
            padding: 0 5px;
            font-size: 0.65rem;
            font-weight: 700;
            background: #FF6B35;
            color: white;
            border-radius: 10px;
            float: right;
            margin-top: 2px;
            animation: pulse-badge 2s infinite;
        }

        @keyframes pulse-badge {

            0%,
            100% {
                box-shadow: 0 0 0 0 rgba(255, 107, 53, 0.4);
            }

            50% {
                box-shadow: 0 0 0 5px rgba(255, 107, 53, 0);
            }
        }

        /* Badge putih saat link aktif */
        .nav-link.active .badge-notif {
            background: white;
            color: #FF6B35;
        }

        /* ── Sidebar logout hover merah ── */
        .sidebar .nav-link.text-danger:hover {
            background-color: #FFF0EE;
            color: #dc3545 !important;
        }

        /* ── User card di bawah sidebar ── */
        .sidebar-user {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 12px 15px;
            margin: 8px 0 4px;
            border-radius: 12px;
            background: #FFF4E6;
            border: 1px solid #FFE4CC;
        }

        .sidebar-user img {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid #FF6B35;
            flex-shrink: 0;
        }

        .sidebar-user-name {
            font-size: 0.8rem;
            font-weight: 600;
            color: #2C3E50;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            max-width: 150px;
        }

        .sidebar-user-role {
            font-size: 0.7rem;
            color: #FF6B35;
            font-weight: 500;
        }
    </style>
@endpush

{{-- ═══════════════════════════════════════════════════════════
Main content — injects teknisi shell into app's @yield('content')
═══════════════════════════════════════════════════════════ --}}
@section('content')

    {{-- Mobile Top Bar --}}
    <div class="mobile-topbar">
        <div class="d-flex align-items-center gap-2">
            <i class="fas fa-wrench text-orange"></i>
            <span class="fw-semibold" style="color: var(--dark-gray);">Teknisi Panel</span>
        </div>
        <button class="hamburger-btn" id="sidebarToggle" aria-label="Toggle menu">
            <span></span><span></span><span></span>
        </button>
    </div>

    {{-- Backdrop --}}
    <div class="sidebar-backdrop" id="sidebarBackdrop"></div>

    {{-- Sidebar --}}
    <nav class="sidebar" id="teknisiSidebar">
        <div class="position-sticky pt-3 px-2">

            <button class="sidebar-close" id="sidebarClose" aria-label="Close menu">
                <i class="fas fa-times"></i>
            </button>

            <div class="text-center mb-4 pb-2">
                <i class="fas fa-wrench fa-3x text-orange"></i>
                <h6 class="mt-2 text-muted">Teknisi Panel</h6>
            </div>

            <hr>

            <ul class="nav flex-column">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('teknisi.dashboard') ? 'active' : '' }}"
                        href="{{ route('teknisi.dashboard') }}">
                        <i class="fas fa-tachometer-alt me-2"></i> Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('teknisi.tasks.*') ? 'active' : '' }}"
                        href="{{ route('teknisi.tasks.index') }}">
                        <i class="fas fa-tasks me-2"></i> Daftar Tugas
                    </a>
                </li>
            </ul>

            <hr>

            <ul class="nav flex-column">
                {{-- Notifikasi --}}
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('notifications.*') ? 'active' : '' }}"
                        href="{{ route('notifications.index') }}">
                        <i class="fas fa-bell me-2"></i> Notifikasi
                        @php $unread = Auth::user()->unreadNotifications->count(); @endphp
                        @if($unread > 0)
                            <span class="badge-notif">{{ $unread > 99 ? '99+' : $unread }}</span>
                        @endif
                    </a>
                </li>
                {{-- Logout --}}
                <li class="nav-item">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="nav-link text-danger bg-transparent border-0 w-100 text-start">
                            <i class="fas fa-sign-out-alt me-2"></i> Logout
                        </button>
                    </form>
                </li>
            </ul>

            {{-- User card --}}
            <div class="sidebar-user">
                <img src="{{ Auth::user()->avatar_url }}" alt="avatar">
                <div class="sidebar-user-info">
                    <div class="sidebar-user-name">{{ Auth::user()->name }}</div>
                    <div class="sidebar-user-role">Teknisi</div>
                </div>
            </div>

        </div>
    </nav>

    {{-- Teknisi Main Content --}}
    <main class="teknisi-main">
        @yield('teknisi-content')
    </main>

@endsection

{{-- ═══════════════════════════════════════════════════════════
Sidebar JS
═══════════════════════════════════════════════════════════ --}}
@push('scripts')
    <script>
        const sidebar = document.getElementById('teknisiSidebar');
        const toggle = document.getElementById('sidebarToggle');
        const closeBtn = document.getElementById('sidebarClose');
        const backdrop = document.getElementById('sidebarBackdrop');

        function openSidebar() {
            sidebar.classList.add('open');
            backdrop.classList.add('visible');
            backdrop.style.display = 'block';
            toggle.classList.add('open');
            document.body.style.overflow = 'hidden';
        }

        function closeSidebar() {
            sidebar.classList.remove('open');
            toggle.classList.remove('open');
            document.body.style.overflow = '';
            backdrop.classList.remove('visible');
            setTimeout(() => { backdrop.style.display = 'none'; }, 300);
        }

        toggle?.addEventListener('click', () =>
            sidebar.classList.contains('open') ? closeSidebar() : openSidebar()
        );
        closeBtn?.addEventListener('click', closeSidebar);
        backdrop?.addEventListener('click', closeSidebar);
        document.addEventListener('keydown', e => { if (e.key === 'Escape') closeSidebar(); });
        sidebar?.querySelectorAll('.nav-link').forEach(link => {
            link.addEventListener('click', () => { if (window.innerWidth < 768) closeSidebar(); });
        });
    </script>
@endpush