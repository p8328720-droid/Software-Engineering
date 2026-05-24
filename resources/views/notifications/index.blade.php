@extends('layouts.app')

@section('title', 'Notifikasi')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 mx-auto">
            <!-- Tombol Kembali sesuai Role -->
            <div class="mb-3">
                @auth
                    @if(Auth::user()->role == 'mahasiswa')
                        <a href="{{ route('mahasiswa.dashboard') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-2"></i> Kembali ke Dashboard Mahasiswa
                        </a>
                    @elseif(Auth::user()->role == 'teknisi')
                        <a href="{{ route('teknisi.dashboard') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-2"></i> Kembali ke Dashboard Teknisi
                        </a>
                    @elseif(Auth::user()->role == 'admin')
                        <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-2"></i> Kembali ke Dashboard Admin
                        </a>
                    @else
                        <a href="{{ route('landing') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-2"></i> Kembali ke Halaman Utama
                        </a>
                    @endif
                @endauth
            </div>

            <div class="card border-0 fade-in-up">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="fas fa-bell me-2"></i>Notifikasi</h5>
                    @if($notifications->where('is_read', false)->count() > 0)
                    <form action="{{ route('notifications.mark-all-read') }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-outline-light">
                            <i class="fas fa-check-double me-1"></i> Tandai Semua Dibaca
                        </button>
                    </form>
                    @endif
                </div>
                <div class="card-body p-0">
                    @forelse($notifications as $notification)
                    <div class="notification-item p-3 border-bottom {{ !$notification->is_read ? 'bg-light' : '' }}">
                        <div class="d-flex">
                            <div class="me-3">
                                @if($notification->type == 'success')
                                    <i class="fas fa-check-circle fa-2x text-success"></i>
                                @elseif($notification->type == 'danger')
                                    <i class="fas fa-exclamation-circle fa-2x text-danger"></i>
                                @elseif($notification->type == 'warning')
                                    <i class="fas fa-exclamation-triangle fa-2x text-warning"></i>
                                @else
                                    <i class="fas fa-info-circle fa-2x text-info"></i>
                                @endif
                            </div>
                            <div class="flex-grow-1">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <h6 class="mb-1">{{ $notification->title }}</h6>
                                        <p class="mb-1 small">{{ $notification->message }}</p>
                                        <small class="text-muted">
                                            <i class="far fa-clock me-1"></i> {{ $notification->created_at->diffForHumans() }}
                                        </small>
                                    </div>
                                    <div class="btn-group">
                                        @if(!$notification->is_read)
                                        <form action="{{ route('notifications.mark-read', $notification) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-link text-primary" title="Tandai dibaca">
                                                <i class="fas fa-check-circle"></i>
                                            </button>
                                        </form>
                                        @endif
                                        <form action="{{ route('notifications.destroy', $notification) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-link text-danger" title="Hapus" onclick="return confirm('Hapus notifikasi ini?')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-5">
                        <i class="fas fa-bell-slash fa-4x text-muted mb-3 d-block"></i>
                        <h6 class="text-muted">Belum ada notifikasi</h6>
                        <p class="text-muted small">Notifikasi akan muncul saat ada aktivitas terkait laporan Anda</p>
                    </div>
                    @endforelse
                </div>
                @if($notifications->hasPages())
                <div class="card-footer">
                    {{ $notifications->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.notification-item {
    transition: background 0.3s;
}
.notification-item:hover {
    background: #f8f9fa;
}
.fade-in-up {
    animation: fadeInUp 0.6s ease-out;
}
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}
.btn-outline-secondary:hover {
    background: #6c757d;
    color: white;
}
</style>
@endpush