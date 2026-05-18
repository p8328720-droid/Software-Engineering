@props(['report'])

<div class="card border-0 shadow-sm mb-4">
    <div class="card-header bg-white py-3">
        <h5 class="mb-0 fs-6 fw-bold"><i class="fas fa-info-circle text-info me-2"></i>Informasi Kerusakan</h5>
    </div>
    <div class="card-body">
        <div class="row mb-4">
            <div class="col-md-6">
                <label class="text-muted small fw-bold text-uppercase">Judul Masalah</label>
                <p class="mb-0 fw-medium fs-5 text-dark">{{ $report->title }}</p>
            </div>
            <div class="col-md-6">
                <label class="text-muted small fw-bold text-uppercase">Ruangan / Lokasi</label>
                <p class="mb-0 fw-medium"><i class="fas fa-map-marker-alt text-danger me-1"></i>
                    {{ $report->room->name ?? '-' }}</p>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-md-6">
                <label class="text-muted small fw-bold text-uppercase">Kategori Kerusakan</label>
                <p class="mb-0"><span
                        class="badge bg-secondary">{{ $report->sla->facility_category ?? '-' }}</span></p>
            </div>
            <div class="col-md-6">
                <label class="text-muted small fw-bold text-uppercase">Tingkat Urgensi</label>
                <div class="mt-1">
                    @if($report->urgency == 'high') <span class="badge bg-danger px-3">HIGH / EMERGENCY</span>
                    @elseif($report->urgency == 'medium') <span class="badge bg-warning text-dark px-3">MEDIUM</span>
                    @else <span class="badge bg-info text-dark px-3">LOW</span> @endif
                </div>
            </div>
        </div>

        <div class="mb-4">
            <label class="text-muted small fw-bold text-uppercase">Deskripsi Laporan</label>
            <p class="bg-light p-3 rounded border mb-0 text-dark">{{ $report->description }}</p>
        </div>

        @if($report->image_path)
            <div class="mb-0">
                <label class="text-muted small fw-bold text-uppercase mb-2">Bukti Foto Kerusakan</label>
                <img src="{{ asset('storage/' . $report->image_path) }}"
                    class="img-fluid rounded border shadow-sm d-block" style="max-height: 400px;">
            </div>
        @endif
    </div>
</div>
