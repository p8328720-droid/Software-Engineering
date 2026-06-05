@extends('layouts.dashboard')

@section('title', 'Detail Kinerja Teknisi')

@section('dashboard-content')
<div class="container-fluid px-0">
    <div class="d-flex justify-content-between flex-wrap align-items-center mb-4">
        <h4 class="fw-bold">Detail Kinerja Teknisi</h4>
        <a href="{{ route('admin.monitoring.index') }}" class="btn btn-secondary fw-bold">Kembali</a>
    </div>

    <div class="row">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm text-center">
                <div class="card-body p-4">
                    <img src="{{ $technician->avatar_url }}" class="rounded-circle mb-3" width="100">
                    <h5 class="fw-bold">{{ $technician->name }}</h5>
                    <p class="text-secondary small">{{ $technician->email }}</p>
                    <hr>
                    <div class="row">
                        <div class="col-6">
                            <h5 class="text-success fw-bold">{{ $tasks->whereNotNull('completed_at')->count() }}</h5>
                            <small class="text-secondary small-caps">Selesai</small>
                        </div>
                        <div class="col-6">
                            <h5 class="text-warning fw-bold">{{ $tasks->whereNull('completed_at')->count() }}</h5>
                            <small class="text-secondary small-caps">Aktif</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-8">
            <x-data-table title="Riwayat Tugas">
                        <x-slot:thead>
                            <th class="ps-3 py-3 small-caps">No. Laporan</th>
                            <th class="py-3 small-caps">Judul</th>
                            <th class="py-3 small-caps">Status</th>
                            <th class="py-3 small-caps">Waktu Pengerjaan</th>
                        </x-slot:thead>
                        @forelse($tasks as $task)
                            <tr>
                                <td class="ps-3 fw-bold text-dark">#{{ str_pad($task->report_id, 5, '0', STR_PAD_LEFT) }}</td>
                                <td class="small">{{ $task->report->title }}</td>
                                <td><x-report-status :status="$task->report->status" size="sm" /></td>
                                <td class="small">@if($task->started_at && $task->completed_at){{ $task->started_at->diffInHours($task->completed_at) }} jam @else - @endif</td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="text-center py-4 small text-muted">Belum ada tugas</td></tr>
                        @endforelse
                    </x-data-table>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .small-caps { font-size: 0.7rem; text-transform: uppercase; letter-spacing: 1px; font-weight: 800; color: #6c757d; }
</style>
@endpush
