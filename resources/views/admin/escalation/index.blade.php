@extends('layouts.dashboard')

@section('title', 'Eskalasi Laporan')

@section('dashboard-content')
<div class="d-flex justify-content-between flex-wrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2"><i class="fas fa-arrow-up text-danger me-2"></i>Eskalasi Laporan</h1>
</div>

<div class="alert alert-warning">
    <i class="fas fa-info-circle me-2"></i>Laporan yang melewati batas SLA akan muncul di sini. Segera lakukan eskalasi ke manajemen terkait.
</div>

<div class="card border-0">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr class="table-light">
                        <th>No. Laporan</th>
                        <th>Judul</th>
                        <th>Ruangan</th>
                        <th>Lokasi</th>
                        <th>SLA Deadline</th>
                        <th>Keterlambatan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($escalatedReports as $report)
                    <tr>
                        <td>#{{ str_pad($report->id, 5, '0', STR_PAD_LEFT) }}</td>
                        <td>{{ $report->title ?? $report->description ?? 'N/A' }}</td>
                        <td>{{ $report->room->name ?? 'N/A' }}</td>
                        <td>{{ $report->location_detail ?? $report->room->building ?? 'N/A' }}</td>
                        <td>{{ $report->sla_deadline ? $report->sla_deadline->format('d/m/Y H:i') : 'N/A' }}</td>
                        <td>
                            @if($report->sla_deadline)
                                <span class="text-danger">{{ $report->sla_deadline->diffInHours(now()) }} jam</span>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td>
                            <form action="/admin/escalation/{{ $report->id }}/escalate" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin mengeskalasi laporan ini?')">
                                    <i class="fas fa-arrow-up"></i> Eskalasi
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-4">
                            <i class="fas fa-check-circle fa-3x text-success mb-2 d-block"></i>
                            Tidak ada laporan yang perlu dieskalasi
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection