@extends('layouts.auth')

@section('title', 'Daftar Admin')

@section('content')
<div class="container">
    <div class="row justify-content-center min-vh-100 align-items-center">
        <div class="col-md-6 col-lg-5">
            <div class="card shadow-lg border-0">
                <div class="card-header text-center bg-gradient-orange text-white border-0">
                    <i class="fas fa-user-cog fa-2x mb-2"></i>
                    <h4 class="mb-0">Daftar Akun Admin</h4>
                    <p class="mb-0 mt-2 opacity-75">Registrasi administrator baru</p>
                </div>
                <div class="card-body p-4">
                    @if($errors->any())
                        <div class="alert alert-danger">
                            <i class="fas fa-exclamation-circle me-2"></i> Terjadi kesalahan:
                            <ul class="mb-0 mt-2">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('admin.register') }}">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="name" class="form-label">
                                <i class="fas fa-user me-2 text-orange"></i>Nama Lengkap <span class="text-danger">*</span>
                            </label>
                            <input type="text" 
                                   name="name" 
                                   id="name" 
                                   class="form-control @error('name') is-invalid @enderror" 
                                   value="{{ old('name') }}" 
                                   placeholder="Masukkan nama lengkap"
                                   required 
                                   autofocus>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="email" class="form-label">
                                <i class="fas fa-envelope me-2 text-orange"></i>Email <span class="text-danger">*</span>
                            </label>
                            <input type="email" 
                                   name="email" 
                                   id="email" 
                                   class="form-control @error('email') is-invalid @enderror" 
                                   value="{{ old('email') }}" 
                                   placeholder="admin@example.com"
                                   required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="phone" class="form-label">
                                <i class="fas fa-phone me-2 text-orange"></i>No. Telepon <span class="text-danger">*</span>
                            </label>
                            <input type="text" 
                                   name="phone" 
                                   id="phone" 
                                   class="form-control @error('phone') is-invalid @enderror" 
                                   value="{{ old('phone') }}" 
                                   placeholder="08123456789"
                                   required>
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="division" class="form-label">
                                <i class="fas fa-building me-2 text-orange"></i>Divisi
                            </label>
                            <select name="division" id="division" class="form-select @error('division') is-invalid @enderror">
                                <option value="">Pilih Divisi</option>
                                <option value="Fasilitas" {{ old('division') == 'Fasilitas' ? 'selected' : '' }}>Divisi Fasilitas</option>
                                <option value="IT" {{ old('division') == 'IT' ? 'selected' : '' }}>Divisi IT</option>
                                <option value="Akademik" {{ old('division') == 'Akademik' ? 'selected' : '' }}>Divisi Akademik</option>
                                <option value="Keuangan" {{ old('division') == 'Keuangan' ? 'selected' : '' }}>Divisi Keuangan</option>
                                <option value="Umum" {{ old('division') == 'Umum' ? 'selected' : '' }}>Divisi Umum</option>
                            </select>
                            @error('division')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="password" class="form-label">
                                <i class="fas fa-lock me-2 text-orange"></i>Password <span class="text-danger">*</span>
                            </label>
                            <input type="password" 
                                   name="password" 
                                   id="password" 
                                   class="form-control @error('password') is-invalid @enderror" 
                                   placeholder="Minimal 6 karakter"
                                   required>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="password_confirmation" class="form-label">
                                <i class="fas fa-check-circle me-2 text-orange"></i>Konfirmasi Password <span class="text-danger">*</span>
                            </label>
                            <input type="password" 
                                   name="password_confirmation" 
                                   id="password_confirmation" 
                                   class="form-control" 
                                   placeholder="Ulangi password"
                                   required>
                        </div>
                        
                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="terms" required>
                            <label class="form-check-label" for="terms">
                                Saya setuju dengan <a href="#" class="text-decoration-none">Syarat & Ketentuan</a> yang berlaku
                            </label>
                        </div>
                        
                        <button type="submit" class="btn btn-primary w-100 py-2">
                            <i class="fas fa-user-plus me-2"></i> Daftar sebagai Admin
                        </button>
                    </form>
                    
                    <hr class="my-4">
                    
                    <div class="text-center">
                        <p class="mb-2">
                            Sudah punya akun? 
                            <a href="{{ route('admin.login') }}" class="text-decoration-none fw-bold">
                                <i class="fas fa-sign-in-alt me-1"></i> Login di sini
                            </a>
                        </p>
                        <a href="{{ route('landing') }}" class="btn btn-outline-secondary w-100 mt-2">
                            <i class="fas fa-arrow-left me-2"></i> Kembali ke Portal Utama
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.text-orange {
    color: #FF6B35;
}
.bg-gradient-orange {
    background: linear-gradient(135deg, #FF6B35 0%, #FF8C42 100%);
}
.btn-outline-secondary:hover {
    background: #6c757d;
    color: white;
}
</style>
@endpush