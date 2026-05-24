@extends('layouts.auth')

@section('title', 'Daftar Mahasiswa')

@section('content')
<div class="container">
    <div class="row justify-content-center min-vh-100 align-items-center">
        <div class="col-md-6">
            <div class="card shadow-lg border-0">
                <div class="card-header text-center bg-gradient-orange text-white border-0">
                    <i class="fas fa-user-plus fa-2x mb-2"></i>
                    <h4 class="mb-0">Daftar Akun Mahasiswa</h4>
                    <p class="mb-0 mt-2 opacity-75">Bergabung dengan SiRUKA</p>
                </div>
                <div class="card-body p-4">
                    @if($errors->any())
                        <div class="alert alert-danger">
                            <i class="fas fa-exclamation-circle me-2"></i> Terjadi kesalahan:
                            <ul class="mb-0 mt-2">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('mahasiswa.register') }}">
                        @csrf
                        <div class="row">
                            <div class="col-md-6 mb-3"><label class="form-label">Nama Lengkap</label><input type="text" name="name" class="form-control" value="{{ old('name') }}" required></div>
                            <div class="col-md-6 mb-3"><label class="form-label">NIM</label><input type="text" name="student_id" class="form-control" value="{{ old('student_id') }}" required></div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3"><label class="form-label">Email</label><input type="email" name="email" class="form-control" value="{{ old('email') }}" required></div>
                            <div class="col-md-6 mb-3"><label class="form-label">No. Telepon</label><input type="text" name="phone" class="form-control" value="{{ old('phone') }}" required></div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3"><label class="form-label">Fakultas</label>
                                <select name="faculty" class="form-select" required>
                                    <option value="">Pilih Fakultas</option>
                                    <option value="Fakultas Teknik">Fakultas Teknik</option>
                                    <option value="Fakultas Ekonomi">Fakultas Ekonomi</option>
                                    <option value="Fakultas Hukum">Fakultas Hukum</option>
                                    <option value="Fakultas Kedokteran">Fakultas Kedokteran</option>
                                    <option value="Fakultas Ilmu Komputer">Fakultas Ilmu Komputer</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3"><label class="form-label">Jurusan</label><input type="text" name="major" class="form-control" value="{{ old('major') }}" required></div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3"><label class="form-label">Password</label><input type="password" name="password" class="form-control" required></div>
                            <div class="col-md-6 mb-3"><label class="form-label">Konfirmasi Password</label><input type="password" name="password_confirmation" class="form-control" required></div>
                        </div>
                        <button type="submit" class="btn btn-primary w-100"><i class="fas fa-user-plus me-2"></i> Daftar Sekarang</button>
                    </form>
                    <hr class="my-4">
                    <div class="text-center"><p class="mb-2">Sudah punya akun? <a href="{{ route('mahasiswa.login') }}" class="text-decoration-none fw-bold"><i class="fas fa-sign-in-alt me-1"></i> Login di sini</a></p></div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection