@extends('layouts.admin')

@section('title', 'Edit Aturan SLA')

@section('admin-content')
<div class="d-flex justify-content-between flex-wrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2"><i class="fas fa-edit me-2 text-orange"></i>Edit Aturan SLA</h1>
    <a href="{{ route('admin.sla') }}" class="btn btn-secondary"><i class="fas fa-arrow-left me-1"></i> Kembali</a>
</div>

<div class="card border-0">
    <div class="card-body">
        <form action="{{ route('admin.sla.update', $sla->id) }}" method="POST">
            @csrf @method('PUT')
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Kategori Fasilitas</label>
                    <input type="text" class="form-control" value="{{ $sla->facility_category }}" disabled>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Tingkat Urgensi</label>
                    <input type="text" class="form-control" value="{{ $sla->urgency == 'low' ? 'Rendah' : ($sla->urgency == 'medium' ? 'Sedang' : 'Tinggi') }}" disabled>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Response Time (jam) <span class="text-danger">*</span></label>
                    <input type="number" name="response_hours" class="form-control @error('response_hours') is-invalid @enderror" value="{{ old('response_hours', $sla->response_hours) }}" min="1" max="72" required>
                    <small class="text-muted">Batas waktu respons dari laporan diterima</small>
                    @error('response_hours')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Resolution Time (jam) <span class="text-danger">*</span></label>
                    <input type="number" name="resolution_hours" class="form-control @error('resolution_hours') is-invalid @enderror" value="{{ old('resolution_hours', $sla->resolution_hours) }}" min="1" max="168" required>
                    <small class="text-muted">Batas waktu penyelesaian perbaikan</small>
                    @error('resolution_hours')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label">Status Aktif</label>
                <select name="is_active" class="form-select @error('is_active') is-invalid @enderror">
                    <option value="1" {{ old('is_active', $sla->is_active) == 1 ? 'selected' : '' }}>Aktif</option>
                    <option value="0" {{ old('is_active', $sla->is_active) == 0 ? 'selected' : '' }}>Nonaktif</option>
                </select>
                @error('is_active')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="alert alert-warning"><i class="fas fa-exclamation-triangle me-2"></i> Perubahan aturan SLA akan berlaku untuk laporan baru setelah perubahan disimpan.</div>
            <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i> Simpan Perubahan</button>
            <a href="{{ route('admin.sla') }}" class="btn btn-secondary"><i class="fas fa-times me-1"></i> Batal</a>
        </form>
    </div>
</div>
@endsection