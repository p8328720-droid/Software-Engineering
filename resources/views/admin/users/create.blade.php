@extends('layouts.admin')
 
@section('title', 'Tambah User')
 
@section('admin-content')
 
<div class="d-flex justify-content-between flex-wrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2"><i class="fas fa-user-plus me-2 text-orange"></i>Tambah User Baru</h1>
    <a href="{{ route('admin.users') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left me-1"></i> Kembali
    </a>
</div>
 
<div class="card border-0">
    <div class="card-body">
        <form action="{{ route('admin.users.store') }}" method="POST">
            @csrf
 
            {{-- ===== INFORMASI DASAR (semua role) ===== --}}
            <h6 class="text-muted text-uppercase fw-semibold mb-3 border-bottom pb-2">
                <i class="fas fa-info-circle me-1"></i> Informasi Dasar
            </h6>
 
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                    <input type="text" name="name"
                        class="form-control @error('name') is-invalid @enderror"
                        value="{{ old('name') }}" required>
                    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Email <span class="text-danger">*</span></label>
                    <input type="email" name="email"
                        class="form-control @error('email') is-invalid @enderror"
                        value="{{ old('email') }}" required>
                    @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>
 
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">No. Telepon</label>
                    <input type="text" name="phone"
                        class="form-control @error('phone') is-invalid @enderror"
                        value="{{ old('phone') }}">
                    @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Role <span class="text-danger">*</span></label>
                    <select name="role" id="roleSelect"
                        class="form-select @error('role') is-invalid @enderror" required>
                        <option value="mahasiswa" {{ old('role', 'mahasiswa') == 'mahasiswa' ? 'selected' : '' }}>
                            Mahasiswa
                        </option>
                        <option value="teknisi" {{ old('role') == 'teknisi' ? 'selected' : '' }}>
                            Teknisi
                        </option>
                        <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>
                            Admin
                        </option>
                    </select>
                    @error('role')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>
 
            {{-- ===== SECTION MAHASISWA ===== --}}
            <div id="section-mahasiswa">
                <h6 class="text-muted text-uppercase fw-semibold mb-3 border-bottom pb-2 mt-2">
                    <i class="fas fa-graduation-cap me-1"></i> Data Mahasiswa
                </h6>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">NIM</label>
                        <input type="text" name="student_id"
                            class="form-control @error('student_id') is-invalid @enderror"
                            value="{{ old('student_id') }}"
                            placeholder="Contoh: 2021010001">
                        @error('student_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Fakultas</label>
                        <select name="faculty" class="form-select @error('faculty') is-invalid @enderror">
                            <option value="">Pilih Fakultas</option>
                            @foreach(['Fakultas Teknik','Fakultas Ekonomi','Fakultas Hukum','Fakultas Kedokteran','Fakultas Ilmu Komputer'] as $fak)
                                <option value="{{ $fak }}"
                                    {{ old('faculty') == $fak ? 'selected' : '' }}>
                                    {{ $fak }}
                                </option>
                            @endforeach
                        </select>
                        @error('faculty')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Jurusan / Program Studi</label>
                        <input type="text" name="major"
                            class="form-control @error('major') is-invalid @enderror"
                            value="{{ old('major') }}"
                            placeholder="Contoh: Teknik Informatika">
                        @error('major')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
            </div>
 
            {{-- ===== SECTION TEKNISI ===== --}}
            <div id="section-teknisi" style="display:none;">
                <h6 class="text-muted text-uppercase fw-semibold mb-3 border-bottom pb-2 mt-2">
                    <i class="fas fa-tools me-1"></i> Data Teknisi
                </h6>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">ID Teknisi / NIP</label>
                        <input type="text" name="student_id"
                            class="form-control"
                            value="{{ old('student_id') }}"
                            placeholder="Contoh: TK-2024-001">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Spesialisasi</label>
                        <select name="major" class="form-select @error('major') is-invalid @enderror">
                            <option value="">Pilih Spesialisasi</option>
                            @foreach([
                                'Jaringan & Server',
                                'Hardware & Komputer',
                                'Software & Sistem Operasi',
                                'Listrik & Elektronik',
                                'AC & Pendingin',
                                'Umum',
                            ] as $spesialis)
                                <option value="{{ $spesialis }}"
                                    {{ old('major') == $spesialis ? 'selected' : '' }}>
                                    {{ $spesialis }}
                                </option>
                            @endforeach
                        </select>
                        @error('major')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
            </div>
 
            {{-- ===== SECTION ADMIN ===== --}}
            <div id="section-admin" style="display:none;">
                <h6 class="text-muted text-uppercase fw-semibold mb-3 border-bottom pb-2 mt-2">
                    <i class="fas fa-shield-alt me-1"></i> Data Admin
                </h6>
                <div class="alert alert-info py-2 mb-3">
                    <i class="fas fa-info-circle me-1"></i>
                    Admin memiliki akses penuh ke seluruh sistem. Tidak ada data tambahan yang diperlukan.
                </div>
            </div>
 
            {{-- ===== PASSWORD (semua role) ===== --}}
            <h6 class="text-muted text-uppercase fw-semibold mb-3 border-bottom pb-2 mt-2">
                <i class="fas fa-lock me-1"></i> Password
            </h6>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Password <span class="text-danger">*</span></label>
                    <input type="password" name="password"
                        class="form-control @error('password') is-invalid @enderror"
                        autocomplete="new-password" required>
                    @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Konfirmasi Password <span class="text-danger">*</span></label>
                    <input type="password" name="password_confirmation" class="form-control" required>
                </div>
            </div>
 
            <div class="d-flex gap-2 mt-2">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-1"></i> Simpan
                </button>
                <a href="{{ route('admin.users') }}" class="btn btn-secondary">
                    <i class="fas fa-times me-1"></i> Batal
                </a>
            </div>
 
        </form>
    </div>
</div>
 
@endsection
 
@push('scripts')
<script>
(function () {
    const roleSelect = document.getElementById('roleSelect');
    const sections   = {
        mahasiswa : document.getElementById('section-mahasiswa'),
        teknisi   : document.getElementById('section-teknisi'),
        admin     : document.getElementById('section-admin'),
    };
 
    function showSection(role) {
        Object.entries(sections).forEach(([key, el]) => {
            const isActive = key === role;
            el.style.display = isActive ? '' : 'none';
            el.querySelectorAll('input, select').forEach(function (field) {
                field.disabled = !isActive;
            });
        });
    }
 
    showSection(roleSelect.value);
 
    roleSelect.addEventListener('change', function () {
        showSection(this.value);
    });
})();
</script>
@endpush