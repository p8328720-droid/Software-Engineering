@extends('layouts.dashboard')

@section('title', 'Admin Dashboard')

@section('dashboard-content')
<div class="d-flex justify-content-between flex-wrap align-items-center pt-3 pb-2 mb-4 border-bottom">
    <h1 class="h2"><i class="fas fa-tachometer-alt me-2 text-orange"></i>Dashboard Admin</h1>
    <div class="btn-toolbar">
        <span class="text-muted">{{ now()->format('d F Y') }}</span>
    </div>
</div>

<div class="row mb-4">
    <div class="col-md-3 mb-3">
        <div class="card stat-card border-0 h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted mb-0">Total Laporan</p>
                        <h2 class="mb-0 text-orange">{{ number_format($stats['total_reports']) }}</h2>
                    </div>
                    <div><i class="fas fa-flag-checkered fa-3x text-orange-light"></i></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card stat-card border-0 h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted mb-0">Pending</p>
                        <h2 class="mb-0 text-warning">{{ number_format($stats['pending_reports']) }}</h2>
                    </div>
                    <div><i class="fas fa-clock fa-3x text-warning"></i></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card stat-card border-0 h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted mb-0">Diproses</p>
                        <h2 class="mb-0 text-info">{{ number_format($stats['in_progress_reports']) }}</h2>
                    </div>
                    <div><i class="fas fa-spinner fa-3x text-info"></i></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card stat-card border-0 h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted mb-0">Selesai</p>
                        <h2 class="mb-0 text-success">{{ number_format($stats['completed_reports']) }}</h2>
                    </div>
                    <div><i class="fas fa-check-circle fa-3x text-success"></i></div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row mb-4">
    <div class="col-md-3 mb-3">
        <div class="card stat-card border-0 h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted mb-0">Total User</p>
                        <h2 class="mb-0 text-primary">{{ number_format($stats['total_users']) }}</h2>
                    </div>
                    <div><i class="fas fa-users fa-3x text-primary"></i></div>
                </div>
                <small class="text-muted">Mahasiswa: {{ number_format($stats['total_students']) }} | Teknisi: {{ number_format($stats['total_technicians']) }}</small>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card stat-card border-0 h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted mb-0">Total Fasilitas</p>
                        <h2 class="mb-0 text-success">{{ number_format($stats['total_facilities']) }}</h2>
                    </div>
                    <div><i class="fas fa-building fa-3x text-success"></i></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card stat-card border-0 h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted mb-0">SLA Violation</p>
                        <h2 class="mb-0 text-danger">{{ number_format($stats['sla_violations']) }}</h2>
                    </div>
                    <div><i class="fas fa-exclamation-triangle fa-3x text-danger"></i></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card stat-card border-0 h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted mb-0">Kepatuhan SLA</p>
                        @php
                            $compliance = $stats['total_reports'] > 0 
                                ? round((($stats['total_reports'] - $stats['sla_violations']) / $stats['total_reports']) * 100) 
                                : 100;
                        @endphp
                        <h2 class="mb-0 text-info">{{ $compliance }}<small>%</small></h2>
                    </div>
                    <div><i class="fas fa-chart-line fa-3x text-info"></i></div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row mb-4">
    <div class="col-md-6 mb-3">
        <div class="card border-0">
            <div class="card-header bg-white">
                <h5 class="mb-0"><i class="fas fa-chart-pie text-orange me-2"></i>Status Laporan</h5>
            </div>
            <div class="card-body">
                <canvas id="statusChart" height="250"></canvas>
            </div>
        </div>
    </div>
    <div class="col-md-6 mb-3">
        <div class="card border-0">
            <div class="card-header bg-white">
                <h5 class="mb-0"><i class="fas fa-chart-bar text-orange me-2"></i>Top 5 Fasilitas Paling Sering Rusak</h5>
            </div>
            <div class="card-body">
                <canvas id="facilityChart" height="250"></canvas>
            </div>
        </div>
    </div>
</div>

<div class="row mb-4">
    <div class="col-12">
        <div class="card border-0">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="fas fa-list-alt text-orange me-2"></i>Laporan Terbaru</h5>
                <a href="#" class="btn btn-sm btn-outline-primary">Lihat Semua</a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr class="table-light">
                                <th>ID</th><th>Pelapor</th><th>Fasilitas</th><th>Judul</th><th>Status</th><th>SLA</th><th>Tanggal</th><th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recent_reports as $report)
                            <tr>
                                <td>#{{ str_pad($report->id, 5, '0', STR_PAD_LEFT) }}</td>
                                <td>{{ $report->user->name }}</td>
                                <td>{{ $report->facility->name }}</td>
                                <td>{{ Str::limit($report->title, 30) }}</td>
                                <td><x-report-status :status="$report->status" /></td>
                                <td>
                                    @if($report->sla_deadline < now() && $report->status != 'completed')
                                        <span class="text-danger"><i class="fas fa-exclamation-circle"></i> Terlambat</span>
                                    @else
                                        <span class="text-success"><i class="fas fa-check-circle"></i> On Track</span>
                                    @endif
                                </td>
                                <td>{{ $report->created_at->format('d/m/Y H:i') }}</td>
                                <td><a href="#" class="btn btn-sm btn-outline-primary"><i class="fas fa-eye"></i></a></td>
                            </tr>
                            @empty
                            <tr><td colspan="8" class="text-center py-4">Belum ada laporan</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card border-0">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="fas fa-users text-orange me-2"></i>User Terbaru</h5>
                <a href="{{ route('admin.users') }}" class="btn btn-sm btn-outline-primary">Kelola User</a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr class="table-light">
                                <th>Nama</th><th>Email</th><th>NIM</th><th>Role</th><th>Tanggal Daftar</th><th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recent_users as $user)
                            <tr>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>{{ $user->student_id ?? '-' }}</td>
                                <td>
                                    @if($user->role == 'admin')<span class="badge bg-danger">Admin</span>
                                    @elseif($user->role == 'teknisi')<span class="badge bg-info">Teknisi</span>
                                    @elseif($user->role == 'supervisor')<span class="badge bg-warning">Supervisor</span>
                                    @else<span class="badge bg-success">Mahasiswa</span>@endif
                                </td>
                                <td>{{ $user->created_at->format('d/m/Y') }}</td>
                                <td><a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-sm btn-outline-primary"><i class="fas fa-edit"></i></a></td>
                            </tr>
                            @empty
                            <tr><td colspan="6" class="text-center py-4">Belum ada user</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.stat-card { transition: transform 0.3s ease; border-radius: 15px; }
.stat-card:hover { transform: translateY(-5px); box-shadow: 0 10px 25px rgba(0,0,0,0.1); }
.text-orange { color: #FF6B35; }
.text-orange-light { color: #FF8C42; }
.table-light { background-color: #FFF4E6; }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
new Chart(document.getElementById('statusChart'), {
    type: 'doughnut',
    data: {
        labels: ['Pending', 'Diproses', 'Selesai'],
        datasets: [{ data: [{{ $stats['pending_reports'] }}, {{ $stats['in_progress_reports'] }}, {{ $stats['completed_reports'] }}], backgroundColor: ['#6c757d', '#ffc107', '#28a745'], borderWidth: 0 }]
    },
    options: { responsive: true, plugins: { legend: { position: 'bottom' } } }
});

new Chart(document.getElementById('facilityChart'), {
    type: 'bar',
    data: {
        labels: @json($reports_by_facility->pluck('name')),
        datasets: [{ label: 'Jumlah Laporan', data: @json($reports_by_facility->pluck('reports_count')), backgroundColor: '#FF6B35', borderRadius: 8 }]
    },
    options: { responsive: true, plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } } }
});
</script>
@endpush