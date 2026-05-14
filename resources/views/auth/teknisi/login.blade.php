@extends('layouts.auth')

@section('title', 'Login Teknisi')

@section('content')
<div class="container">
    <div class="row justify-content-center min-vh-100 align-items-center">
        <div class="col-md-5">
            <div class="card shadow-lg border-0">
                <div class="card-header text-center bg-gradient-orange text-white border-0">
                    <i class="fas fa-wrench fa-3x mb-2"></i>
                    <h3 class="mb-0">Portal Teknisi</h3>
                    <p class="mb-0 mt-2 opacity-75">Login untuk mengelola perbaikan</p>
                </div>
                <div class="card-body p-4">
                    @if($errors->any())<div class="alert alert-danger"><i class="fas fa-exclamation-circle me-2"></i> {{ $errors->first() }}</div>@endif
                    @if(session('success'))<div class="alert alert-success"><i class="fas fa-check-circle me-2"></i> {{ session('success') }}</div>@endif

                    <form method="POST" action="{{ route('teknisi.login') }}">
                        @csrf
                        <div class="mb-3"><label class="form-label"><i class="fas fa-envelope me-2 text-orange"></i>Email Teknisi</label><input type="email" name="email" class="form-control" placeholder="teknisi@example.com" value="{{ old('email') }}" required autofocus></div>
                        <div class="mb-3"><label class="form-label"><i class="fas fa-lock me-2 text-orange"></i>Password</label><input type="password" name="password" class="form-control" placeholder="Masukkan password" required></div>
                        <div class="mb-3 form-check"><input type="checkbox" class="form-check-input" id="remember" name="remember"><label class="form-check-label" for="remember"><i class="fas fa-check-circle me-1"></i> Ingat Saya</label></div>
                        <button type="submit" class="btn btn-primary w-100 py-2"><i class="fas fa-sign-in-alt me-2"></i> Login sebagai Teknisi</button>
                    </form>
                    <hr class="my-4">
                    <div class="text-center"><a href="{{ route('landing') }}" class="btn btn-outline-secondary w-100"><i class="fas fa-arrow-left me-2"></i> Kembali ke Portal Utama</a></div>
                </div>
            </div>
            <div class="text-center mt-3"><small class="text-muted"><i class="fas fa-info-circle me-1"></i> Demo: teknisi@example.com / password</small></div>
        </div>
    </div>
</div>
@endsection