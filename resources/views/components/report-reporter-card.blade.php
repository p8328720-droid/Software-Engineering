@props(['reporter'])

<div class="card border-0 shadow-sm mb-4">
    <div class="card-header bg-white py-3">
        <h5 class="mb-0 fs-6 fw-bold">Informasi Pelapor</h5>
    </div>
    <div class="card-body text-center">
        <div class="mb-3">
            <div class="rounded-circle bg-light d-flex align-items-center justify-content-center mx-auto" style="width: 64px; height: 64px;">
                <span class="fs-4 fw-bold text-secondary">{{ substr($reporter->name, 0, 1) }}</span>
            </div>
        </div>
        <h6 class="fw-bold mb-0 text-dark">{{ $reporter->name ?? 'Unknown' }}</h6>
        <p class="text-muted small text-capitalize mb-3">{{ $reporter->role ?? 'Mahasiswa' }}</p>
        <hr>
        <div class="text-start">
            <p class="small mb-2 text-truncate">Email: {{ $reporter->email ?? '-' }}</p>
            <p class="small mb-0">Telepon: {{ $reporter->phone ?? '-' }}</p>
        </div>
    </div>
</div>
