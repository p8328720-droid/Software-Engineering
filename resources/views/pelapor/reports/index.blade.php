@extends('layouts.dashboard')

@section('title', 'Daftar Laporan')

@section('dashboard-content')
<div class="container-fluid px-0">
    <x-data-table>
        <x-slot:header>
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-bold"><i class="fas fa-list text-orange me-2"></i>Daftar Laporan Saya</h5>
                <a href="{{ route('pelapor.reports.create') }}" class="btn btn-primary fw-bold shadow-sm">Buat Laporan</a>
            </div>
        </x-slot:header>
        <x-slot:thead>
            <th class="ps-3 py-3 small-caps">ID</th>
            <th class="py-3 small-caps">Judul</th>
            <th class="py-3 small-caps">Ruangan</th>
            <th class="py-3 small-caps text-center">Status</th>
            <th class="py-3 small-caps">Tanggal</th>
            <th class="py-3 small-caps text-center">Aksi</th>
        </x-slot:thead>
        @forelse($reports as $report)
        <tr>
            <td class="ps-3 fw-bold">#{{ str_pad($report->id, 5, '0', STR_PAD_LEFT) }}</td>
            <td class="small">{{ $report->title }}</td>
            <td class="small">{{ $report->room->name }}</td>
            <td class="text-center"><x-report-status :status="$report->status" size="sm" /></td>
            <td class="small">{{ $report->created_at->format('d/m/Y') }}</td>
            <td class="text-center">
                <a href="{{ route('pelapor.reports.show', $report) }}" class="btn btn-sm btn-light border p-1 px-2">Detail</a>
            </td>
        </tr>
        @empty
        <tr><td colspan="6" class="text-center py-5 text-muted">Belum ada laporan</td></tr>
        @endforelse
    </x-data-table>

    <div class="mt-3">{{ $reports->links() }}</div>
</div>
@endsection

@push('styles')
<style>
    .small-caps { font-size: 0.7rem; text-transform: uppercase; letter-spacing: 1px; font-weight: 800; color: #6c757d; }
</style>
@endpush