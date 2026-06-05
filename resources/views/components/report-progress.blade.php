@props(['status'])

@php
    $currentLevel = $status == 'completed' ? 3 : ($status == 'in_progress' ? 2 : 1);
@endphp

<div class="card border-0 shadow-sm mb-4">
    <div class="card-header bg-white py-3">
        <h5 class="mb-0 fw-bold">Status Tracking Laporan</h5>
    </div>
    <div class="card-body py-4">
        @if($status === 'rejected')
            <div class="alert alert-danger text-center mb-0">Laporan ini telah ditolak.</div>
        @else
            <div class="progress-tracker">
                <div class="step {{ $currentLevel > 1 ? 'completed' : 'active' }}">
                    <div class="step-icon">
                        <i class="fas fa-{{ $currentLevel > 1 ? 'check' : 'clock' }}"></i>
                    </div>
                    <div class="step-label">Menunggu Verifikasi</div>
                </div>

                <div class="step {{ $currentLevel > 2 ? 'completed' : ($currentLevel == 2 ? 'active' : '') }}">
                    <div class="step-icon">
                        <i class="fas fa-{{ $currentLevel > 2 ? 'check' : ($currentLevel == 2 ? 'spinner fa-spin' : 'cog') }}"></i>
                    </div>
                    <div class="step-label">Dalam Proses</div>
                </div>

                <div class="step {{ $currentLevel == 3 ? 'completed' : '' }}">
                    <div class="step-icon">
                        <i class="fas fa-{{ $currentLevel == 3 ? 'check' : 'flag-checkered' }}"></i>
                    </div>
                    <div class="step-label">Selesai</div>
                </div>
            </div>
        @endif
    </div>
</div>

@push('styles')
<style>
    .progress-tracker {
        display: flex;
        justify-content: space-between;
        margin: 20px 0;
        position: relative;
    }

    .progress-tracker::before {
        content: '';
        position: absolute;
        top: 25px;
        left: 10%;
        right: 10%;
        height: 3px;
        background: #e0e0e0;
        z-index: 1;
    }

    .step {
        text-align: center;
        position: relative;
        z-index: 2;
        flex: 1;
    }

    .step-icon {
        width: 50px;
        height: 50px;
        background: white;
        border: 3px solid #e0e0e0;
        border-radius: 50%;
        margin: 0 auto 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
        color: #a0a0a0;
        transition: 0.3s;
    }

    .step.completed .step-icon {
        background: #28a745;
        border-color: #28a745;
        color: white;
    }

    .step.active .step-icon {
        border-color: #FF6B35;
        background: #FF6B35;
        color: white;
    }

    .step-label {
        font-weight: 600;
        font-size: 13px;
        color: #333;
    }
</style>
@endpush
