@extends('layouts.dashboard')

@section('title', 'Daftar Laporan')

@section('dashboard-content')
<x-data-table>
    <x-slot:header>
        <h5 class="mb-0 fw-bold"><i class="fas fa-list text-orange me-2"></i>Daftar Laporan</h5>
    </x-slot:header>
    <x-slot:thead>
        <th class="ps-3 py-3 small-caps">ID</th>
        <th class="py-3 small-caps">Judul</th>
        <th class="py-3 small-caps">Pelapor</th>
        <th class="py-3 small-caps">Ruangan</th>
        <th class="py-3 small-caps text-center">Status</th>
        <th class="py-3 small-caps">SLA Deadline</th>
        <th class="py-3 small-caps text-center">Aksi</th>
    </x-slot:thead>
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
</x-data-table>
<div class="mt-3">{{ $reports->links() }}</div>
@endsection

@push('styles')
<style>
    .small-caps { font-size: 0.7rem; text-transform: uppercase; letter-spacing: 1px; font-weight: 800; color: #6c757d; }
</style>
@endpush
