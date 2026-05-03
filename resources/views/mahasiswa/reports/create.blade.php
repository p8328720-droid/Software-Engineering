@extends('layouts.mahasiswa')

@section('title', 'Buat Laporan')

@section('mahasiswa-content')
<div class="card border    <    <div class="card-header bg-white">
        <h5 class="mb-0"><i class="fas fa-plus-circle text-orange me-2"></i>Buat Laporan Kerusakan Baru</h5>
    </div>
    <div class="card-body">
        @if($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('mahasiswa.reports.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="mb-3">
                <label class="form-label">Judul Laporan <span class="text-danger">*</span></label>
                <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title') }}" required>
                @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            
            <div class="mb-3">
                <label class="form-label">Fasilitas <span class="text-danger">*</span></label>
                <select name="facility_id" class="form-select @error('facility_id') is-invalid @enderror" required>
                    <option value="">-- Pilih Fasilitas --</option>
                    @if(isset($facilities) && $facilities->count() > 0)
                        @foreach($facilities as $facility)
                            <option value="{{ $facility->id }}" {{ old('facility_id') == $facility->id ? 'selected' : '' }}>
                                [{{ $facility->category }}] {{ $facility->name }} - {{ $facility->location }}
                            </option>
                        @endforeach
                    @else
                        <option value="" disabled>Belum ada fasilitas. Hubungi admin.</option>
                    @endif
                </select>
                @error('facility_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="mb-3">
                <label class="form-label">Lokasi Detail <span class="text-danger">*</span></label>
                <input type="text" name="location_detail" class="form-control @error('location_detail') is-invalid @enderror" value="{{ old('location_detail') }}" placeholder="Contoh: Gedung A, Lantai 2, Ruang 201" required>
                @error('location_detail')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            
            <div class="mb-3">
                <label class="form-label">Tingkat Urgensi <span class="text-danger">*</span></label>
                <select name="urgency" class="form-select @error('urgency') is-invalid @enderror" required>
                    <option value="low" {{ old('urgency') == 'low' ? 'selected' : '' }}>Rendah - Tidak mengganggu aktivitas</option>
                    <option value="medium" {{ old('urgency') == 'medium' ? 'selected' : '' }}>Sedang - Mengganggu namun masih bisa digunakan</option>
                    <option value="high" {{ old('urgency') == 'high' ? 'selected' : '' }}>Tinggi - Tidak bisa digunakan, perlu segera diperbaiki</option>
                </select>
                @error('urgency')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            
            <div class="mb-3">
                <label class="form-label">Deskripsi Masalah <span class="text-danger">*</span></label>
                <textarea name="description" class="form-control @error('description') is-invalid @enderror" rows="5" required>{{ old('description') }}</textarea>
                @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            
            <div class="mb-3">
                <label class="form-label">Bukti Foto (Opsional)</label>
                <input type="file" name="image" class="form-control @error('image') is-invalid @enderror" accept="image/*">
                <small class="text-muted">Format: JPG, PNG, JPEG. Maksimal 2MB</small>
                @error('image')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            
            <div class="alert alert-info">
                <i class="fas fa-info-circle me-2"></i>
                <strong>Informasi SLA:</strong> Laporan akan segera diproses oleh teknisi.
            </div>
            
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-paper-plane me-2"></i> Kirim Laporan
            </button>
  <i class="fas fa-paper-plane me-2"></i> Kirim Laporan
            </button>
            <a href="{{ route('mahasiswa.dashboard') }}" class="btn btn-secondary">Batal</a>
        </form>
    </div>
</div>
@endsection