{{-- partials/navbar.blade.php --}}
<nav class="app-navbar sticky-top">
    <div class="container">
        <div class="d-flex align-items-center justify-content-between" style="height:64px">

            {{-- Brand --}}
            <a class="d-flex align-items-center gap-2 text-decoration-none" href="{{ route('landing') }}">
                <span class="brand-icon"><i class="fas fa-tools"></i></span>
                <span class="fw-700" style="font-size:1.2rem; color:var(--dark-gray); font-weight:700">SiRUKA</span>
            </a>

            {{-- Right --}}
            @auth
            @php $unread = Auth::user()->unreadNotifications->count(); @endphp
            <div class="d-flex align-items-center gap-2">

                {{-- Bell --}}
                <a href="{{ route('notifications.index') }}" class="navbar-icon-btn">
                    <i class="fas fa-bell"></i>
                    @if($unread > 0)
                        <span class="bell-badge">{{ $unread > 99 ? '99+' : $unread }}</span>
                    @endif
                </a>

                {{-- User dropdown --}}
                <div class="dropdown">
                    <button class="user-pill dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                        <img src="{{ Auth::user()->avatar_url }}" class="user-avatar" alt="avatar">
                        <span class="d-none d-md-inline user-name">{{ Auth::user()->name }}</span>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end navbar-dropdown mt-2">
                        <li class="px-3 py-2">
                            <div class="fw-600" style="font-size:.85rem; color:var(--dark-gray)">{{ Auth::user()->name }}</div>
                            <div style="font-size:.72rem; color:#6c757d">{{ Auth::user()->email }}</div>
                        </li>
                        <li><hr class="dropdown-divider my-1"></li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-item text-danger" style="border-radius:8px">
                                    <i class="fas fa-sign-out-alt me-2"></i> Logout
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>

            </div>
            @else
            <a class="btn-pill-orange" href="{{ route('landing') }}">
                <i class="fas fa-home me-1"></i> Home
            </a>
            @endauth

        </div>
    </div>
</nav>

@once
@push('styles')
<style>
    .app-navbar {
        background: white;
        border-bottom: 3px solid var(--primary-orange);
        box-shadow: 0 2px 12px rgba(0,0,0,.07);
        z-index: 1020;
    }

    .brand-icon {
        width: 36px; height: 36px;
        border-radius: 10px;
        background: linear-gradient(135deg, var(--primary-orange), var(--primary-orange-dark));
        color: white;
        display: flex; align-items: center; justify-content: center;
        font-size: .95rem;
        box-shadow: 0 3px 8px rgba(255,107,53,.3);
        transition: transform .2s;
        flex-shrink: 0;
    }
    .brand-icon:hover { transform: rotate(-8deg) scale(1.08); }

    .navbar-icon-btn {
        position: relative;
        width: 36px; height: 36px;
        border-radius: 10px;
        background: var(--light-orange);
        color: var(--primary-orange);
        display: flex; align-items: center; justify-content: center;
        text-decoration: none;
        transition: all .2s;
    }
    .navbar-icon-btn:hover {
        background: var(--primary-orange);
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(255,107,53,.35);
    }

    .bell-badge {
        position: absolute; top: -4px; right: -4px;
        min-width: 17px; height: 17px; padding: 0 3px;
        background: #dc3545; color: white;
        font-size: .6rem; font-weight: 700;
        border-radius: 9px; border: 2px solid white;
        display: flex; align-items: center; justify-content: center;
    }

    .user-pill {
        display: flex; align-items: center; gap: 8px;
        background: var(--light-orange);
        border: none; border-radius: 50px;
        padding: 4px 12px 4px 4px;
        transition: all .2s;
        cursor: pointer;
    }
    .user-pill:hover { background: #FFE4CC; }
    .user-pill::after { display: none; }

    .user-avatar {
        width: 28px; height: 28px;
        border-radius: 50%; object-fit: cover;
        border: 2px solid var(--primary-orange);
    }

    .user-name {
        font-size: .83rem; font-weight: 600;
        color: var(--dark-gray);
        max-width: 120px;
        white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
    }

    .navbar-dropdown {
        border: none; border-radius: 14px;
        box-shadow: 0 8px 28px rgba(0,0,0,.11);
        padding: 6px; min-width: 200px;
    }
    .navbar-dropdown .dropdown-item {
        border-radius: 8px; font-size: .85rem;
        font-weight: 500; padding: 8px 12px;
    }
    .navbar-dropdown .dropdown-item:hover { background: #FFF0EE; }

    .btn-pill-orange {
        background: linear-gradient(135deg, var(--primary-orange), var(--primary-orange-dark));
        color: white; border-radius: 50px;
        padding: 7px 20px; font-size: .85rem; font-weight: 600;
        text-decoration: none; transition: all .2s;
    }
    .btn-pill-orange:hover {
        color: white; transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(255,107,53,.4);
    }
</style>
@endpush

@push('scripts')
<script>
    // Auto-close dropdown on any nav link click inside it
    document.querySelectorAll('.navbar-dropdown .dropdown-item').forEach(el => {
        el.addEventListener('click', () => {
            bootstrap.Dropdown.getInstance(
                document.querySelector('.user-pill')
            )?.hide();
        });
    });
</script>
@endpush
@endonce