@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center min-vh-100 align-items-center">
        <div class="col-md-6 col-lg-5">
            <div class="card fade-in-up border-0">
                <div class="card-header text-center">
                    <i class="fas fa-envelope fa-3x mb-2"></i>
                    <h4 class="mb-0">Verifikasi Email</h4>
                    <p class="mb-0 mt-2 opacity-75">Konfirmasi alamat email Anda</p>
                </div>
                <div class="card-body p-4 text-center">
                    @if (session('resent'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle me-2"></i>
                            Link verifikasi baru telah dikirim ke alamat email Anda.
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif
                    
                    <div class="mb-4">
                        <div class="verification-icon mb-3">
                            <i class="fas fa-envelope-open-text fa-4x text-orange"></i>
                        </div>
                        <h5>Periksa Email Anda</h5>
                        <p class="text-muted">
                            Kami telah mengirimkan link verifikasi ke alamat email 
                            <strong>{{ Auth::user()->email ?? 'email@example.com' }}</strong>
                        </p>
                        <p class="text-muted small">
                            Silakan klik link verifikasi yang kami kirim untuk mengaktifkan akun Anda.
                        </p>
                    </div>
                    
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        Tidak menerima email verifikasi?
                    </div>
                    
                    <form method="POST" action="{{ route('verification.resend') }}">
                        @csrf
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-paper-plane me-2"></i> Kirim Ulang Email Verifikasi
                        </button>
                    </form>
                    
                    <hr class="my-4">
                    
                    <div class="text-center">
                        <p class="mb-0 text-muted small">
                            <i class="fas fa-question-circle me-1"></i>
                            Pastikan Anda memeriksa folder Spam atau Junk Mail.
                        </p>
                    </div>
                </div>
            </div>
            
            <div class="card border-0 mt-3 bg-light">
                <div class="card-body py-3">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-tools text-orange fa-2x me-3"></i>
                        <div>
                            <h6 class="mb-0">Butuh bantuan?</h6>
                            <small class="text-muted">Jika masih mengalami kendala, hubungi admin melalui email admin@siruka.ac.id</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.verification-icon {
    animation: pulse 2s infinite;
}
@keyframes pulse {
    0% { transform: scale(1); opacity: 1; }
    50% { transform: scale(1.1); opacity: 0.8; }
    100% { transform: scale(1); opacity: 1; }
}
</style>
@endpush