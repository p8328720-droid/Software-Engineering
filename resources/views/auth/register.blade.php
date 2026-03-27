@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-7">
            <div class="card fade-in-up border-0">
                <div class="card-header text-center">
                    <i class="fas fa-user-plus fa-2x mb-2"></i>
                    <h4 class="mb-0">Registrasi Mahasiswa</h4>
                    <p class="mb-0 mt-2 opacity-75">Daftar akun baru SiRUKA</p>
                </div>
                <div class="card-body p-4">
                    @if($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-circle me-2"></i>
                            <strong>Periksa kembali data Anda:</strong>
                            <ul class="mb-0 mt-2">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif
                    
                    <form method="POST" action="{{ route('register') }}">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="name" class="form-label">
                                    <i class="fas fa-user me-2 text-orange"></i>Nama Lengkap
                                </label>
                                <input id="name" type="text" 
                                       class="form-control @error('name') is-invalid @enderror" 
                                       name="name" value="{{ old('name') }}" 
                                       required autofocus>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="student_id" class="form-label">
                                    <i class="fas fa-id-card me-2 text-orange"></i>NIM
                                </label>
                                <input id="student_id" type="text" 
                                       class="form-control @error('student_id') is-invalid @enderror" 
                                       name="student_id" value="{{ old('student_id') }}" required>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label">
                                    <i class="fas fa-envelope me-2 text-orange"></i>Email
                                </label>
                                <input id="email" type="email" 
                                       class="form-control @error('email') is-invalid @enderror" 
                                       name="email" value="{{ old('email') }}" required>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="phone" class="form-label">
                                    <i class="fas fa-phone me-2 text-orange"></i>No. Telepon
                                </label>
                                <input id="phone" type="text" 
                                       class="form-control @error('phone') is-invalid @enderror" 
                                       name="phone" value="{{ old('phone') }}" required>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="faculty" class="form-label">
                                    <i class="fas fa-university me-2 text-orange"></i>Fakultas
                                </label>
                                <select id="faculty" class="form-select @error('faculty') is-invalid @enderror" 
                                        name="faculty" required>
                                    <option value="">Pilih Fakultas</option>
                                    <option value="Fakultas Teknik" {{ old('faculty') == 'Fakultas Teknik' ? 'selected' : '' }}>Fakultas Teknik</option>
                                    <option value="Fakultas Ekonomi" {{ old('faculty') == 'Fakultas Ekonomi' ? 'selected' : '' }}>Fakultas Ekonomi</option>
                                    <option value="Fakultas Hukum" {{ old('faculty') == 'Fakultas Hukum' ? 'selected' : '' }}>Fakultas Hukum</option>
                                    <option value="Fakultas Kedokteran" {{ old('faculty') == 'Fakultas Kedokteran' ? 'selected' : '' }}>Fakultas Kedokteran</option>
                                    <option value="Fakultas Ilmu Komputer" {{ old('faculty') == 'Fakultas Ilmu Komputer' ? 'selected' : '' }}>Fakultas Ilmu Komputer</option>
                                </select>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="major" class="form-label">
                                    <i class="fas fa-graduation-cap me-2 text-orange"></i>Jurusan
                                </label>
                                <input id="major" type="text" 
                                       class="form-control @error('major') is-invalid @enderror" 
                                       name="major" value="{{ old('major') }}" required>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="password" class="form-label">
                                    <i class="fas fa-lock me-2 text-orange"></i>Password
                                </label>
                                <input id="password" type="password" 
                                       class="form-control @error('password') is-invalid @enderror" 
                                       name="password" required>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="password-confirm" class="form-label">
                                    <i class="fas fa-check-circle me-2 text-orange"></i>Konfirmasi Password
                                </label>
                                <input id="password-confirm" type="password" 
                                       class="form-control" name="password_confirmation" required>
                            </div>
                        </div>
                        
                        <div class="mb-3 form-check">
                            <input class="form-check-input" type="checkbox" id="terms" required>
                            <label class="form-check-label" for="terms">
                                Saya setuju dengan <a href="#" class="text-decoration-none">Syarat & Ketentuan</a> yang berlaku
                            </label>
                        </div>
                        
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-user-plus me-2"></i> Daftar Sekarang
                        </button>
                    </form>
                    
                    <hr class="my-4">
                    
                    <div class="text-center">
                        <p class="mb-0">Sudah punya akun? 
                            <a href="{{ route('login') }}" class="text-decoration-none fw-bold">
                                <i class="fas fa-sign-in-alt me-1"></i> Login di sini
                            </a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection