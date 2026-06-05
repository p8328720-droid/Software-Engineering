@props(['report'])

<div class="card border-0 shadow-sm mb-4">
    <div class="card-header bg-white py-3">
        <h5 class="mb-0 small fw-bold">STATUS & WAKTU</h5>
    </div>
    <div class="card-body">
        <div class="mb-3 pb-3 border-bottom">
            <label class="text-secondary small fw-bold text-uppercase d-block mb-1">Status</label>
            <x-report-status :status="$report->status" />
        </div>
        <div class="mb-3">
            <label class="text-secondary small fw-bold text-uppercase d-block mb-1">Dilaporkan</label>
            <span class="fw-medium text-dark">{{ $report->created_at->format('d M Y, H:i') }}</span>
        </div>
        <div class="mb-0">
            <label class="text-secondary small fw-bold text-uppercase d-block mb-1">Batas SLA (Deadline)</label>
            <span class="text-danger fw-bold">
                {{ $report->sla_deadline ? $report->sla_deadline->format('d M Y, H:i') : '-' }}</span>
        </div>
    </div>
</div>
