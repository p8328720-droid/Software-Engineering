@extends('layouts.dashboard')

@section('title', 'Tambah Ruangan')

@section('dashboard-content')
    <div class="container-fluid px-0">
        <div class="d-flex justify-content-between align-items-center pt-3 pb-2 mb-4 border-bottom">
            <h1 class="h4 fw-bold text-dark"><i class="fas fa-plus-circle text-orange me-2"></i>Tambah Ruangan Baru</h1>
            <a href="{{ route('admin.rooms.index') }}" class="btn btn-light btn-sm border fw-bold small-caps px-3">
                <i class="fas fa-arrow-left me-1"></i> Kembali
            </a>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <form action="{{ route('admin.rooms.store') }}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <label class="form-label small-caps fw-bold text-muted">Nama Ruangan <span
                                    class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                                value="{{ old('name') }}" placeholder="Contoh: Lab Komputer 1" required>
                            @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6 mb-4">
                            <label class="form-label small-caps fw-bold text-muted">Lokasi / Lantai</label>
                            <input type="text" name="location" class="form-control @error('location') is-invalid @enderror"
                                value="{{ old('location') }}" placeholder="Contoh: Gedung A, Lantai 2">
                            @error('location')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label small-caps fw-bold text-muted">Deskripsi Ruangan</label>
                        <textarea name="description" class="form-control" rows="3"
                            placeholder="Tambahkan info tambahan ruangan...">{{ old('description') }}</textarea>
                    </div>

                    <div class="pt-3 border-top d-flex gap-2">
                        <button type="submit" class="btn btn-primary px-4 fw-bold shadow-sm">
                            <i class="fas fa-save me-2"></i> SIMPAN RUANGAN
                        </button>
                        <a href="{{ route('admin.rooms.index') }}" class="btn btn-light border px-4">BATAL</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection