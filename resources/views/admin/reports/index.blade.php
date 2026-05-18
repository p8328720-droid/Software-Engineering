@extends('layouts.dashboard')

@section('title', 'Daftar Laporan')

@section('dashboard-content')
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
        <h5 class="mb-0 fw-bold"><i class="fas fa-list text-orange me-2"></i>Daftar Laporan</h5>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr class="table-light">
                        <th class="ps-3 py-3 small-caps">ID</th>
                        <th class="py-3 small-caps">Judul</th>
                        <th class="py-3 small-caps">Pelapor</th>
                        <th class="py-3 small-caps">Ruangan</th>
                        <th class="py-3 small-caps text-center">Status</th>
                        <th class="py-3 small-caps">SLA Deadline</th>
                        <th class="py-3 small-caps text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($reports as $report)
                    <tr>
                        <td class="ps-3 fw-bold text-dark">#{{ str_pad($report->id, 5, '0', STR_PAD_LEFT) }}</td>
                        <td class="small">{{ $report->title }}</td>
                        <td class="small">{{ $report->reporter->name ?? 'N/A' }}</td>
                        <td class="small">{{ $report->room->name ?? 'N/A' }}</td>
                        <td class="text-center"><x-report-status :status="$report->status" size="sm" /></td>
                        <td class="small {{ $report->sla_deadline->isPast() ? 'text-danger fw-bold' : '' }}">
                            {{ $report->sla_deadline->format('d/m/Y H:i') }}
                        </td>
                        <td class="text-center">
                            <a href="{{ route('admin.reports.show', $report->id) }}" class="btn btn-sm btn-outline-primary">Detail</a>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="7" class="text-center py-5 text-muted">Belum ada laporan</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-3">{{ $reports->links() }}</div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .small-caps { font-size: 0.7rem; text-transform: uppercase; letter-spacing: 1px; font-weight: 800; color: #6c757d; }
</style>
@endpush
