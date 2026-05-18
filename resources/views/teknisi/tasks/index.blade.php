@extends('layouts.dashboard')

@section('title', 'Daftar Tugas')

@section('dashboard-content')
<div class="container-fluid px-0">
    {{-- PAGE TITLE --}}
    <div class="d-flex justify-content-between flex-wrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h4 fw-bold text-dark"><i class="fas fa-clipboard-list text-orange me-2"></i>Manajemen Tugas Teknisi</h1>
    </div>

    {{-- TAB NAVIGATION --}}
    <ul class="nav nav-tabs mb-4 border-0" id="taskTab" role="tablist">
        <li class="nav-item">
            <button class="nav-link active fw-bold px-4" data-bs-toggle="tab" data-bs-target="#active" type="button">
                Tugas Aktif <span class="badge rounded-pill bg-danger ms-1">{{ $activeTasks->count() }}</span>
            </button>
        </li>
        <li class="nav-item">
            <button class="nav-link fw-bold px-4" data-bs-toggle="tab" data-bs-target="#completed" type="button">
                Tugas Selesai <span class="badge rounded-pill bg-secondary ms-1 text-white">{{ $completedTasks->count() }}</span>
            </button>
        </li>
    </ul>

    <div class="tab-content" id="taskTabContent">
        {{-- SECTION TUGAS AKTIF --}}
        <div class="tab-pane fade show active" id="active">
            <x-data-table>
                <x-slot:header>
                    <div class="d-flex justify-content-between align-items-center">
                        <h6 class="mb-0 fw-bold">
                            <i class="fas fa-tools text-orange me-2"></i>Antrean Tugas Perbaikan
                        </h6>
                    </div>
                </x-slot:header>
                <x-slot:thead>
                    <th class="ps-3 py-3 small-caps">No. Laporan</th>
                    <th class="py-3 small-caps">Masalah & Kategori</th>
                    <th class="py-3 small-caps">Lokasi</th>
                    <th class="py-3 small-caps text-center">Urgensi</th>
                    <th class="py-3 small-caps">SLA Deadline</th>
                    <th class="py-3 small-caps text-center">Aksi</th>
                </x-slot:thead>
                @forelse($activeTasks as $task)
                    <tr>
                        <td class="ps-3 fw-bold text-primary">#{{ str_pad($task->report_id, 5, '0', STR_PAD_LEFT) }}</td>
                        <td>
                            <div class="fw-bold small">{{ $task->report->title }}</div>
                            <small class="text-muted" style="font-size: 11px;">{{ $task->report->sla->facility_category ?? '-' }}</small>
                        </td>
                        <td class="small">
                            {{ $task->report->room->name ?? 'N/A' }}
                        </td>
                        <td class="text-center">
                            @if($task->report->urgency == 'high')
                                <span class="badge bg-danger px-3 py-1 fw-bold" style="font-size: 10px;">HIGH</span>
                            @elseif($task->report->urgency == 'medium')
                                <span class="badge bg-warning text-dark px-3 py-1 fw-bold" style="font-size: 10px;">MEDIUM</span>
                            @else
                                <span class="badge bg-info text-dark px-3 py-1 fw-bold" style="font-size: 10px;">LOW</span>
                            @endif
                        </td>
                        <td>
                            <div class="small fw-bold {{ $task->report->sla_deadline->isPast() ? 'text-danger' : '' }}">
                                {{ $task->report->sla_deadline->format('d/m/Y H:i') }}
                            </div>
                            <small class="text-muted" style="font-size: 10px;">{{ $task->report->sla_deadline->diffForHumans() }}</small>
                        </td>
                        <td class="text-center">
                            <a href="{{ route('teknisi.tasks.show', $task->id) }}" class="btn btn-sm btn-primary fw-bold shadow-sm">
                                Kerjakan
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-5 text-muted small">Tidak ada tugas aktif.</td>
                    </tr>
                @endforelse
            </x-data-table>
        </div>

        {{-- SECTION TUGAS SELESAI --}}
        <div class="tab-pane fade" id="completed">
            <x-data-table>
                <x-slot:header>
                    <h6 class="mb-0 fw-bold">
                        Riwayat Pekerjaan Selesai
                    </h6>
                </x-slot:header>
                <x-slot:thead>
                    <th class="ps-3 py-3 small-caps">No. Laporan</th>
                    <th class="py-3 small-caps">Judul Laporan</th>
                    <th class="py-3 small-caps">Ruangan</th>
                    <th class="py-3 small-caps text-center">Status</th>
                    <th class="py-3 small-caps">Waktu Selesai</th>
                    <th class="py-3 small-caps text-center">Aksi</th>
                </x-slot:thead>
                @forelse($completedTasks as $task)
                    <tr>
                        <td class="ps-3">#{{ str_pad($task->report_id, 5, '0', STR_PAD_LEFT) }}</td>
                        <td>
                            <div class="fw-bold small">{{ $task->report->title }}</div>
                            <small class="text-muted" style="font-size: 11px;">{{ $task->report->sla->facility_category ?? '-' }}</small>
                        </td>
                        <td class="small">{{ $task->report->room->name ?? 'N/A' }}</td>
                        <td class="text-center">
                            <span class="badge bg-success-light text-success px-3 py-1 fw-bold" style="font-size: 10px;">COMPLETED</span>
                        </td>
                        <td class="small">
                            <div class="fw-bold text-dark">{{ $task->completed_at ? $task->completed_at->format('d M Y') : '-' }}</div>
                            <small class="text-muted">{{ $task->completed_at ? $task->completed_at->format('H:i') : '' }} WIB</small>
                        </td>
                        <td class="text-center">
                            <a href="{{ route('teknisi.tasks.show', $task->id) }}" class="btn btn-sm btn-light border p-1 px-2 fw-bold">
                                Detail
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-5 text-muted small">Belum ada riwayat tugas yang diselesaikan.</td>
                    </tr>
                @endforelse
            </x-data-table>
        </div>
@endsection

@push('styles')
<style>
    /* TYPOGRAPHY & COLORS KONSISTEN */
    .small-caps { font-size: 0.65rem; text-transform: uppercase; letter-spacing: 1px; font-weight: 800; color: #6c757d; }
    .text-orange { color: #FF6B35 !important; }
    .bg-success-light { background-color: rgba(40, 167, 69, 0.1); }
    
    /* NAV TABS */
    .nav-tabs .nav-link { color: #6c757d; border: none; padding: 12px 20px; border-bottom: 3px solid transparent; transition: 0.3s; }
    .nav-tabs .nav-link:hover { color: #FF6B35; }
    .nav-tabs .nav-link.active { color: #FF6B35; border-bottom: 3px solid #FF6B35; background: transparent; }
    
    /* TABLE HOVER */
    .table-hover tbody tr:hover { background-color: rgba(255, 107, 53, 0.03); }
    .card { border-radius: 12px; }
    
    /* BUTTON OVERRIDE */
    .btn-primary { background-color: #FF6B35; border-color: #FF6B35; }
    .btn-primary:hover { background-color: #e55a2b; border-color: #e55a2b; }
</style>
@endpush