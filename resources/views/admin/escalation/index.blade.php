@extends('layouts.dashboard')

@section('title', 'Eskalasi Laporan')

@section('dashboard-content')
<div class="container-fluid px-0">
    <div class="d-flex justify-content-between flex-wrap align-items-center mb-4">
        <h4 class="fw-bold"><i class="fas fa-arrow-up text-danger me-2"></i>Eskalasi Laporan</h4>
    </div>

    <div class="alert alert-warning border-0 shadow-sm mb-4">
        <i class="fas fa-info-circle me-2"></i>Laporan yang melewati batas SLA akan muncul di sini. Segera lakukan eskalasi ke manajemen terkait.
    </div>

    <x-data-table title="Daftar Laporan Ter-Eskalasi">
                <x-slot:thead>
                    <th class="ps-3 py-3 small-caps">No. Laporan</th>
                    <th class="py-3 small-caps">Judul</th>
                    <th class="py-3 small-caps">Ruangan</th>
                    <th class="py-3 small-caps">Lokasi</th>
                    <th class="py-3 small-caps">SLA Deadline</th>
                    <th class="py-3 small-caps">Keterlambatan</th>
                    <th class="py-3 small-caps text-center">Aksi</th>
                </x-slot:thead>
                @forelse($escalatedReports as $report)
                <tr>
                    <td class="ps-3 fw-bold small text-dark">#{{ str_pad($report->id, 5, '0', STR_PAD_LEFT) }}</td>
                    <td class="small">{{ $report->title ?? $report->description ?? 'N/A' }}</td>
                    <td class="small">{{ $report->room->name ?? 'N/A' }}</td>
                    <td class="small">{{ $report->location_detail ?? $report->room->building ?? 'N/A' }}</td>
                    <td class="small">{{ $report->sla_deadline ? $report->sla_deadline->format('d/m/Y H:i') : 'N/A' }}</td>
                    <td class="small">
                        @if($report->sla_deadline)
                            <span class="text-danger fw-bold">{{ $report->sla_deadline->diffInHours(now()) }} jam</span>
                        @else
                            <span class="text-muted">-</span>
                        @endif
                    </td>
                    <td class="text-center">
                        <form action="/admin/escalation/{{ $report->id }}/escalate" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-light border p-1 px-2" onclick="return confirm('Yakin ingin mengeskalasi laporan ini?')">
                                <i class="fas fa-arrow-up small text-danger"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center py-4 small text-muted">
                        <i class="fas fa-check-circle text-success mb-2 d-block fa-2x"></i>
                        Tidak ada laporan yang perlu dieskalasi
                    </td>
                </tr>
                @endforelse
            </x-data-table>
</div>
@endsection