@extends('layouts.dashboard')

@section('title', 'Buat Laporan')

@section('dashboard-content')
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3">
            <h5 class="mb-0 fw-bold"><i class="fas fa-plus-circle text-orange me-2"></i>Buat Laporan Kerusakan Baru</h5>
        </div>
        <div class="card-body">
            @if($errors->any())
                <div class="alert alert-danger border-0 shadow-sm">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('pelapor.reports.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                {{-- Judul Laporan --}}
                <div class="mb-3">
                    <label class="form-label fw-bold">Judul Laporan <span class="text-danger">*</span></label>
                    <input type="text" name="title" class="form-control @error('title') is-invalid @enderror"
                        value="{{ old('title') }}" placeholder="Contoh: AC Rusak, Proyektor Mati" required>
                    @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                {{-- Baris 1: Ruangan & Kategori Fasilitas --}}
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Pilih Ruangan <span class="text-danger">*</span></label>
                        <select name="room_id" class="form-select @error('room_id') is-invalid @enderror" required>
                            <option value="">-- Pilih Ruangan --</option>
                            @foreach($rooms as $room)
                                <option value="{{ $room->id }}" {{ old('room_id') == $room->id ? 'selected' : '' }}>
                                    {{ $room->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('room_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Kategori Fasilitas <span class="text-danger">*</span></label>
                        <select name="facility_category" class="form-select @error('facility_category') is-invalid @enderror" required>
                            <option value="">-- Pilih Kategori --</option>
                            @foreach($categories as $categoryName)
                                <option value="{{ $categoryName }}" {{ old('facility_category') == $categoryName ? 'selected' : '' }}>
                                    {{ $categoryName }}
                                </option>
                            @endforeach
                        </select>
                        @error('facility_category')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>

                {{-- Baris 2: Urgensi & Bukti Foto --}}
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Tingkat Urgensi <span class="text-danger">*</span></label>
                        <select name="urgency" class="form-select @error('urgency') is-invalid @enderror" required>
                            <option value="low" {{ old('urgency') == 'low' ? 'selected' : '' }}>Low (Ringan)</option>
                            <option value="medium" {{ old('urgency') == 'medium' ? 'selected' : '' }}>Medium (Sedang)</option>
                            <option value="high" {{ old('urgency') == 'high' ? 'selected' : '' }}>High (Darurat)</option>
                        </select>
                        @error('urgency')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Bukti Foto (Opsional)</label>
                        <input type="file" name="image" class="form-control @error('image') is-invalid @enderror"
                            accept="image/*">
                        <small class="text-muted">Format: JPG, PNG, JPEG. Maks 2MB</small>
                        @error('image')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>

                {{-- Deskripsi --}}
                <div class="mb-4">
                    <label class="form-label fw-bold">Deskripsi Masalah <span class="text-danger">*</span></label>
                    <textarea name="description" class="form-control @error('description') is-invalid @enderror" rows="5"
                        placeholder="Jelaskan detail kerusakannya..." required>{{ old('description') }}</textarea>
                    @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                {{-- Alert Info SLA --}}
                <div class="alert alert-info border-0 shadow-sm d-flex align-items-center mb-4">
                    <i class="fas fa-info-circle fa-lg me-3"></i>
                    <div>
                        <strong>Informasi SLA:</strong> Target waktu penyelesaian (Deadline) akan ditentukan secara otomatis oleh sistem berdasarkan kombinasi <strong>Kategori</strong> dan <strong>Urgensi</strong> yang Anda pilih.
                    </div>
                </div>

                {{-- Tombol Aksi --}}
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary px-4 fw-bold shadow-sm">
                        <i class="fas fa-paper-plane me-2"></i> Kirim Laporan
                    </button>
                    <a href="{{ route('pelapor.reports.index') }}" class="btn btn-light px-4 border shadow-sm">Batal</a>
                </div>
            </form>
        </div>
    </div>
@endsection