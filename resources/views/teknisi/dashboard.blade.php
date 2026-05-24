@extends('layouts.teknisi')

@section('title', 'Dashboard Teknisi')

@section('teknisi-content')
<div class="row mb-4">
    <div class="col-12">
        <div class="card bg-gradient-orange text-white border-0">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h3 class="mb-0">Selamat Datang, {{ Auth::user()->name }}!</h3>
                        <p class="mb-0 mt-2">Panel Teknisi SiRUKA</p>
                    </div>
                    <div>
                        <i class="fas fa-wrench fa-4x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Stats Cards -->
<div class="row mb-4">
    <div class="col-md-3 mb-3">
        <div class="card stat-card border-0">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted mb-0">Tugas Aktif</p>
                        <h2 class="text-warning">{{ $stats['active_tasks'] ?? 0 }}</h2>
                    </div>
                    <div>
                        <i class="fas fa-tasks fa-3x text-warning"></i>
                    </div>
                </div>
                <small class="text-muted mt-2 d-block">Perlu ditangani</small>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card stat-card border-0">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted mb-0">Tugas Selesai (Hari Ini)</p>
                        <h2 class="text-success">{{ $stats['completed_tasks'] ?? 0 }}</h2>
                    </div>
                    <div>
                        <i class="fas fa-check-circle fa-3x text-success"></i>
                    </div>
                </div>
                <small class="text-muted mt-2 d-block">Target: Meningkat</small>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card stat-card border-0">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted mb-0">Total Laporan</p>
                        <h2 class="text-info">{{ $stats['total_reports'] ?? 0 }}</h2>
                    </div>
                    <div>
                        <i class="fas fa-chart-line fa-3x text-info"></i>
                    </div>
                </div>
                <small class="text-muted mt-2 d-block">Semua laporan</small>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card stat-card border-0">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted mb-0">Rata-rata Rating</p>
                        <h2 class="text-orange">{{ number_format($avgRating ?? 0, 1) }}</h2>
                        <small>/5</small>
                    </div>
                    <div>
                        <i class="fas fa-star fa-3x text-orange"></i>
                    </div>
                </div>
                <small class="text-muted mt-2 d-block">{{ $ratingStats['total_rated'] ?? 0 }} dari {{ $ratingStats['total_completed'] ?? 0 }} laporan dinilai</small>
            </div>
        </div>
    </div>
</div>

<!-- Rating Distribution Mini -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card border-0">
            <div class="card-header bg-white py-2">
                <small class="text-muted"><i class="fas fa-chart-bar text-orange me-1"></i> Distribusi Rating dari Laporan yang Selesai</small>
            </div>
            <div class="card-body py-2">
                <div class="row text-center">
                    <div class="col-2">
                        <div class="small text-muted">⭐ 1</div>
                        <div class="fw-bold">{{ $ratingStats['rating_distribution'][1] ?? 0 }}</div>
                    </div>
                    <div class="col-2">
                        <div class="small text-muted">⭐⭐ 2</div>
                        <div class="fw-bold">{{ $ratingStats['rating_distribution'][2] ?? 0 }}</div>
                    </div>
                    <div class="col-2">
                        <div class="small text-muted">⭐⭐⭐ 3</div>
                        <div class="fw-bold">{{ $ratingStats['rating_distribution'][3] ?? 0 }}</div>
                    </div>
                    <div class="col-2">
                        <div class="small text-muted">⭐⭐⭐⭐ 4</div>
                        <div class="fw-bold">{{ $ratingStats['rating_distribution'][4] ?? 0 }}</div>
                    </div>
                    <div class="col-2">
                        <div class="small text-muted">⭐⭐⭐⭐⭐ 5</div>
                        <div class="fw-bold">{{ $ratingStats['rating_distribution'][5] ?? 0 }}</div>
                    </div>
                    <div class="col-2">
                        <div class="small text-muted">Belum</div>
                        <div class="fw-bold">{{ ($ratingStats['total_completed'] ?? 0) - ($ratingStats['total_rated'] ?? 0) }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Tugas Aktif Section -->
<div class="card border-0 mb-4">
    <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <h5 class="mb-0"><i class="fas fa-tasks text-orange me-2"></i>Tugas Aktif (Dalam Proses)</h5>
        <a href="{{ route('teknisi.tasks.index') }}" class="btn btn-sm btn-outline-primary">Lihat Semua</a>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr class="table-light">
                        <th>No. Laporan</th>
                        <th>Judul</th>
                        <th>Lokasi</th>
                        <th>Pelapor</th>
                        <th>Urgensi</th>
                        <th>SLA Deadline</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($active_tasks as $task)
                    <tr>
                        <td class="align-middle">#{{ str_pad($task->id, 5, '0', STR_PAD_LEFT) }}</td>
                        <td class="align-middle">{{ Str::limit($task->title, 40) }}</td>
                        <td class="align-middle">{{ Str::limit($task->location_detail, 30) }}</td>
                        <td class="align-middle">{{ $task->user->name }}</td>
                        <td class="align-middle">{!! $task->urgency_badge !!}</td>
                        <td class="align-middle">
                            @if($task->sla_deadline)
                                @if($task->sla_deadline < now())
                                    <span class="text-danger">{{ $task->sla_deadline->format('d/m/Y H:i') }}</span>
                                @else
                                    {{ $task->sla_deadline->format('d/m/Y H:i') }}
                                @endif
                            @else
                                -
                            @endif
                        </td>
                        <td class="align-middle">
                            <a href="{{ route('teknisi.tasks.show', $task) }}" class="btn btn-sm btn-primary">
                                <i class="fas fa-eye"></i> Detail
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-4">
                            <i class="fas fa-check-circle fa-2x text-success mb-2 d-block"></i>
                            Tidak ada tugas aktif
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Tugas Selesai dengan Rating -->
<div class="card border-0">
    <div class="card-header bg-white">
        <h5 class="mb-0"><i class="fas fa-star text-warning me-2"></i>Rating dari Laporan yang Selesai</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr class="table-light">
                        <th>No. Laporan</th>
                        <th>Judul</th>
                        <th>Rating</th>
                        <th>Komentar</th>
                        <th>Tanggal Selesai</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($completedTasks as $task)
                    <tr>
                        <td class="align-middle">#{{ str_pad($task->id, 5, '0', STR_PAD_LEFT) }}</td>
                        <td class="align-middle">{{ Str::limit($task->title, 50) }}</td>
                        <td class="align-middle">
                            @if($task->rating)
                                <div class="d-flex align-items-center">
                                    <div class="me-2">
                                        @for($i = 1; $i <= 5; $i++)
                                            @if($i <= $task->rating)
                                                <i class="fas fa-star text-warning"></i>
                                            @else
                                                <i class="far fa-star text-secondary"></i>
                                            @endif
                                        @endfor
                                    </div>
                                    <span class="badge bg-success">{{ $task->rating }}/5</span>
                                </div>
                            @else
                                <span class="badge bg-secondary">Belum dinilai</span>
                            @endif
                        </td>
                        <td class="align-middle">
                            @if($task->rating_comment)
                                <span class="small text-muted" title="{{ $task->rating_comment }}">
                                    <i class="fas fa-quote-left me-1 text-orange"></i>
                                    {{ Str::limit($task->rating_comment, 60) }}
                                </span>
                            @else
                                <span class="text-muted small">-</span>
                            @endif
                        </td>
                        <td class="align-middle text-nowrap">
                            @if($task->resolved_at)
                                {{ $task->resolved_at->format('d/m/Y') }}
                            @else
                                -
                            @endif
                        </td>
                        <td class="align-middle">
                            <a href="{{ route('teknisi.tasks.show', $task) }}" class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-eye"></i> Detail
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-4">
                            <i class="fas fa-check-circle fa-2x text-success mb-2 d-block"></i>
                            Belum ada tugas selesai dengan rating
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.bg-gradient-orange {
    background: linear-gradient(135deg, #FF6B35 0%, #FF8C42 100%);
}
.stat-card {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    border-radius: 15px;
}
.stat-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.1);
}
.text-orange {
    color: #FF6B35;
}
</style>
@endpush