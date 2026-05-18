@extends('layouts.dashboard')

@section('title', 'Tambah User')

@section('dashboard-content')
<div class="container-fluid px-0">
    <div class="d-flex justify-content-between flex-wrap align-items-center mb-4">
        <h4 class="fw-bold"><i class="fas fa-user-plus text-orange me-2"></i>Tambah User Baru</h4>
        <a href="{{ route('admin.users.index') }}" class="btn btn-secondary fw-bold"><i class="fas fa-arrow-left me-1"></i> Kembali</a>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-4">
            <form action="{{ route('admin.users.store') }}" method="POST">
                @csrf
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label small-caps">Nama Lengkap <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required>
                        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label small-caps">Email <span class="text-danger">*</span></label>
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" required>
                        @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label small-caps">NIM</label>
                        <input type="text" name="student_id" class="form-control @error('student_id') is-invalid @enderror" value="{{ old('student_id') }}">
                        @error('student_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label small-caps">No. Telepon</label>
                        <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone') }}">
                        @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label small-caps">Fakultas</label>
                        <select name="faculty" class="form-select @error('faculty') is-invalid @enderror">
                            <option value="">Pilih Fakultas</option>
                            <option value="Fakultas Teknik" {{ old('faculty') == 'Fakultas Teknik' ? 'selected' : '' }}>Fakultas Teknik</option>
                            <option value="Fakultas Ekonomi" {{ old('faculty') == 'Fakultas Ekonomi' ? 'selected' : '' }}>Fakultas Ekonomi</option>
                            <option value="Fakultas Hukum" {{ old('faculty') == 'Fakultas Hukum' ? 'selected' : '' }}>Fakultas Hukum</option>
                            <option value="Fakultas Kedokteran" {{ old('faculty') == 'Fakultas Kedokteran' ? 'selected' : '' }}>Fakultas Kedokteran</option>
                            <option value="Fakultas Ilmu Komputer" {{ old('faculty') == 'Fakultas Ilmu Komputer' ? 'selected' : '' }}>Fakultas Ilmu Komputer</option>
                        </select>
                        @error('faculty')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label small-caps">Jurusan</label>
                        <input type="text" name="major" class="form-control @error('major') is-invalid @enderror" value="{{ old('major') }}">
                        @error('major')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label small-caps">Role <span class="text-danger">*</span></label>
                        <select name="role" class="form-select @error('role') is-invalid @enderror" required>
                            <option value="pelapor" {{ old('role') == 'pelapor' ? 'selected' : '' }}>Pelapor</option>
                            <option value="teknisi" {{ old('role') == 'teknisi' ? 'selected' : '' }}>Teknisi</option>
                            <option value="supervisor" {{ old('role') == 'supervisor' ? 'selected' : '' }}>Supervisor</option>
                            <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                        </select>
                        @error('role')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label small-caps">Password <span class="text-danger">*</span></label>
                        <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" required>
                        @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
                <div class="mb-4">
                    <label class="form-label small-caps">Konfirmasi Password <span class="text-danger">*</span></label>
                    <input type="password" name="password_confirmation" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-primary fw-bold"><i class="fas fa-save me-1"></i> Simpan</button>
                <a href="{{ route('admin.users.index') }}" class="btn btn-secondary ms-2 fw-bold"><i class="fas fa-times me-1"></i> Batal</a>
            </form>
        </div>
    </div>
</div>
@endsection