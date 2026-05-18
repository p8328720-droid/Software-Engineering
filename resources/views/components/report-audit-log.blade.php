@props(['logs'])

<div class="card border-0 shadow-sm mb-4">
    <div class="card-header bg-white py-3">
        <h5 class="mb-0 small fw-bold"><i class="fas fa-history text-secondary me-2"></i>RIWAYAT & CATATAN</h5>
    </div>
    <div class="card-body p-0">
        <div class="list-group list-group-flush">
            @forelse($logs as $log)
                <div class="list-group-item border-0 border-bottom py-3">
                    <div class="d-flex w-100 justify-content-between mb-1">
                        <small class="fw-bold text-primary">{{ $log->user?->name ?? 'System' }}</small>
                        <small class="text-muted small">{{ $log->created_at->diffForHumans() }}</small>
                    </div>
                    <p class="mb-1 small">
                        Status: <span
                            class="fw-bold text-capitalize text-dark">{{ str_replace('_', ' ', $log->status_changed_to) }}</span>
                    </p>
                    @if($log->notes)
                        <div class="bg-light p-2 rounded small mt-1 border-start border-3 border-info">
                            <em>"{{ $log->notes }}"</em>
                        </div>
                    @endif
                </div>
            @empty
                <div class="p-4 text-center text-muted small">Belum ada riwayat aktivitas.</div>
            @endforelse
        </div>
    </div>
</div>
