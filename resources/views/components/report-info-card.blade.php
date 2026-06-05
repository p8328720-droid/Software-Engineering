@props(['report'])

<div class="card border-0 shadow-sm mb-4">
    <div class="card-header bg-white py-3">
        <h5 class="mb-0 fs-6 fw-bold">Informasi Kerusakan</h5>
    </div>
    <div class="card-body">
        <div class="row mb-4">
            <div class="col-md-6">
                <label class="text-secondary small fw-bold text-uppercase mb-1">Judul Masalah</label>
                <p class="mb-0 fw-medium fs-5 text-dark">{{ $report->title }}</p>
            </div>
            <div class="col-md-6">
                <label class="text-secondary small fw-bold text-uppercase mb-1">Ruangan / Lokasi</label>
                <p class="mb-0 fw-medium">{{ $report->room->name ?? '-' }}</p>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-md-6">
                <label class="text-secondary small fw-bold text-uppercase mb-1">Kategori Kerusakan</label>
                <p class="mb-0"><span
                        class="badge bg-secondary">{{ $report->sla->facility_category ?? '-' }}</span></p>
            </div>
            <div class="col-md-6">
                <label class="text-secondary small fw-bold text-uppercase mb-1">Tingkat Urgensi</label>
                <div class="mt-1">
                    @if($report->urgency == 'high') <span class="badge bg-danger px-3">HIGH</span>
                    @elseif($report->urgency == 'medium') <span class="badge bg-warning text-dark px-3">MEDIUM</span>
                    @else <span class="badge bg-info text-dark px-3">LOW</span> @endif
                </div>
            </div>
        </div>

        <div class="mb-4">
            <label class="text-secondary small fw-bold text-uppercase mb-1">Deskripsi Laporan</label>
            <p class="bg-light p-3 rounded border mb-0 text-dark">{{ $report->description }}</p>
        </div>

        <div class="mb-4">
            <label class="text-secondary small fw-bold text-uppercase mb-2">Informasi Pelapor</label>
            <div class="card border-0 bg-light p-3">
                <div class="d-flex flex-column gap-2">
                    <div>
                        <span class="text-muted small d-block">Nama Lengkap</span>
                        <span class="fw-bold text-dark">{{ $report->reporter->name ?? '-' }}</span>
                    </div>
                    <div class="d-flex gap-4">
                        <div class="flex-grow-1">
                            <span class="text-muted small d-block">Email</span>
                            <a href="mailto:{{ $report->reporter->email ?? '#' }}" class="text-decoration-none fw-bold text-dark">{{ $report->reporter->email ?? '-' }}</a>
                        </div>
                        <div class="flex-grow-1">
                            <span class="text-muted small d-block">Telepon</span>
                            <a href="tel:{{ $report->reporter->phone ?? '#' }}" class="text-decoration-none fw-bold text-dark">{{ $report->reporter->phone ?? '-' }}</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @if($report->image_path)
            <div class="mb-0">
                <label class="text-secondary small fw-bold text-uppercase mb-2">Bukti Foto</label>
                <img src="{{ asset('storage/' . $report->image_path) }}"
                    class="img-fluid rounded border shadow-sm d-block" style="max-height: 400px;">
            </div>
        @endif

    </div>
</div>

