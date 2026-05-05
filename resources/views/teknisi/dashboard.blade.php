@extends('layouts.dashboard')

@section('title', 'Dashboard Teknisi')

@section('dashboard-content')
    <div class="container-fluid px-0">
        {{-- WELCOME HEADER --}}
        <div class="row mb-4">
            <div class="col-12">
                <div class="card bg-gradient-orange text-white border-0 shadow-sm">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h3 class="fw-bold mb-1">Selamat Datang, {{ Auth::user()->name }}!</h3>
                                <p class="mb-0 opacity-75">Panel Kontrol Teknisi SiRUKA — Monitoring Kerusakan Fasilitas</p>
                            </div>
                            <div class="d-none d-md-block">
                                <i class="fas fa-wrench fa-4x opacity-50"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- STATISTIC CARDS --}}
        <div class="row mb-4">
            <div class="col-md-3 mb-3">
                <div class="card stat-card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <p class="text-muted small fw-bold text-uppercase mb-1">Tugas Aktif</p>
                                <h2 class="fw-bold text-warning mb-0">{{ $stats['active_tasks'] }}</h2>
                            </div>
                            <i class="fas fa-tasks fa-2x text-warning opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card stat-card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <p class="text-muted small fw-bold text-uppercase mb-1">Tugas Selesai</p>
                                <h2 class="fw-bold text-success mb-0">{{ $stats['completed_tasks'] }}</h2>
                            </div>
                            <i class="fas fa-check-circle fa-2x text-success opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card stat-card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <p class="text-muted small fw-bold text-uppercase mb-1">Rata-rata Waktu</p>
                                <h2 class="fw-bold text-info mb-0">0 <small class="fs-6">jam</small></h2>
                            </div>
                            <i class="fas fa-hourglass-half fa-2x text-info opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card stat-card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <p class="text-muted small fw-bold text-uppercase mb-1">Rating Kerja</p>
                                <h2 class="fw-bold text-orange mb-0">0.0 <small class="fs-6">/5</small></h2>
                            </div>
                            <i class="fas fa-star fa-2x text-orange opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- TABLE TUGAS AKTIF --}}
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white py-3 border-0">
                <h5 class="mb-0 fw-bold fs-6">
                    <i class="fas fa-list-ul text-orange me-2"></i>Daftar Tugas Aktif (Antrean)
                </h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-3 py-3 text-uppercase small fw-bold text-muted">No. Laporan</th>
                                <th class="py-3 text-uppercase small fw-bold text-muted">Judul Masalah</th>
                                <th class="py-3 text-uppercase small fw-bold text-muted">Lokasi</th>
                                <th class="py-3 text-uppercase small fw-bold text-muted text-center">Urgensi</th>
                                <th class="py-3 text-uppercase small fw-bold text-muted">SLA Deadline</th>
                                <th class="py-3 text-uppercase small fw-bold text-muted text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($active_tasks as $task)
                                <tr>
                                    <td class="ps-3 fw-bold">#{{ str_pad($task->report_id, 5, '0', STR_PAD_LEFT) }}</td>
                                    <td>
                                        <div class="fw-bold">{{ $task->report->title }}</div>
                                        <small
                                            class="text-muted">{{ $task->report->sla->facility_category ?? 'Kategori' }}</small>
                                    </td>
                                    <td class="small">
                                        <i class="fas fa-map-marker-alt text-danger me-1"></i>
                                        {{ $task->report->room->name ?? 'Ruangan' }}
                                    </td>
                                    <td class="text-center">
                                        @if($task->report->urgency == 'high')
                                            <span class="badge bg-danger px-3 py-1">High</span>
                                        @elseif($task->report->urgency == 'medium')
                                            <span class="badge bg-warning text-dark px-3 py-1">Medium</span>
                                        @else
                                            <span class="badge bg-info text-dark px-3 py-1">Low</span>
                                        @endif
                                    </td>
                                    <td class="small">
                                        <div class="fw-bold {{ $task->report->sla_deadline->isPast() ? 'text-danger' : '' }}">
                                            {{ $task->report->sla_deadline->format('d/m/Y H:i') }}
                                        </div>
                                        <small class="text-muted">{{ $task->report->sla_deadline->diffForHumans() }}</small>
                                    </td>
                                    <td class="text-center">
                                        <a href="{{ route('teknisi.tasks.show', $task->id) }}"
                                            class="btn btn-sm btn-primary px-3 shadow-sm">
                                            <i class="fas fa-wrench me-1"></i> Kerjakan
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-5">
                                        <i class="fas fa-check-circle fa-3x text-light mb-3 d-block"></i>
                                        <span class="text-muted">Tidak ada tugas aktif. Semua pekerjaan sudah selesai!</span>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        /* Gradient Background SiRUKA */
        .bg-gradient-orange {
            background: linear-gradient(135deg, #FF6B35 0%, #FF8C42 100%);
        }

        /* Card Styling */
        .stat-card {
            transition: transform .3s ease;
            border-radius: 12px;
        }

        .stat-card:hover {
            transform: translateY(-5px);
        }

        .text-orange {
            color: #FF6B35 !important;
        }

        /* Table Adjustments */
        .table thead th {
            letter-spacing: 0.5px;
            border-bottom: none;
        }

        .table tbody tr:hover {
            background-color: rgba(255, 107, 53, 0.03);
        }
    </style>
@endpush