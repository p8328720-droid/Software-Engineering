@extends('layouts.dashboard')

@section('title', 'Edit Ruangan')

@section('dashboard-content')
    <div class="container-fluid px-0">
        {{-- HEADER --}}
        <div class="d-flex justify-content-between align-items-center pt-3 pb-2 mb-4 border-bottom">
            <h1 class="h4 fw-bold text-dark">
                <i class="fas fa-edit text-orange me-2"></i>Edit Data Ruangan
            </h1>
            <a href="{{ route('admin.rooms.index') }}" class="btn btn-light btn-sm border fw-bold small-caps px-3">
                <i class="fas fa-arrow-left me-1"></i> Kembali
            </a>
        </div>

        {{-- FORM CARD --}}
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <form action="{{ route('admin.rooms.update', $room->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        {{-- Nama Ruangan --}}
                        <div class="col-md-6 mb-4">
                            <label class="form-label small-caps fw-bold text-muted">Nama Ruangan <span
                                    class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                                value="{{ old('name', $room->name) }}" placeholder="Contoh: Lab Komputer 1" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Lokasi / Lantai --}}
                        <div class="col-md-6 mb-4">
                            <label class="form-label small-caps fw-bold text-muted">Lokasi / Lantai</label>
                            <input type="text" name="location" class="form-control @error('location') is-invalid @enderror"
                                value="{{ old('location', $room->location) }}" placeholder="Contoh: Gedung A, Lantai 2">
                            @error('location')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    {{-- Deskripsi --}}
                    <div class="mb-4">
                        <label class="form-label small-caps fw-bold text-muted">Deskripsi Ruangan</label>
                        <textarea name="description" class="form-control @error('description') is-invalid @enderror"
                            rows="4"
                            placeholder="Tambahkan detail atau catatan mengenai ruangan ini...">{{ old('description', $room->description) }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- INFO FOOTER --}}
                    <div class="alert alert-light border-0 bg-light small text-muted mb-4">
                        <i class="fas fa-info-circle me-2 text-info"></i>
                        Terakhir diperbarui pada: {{ $room->updated_at->format('d M Y, H:i') }} WIB
                    </div>

                    {{-- ACTION BUTTONS --}}
                    <div class="pt-3 border-top d-flex gap-2">
                        <button type="submit" class="btn btn-primary px-4 fw-bold shadow-sm">
                            <i class="fas fa-save me-2"></i> SIMPAN PERUBAHAN
                        </button>
                        <a href="{{ route('admin.rooms.index') }}" class="btn btn-light border px-4">BATAL</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        /* KONSISTENSI VISUAL GLOBAL */
        .small-caps {
            font-size: 0.65rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-weight: 800;
            color: #6c757d;
        }

        .text-orange {
            color: #FF6B35 !important;
        }

        /* Input Styling */
        .form-control:focus,
        .form-select:focus {
            border-color: #FF6B35;
            box-shadow: 0 0 0 0.25rem rgba(255, 107, 53, 0.1);
        }

        .card {
            border-radius: 12px;
        }

        /* Button Primary Custom */
        .btn-primary {
            background-color: #FF6B35;
            border-color: #FF6B35;
        }

        .btn-primary:hover {
            background-color: #e55a2b;
            border-color: #e55a2b;
        }
    </style>
@endpush