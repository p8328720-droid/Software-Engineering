@extends('layouts.dashboard')

@section('title', 'Dashboard Pelapor')

@section('dashboard-content')
<div class="container-fluid px-0">
    {{-- ROW STATISTIK: KONSISTEN DENGAN ICON BESAR --}}
    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <div class="card text-center border-0 shadow-sm">
                <div class="card-body py-4">
                    <i class="fas fa-flag-checkered fa-3x text-orange mb-3"></i>
                    <h5 class="fw-bold text-muted small-caps">Total Laporan</h5>
                    <h2 class="text-orange fw-bold">{{ $stats['total_reports'] }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card text-center border-0 shadow-sm">
                <div class="card-body py-4">
                    <i class="fas fa-spinner fa-3x text-warning mb-3"></i>
                    <h5 class="fw-bold text-muted small-caps">Dalam Proses</h5>
                    <h2 class="fw-bold">{{ $stats['in_progress_reports'] }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card text-center border-0 shadow-sm">
                <div class="card-body py-4">
                    <i class="fas fa-check-circle fa-3x text-success mb-3"></i>
                    <h5 class="fw-bold text-muted small-caps">Selesai</h5>
                    <h2 class="fw-bold">{{ $stats['completed_reports'] }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card text-center border-0 shadow-sm">
                <div class="card-body py-4">
                    <i class="fas fa-clock fa-3x text-danger mb-3"></i>
                    <h5 class="fw-bold text-muted small-caps">Menunggu</h5>
                    <h2 class="fw-bold">{{ $stats['pending_reports'] }}</h2>
                </div>
            </div>
        </div>
    </div>

    {{-- TABEL LAPORAN TERBARU --}}
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3">
            <h5 class="mb-0 fw-bold"><i class="fas fa-history text-orange me-2"></i>Laporan Terbaru Anda</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr class="table-light">
                            <th class="ps-3 py-3 small-caps">ID</th>
                            <th class="py-3 small-caps">Judul</th>
                            <th class="py-3 small-caps">Ruangan</th>
                            <th class="py-3 small-caps text-center">Status</th>
                            <th class="py-3 small-caps text-center">Tanggal</th>
                            <th class="py-3 small-caps text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recent_reports as $report)
                        <tr>
                            <td class="ps-3 fw-bold">#{{ str_pad($report->id, 5, '0', STR_PAD_LEFT) }}</td>
                            <td>
                                <div class="fw-bold small">{{ $report->title }}</div>
                                <small class="text-muted">{{ $report->sla->facility_category ?? '-' }}</small>
                            </td>
                            <td class="small"><i class="fas fa-map-marker-alt text-danger me-1"></i> {{ $report->room->name }}</td>
                            <td class="text-center">
                                <x-report-status :status="$report->status" size="sm" />
                            </td>
                            <td class="text-center small">{{ $report->created_at->format('d/m/Y') }}</td>
                            <td class="text-center">
                                <a href="{{ route('pelapor.reports.show', $report->id) }}" class="btn btn-sm btn-outline-primary shadow-sm">
                                    Detail
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">Belum ada laporan yang dibuat.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <div class="text-center p-3">
                <a href="{{ route('pelapor.reports.create') }}" class="btn btn-primary btn-lg px-5 fw-bold shadow-sm">
                    <i class="fas fa-plus-circle me-2"></i> Buat Laporan Baru
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .small-caps { font-size: 0.7rem; text-transform: uppercase; letter-spacing: 1px; font-weight: 800; color: #6c757d; }
    .text-orange { color: #FF6B35 !important; }
    .card { border-radius: 12px; }
    .btn-primary { background-color: #FF6B35; border-color: #FF6B35; }
    .btn-primary:hover { background-color: #e55a2b; border-color: #e55a2b; }
</style>
@endpush