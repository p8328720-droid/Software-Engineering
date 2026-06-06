@extends('layouts.admin')

@section('title', 'Admin Dashboard')

@section('admin-content')
<div class="d-flex justify-content-between flex-wrap align-items-center pt-3 pb-2 mb-4 border-bottom">
    <h1 class="h2">Dashboard Admin</h1>
    <div>
        <span class="text-muted">{{ now()->format('d F Y') }}</span>
    </div>
</div>

<!-- Row 1: Stats Cards (4 kolom sama besar) -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card text-center border-0 shadow-sm h-100">
            <div class="card-body">
                <h3 class="text-warning mb-0">{{ number_format($stats['pending_reports'] ?? 0) }}</h3>
                <small class="text-muted">Pending</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-center border-0 shadow-sm h-100">
            <div class="card-body">
                <h3 class="text-info mb-0">{{ number_format($stats['in_progress_reports'] ?? 0) }}</h3>
                <small class="text-muted">Diproses</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-center border-0 shadow-sm h-100">
            <div class="card-body">
                <h3 class="text-success mb-0">{{ number_format($stats['completed_reports'] ?? 0) }}</h3>
                <small class="text-muted">Selesai</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-center border-0 shadow-sm h-100">
            <div class="card-body">
                <h3 class="text-danger mb-0">{{ number_format($stats['rejected_reports'] ?? 0) }}</h3>
                <small class="text-muted">Ditolak</small>
            </div>
        </div>
    </div>
</div>

<!-- Row 2: Stats Cards (4 kolom sama besar) -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card text-center border-0 shadow-sm h-100">
            <div class="card-body">
                <h3 class="text-primary mb-0">{{ number_format($stats['total_users'] ?? 0) }}</h3>
                <small class="text-muted">Total User</small>
                <div class="small text-muted mt-1">
                    Mhs: {{ number_format($stats['total_students'] ?? 0) }} | Tek: {{ number_format($stats['total_technicians'] ?? 0) }}
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-center border-0 shadow-sm h-100">
            <div class="card-body">
                <h3 class="text-success mb-0">{{ number_format($stats['total_facilities'] ?? 0) }}</h3>
                <small class="text-muted">Total Fasilitas</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-center border-0 shadow-sm h-100">
            <div class="card-body">
                <h3 class="text-danger mb-0">{{ number_format($stats['sla_violations'] ?? 0) }}</h3>
                <small class="text-muted">SLA Violation</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-center border-0 shadow-sm h-100">
            <div class="card-body">
                @php
                    $totalCompleted = $stats['completed_reports'] ?? 0;
                    $slaViolations = $stats['sla_violations'] ?? 0;
                    $compliance = $totalCompleted > 0 ? round((($totalCompleted - $slaViolations) / $totalCompleted) * 100) : 100;
                @endphp
                <h3 class="text-info mb-0">{{ $compliance }}<small>%</small></h3>
                <small class="text-muted">Kepatuhan SLA</small>
            </div>
        </div>
    </div>
</div>

<!-- Row 3: 2 Charts (sejajar: Status Laporan + Tingkat Urgensi) -->
<div class="row mb-4">
    <div class="col-md-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0"><i class="fas fa-chart-pie me-2 text-orange"></i>Status Laporan</h5>
            </div>
            <div class="card-body d-flex justify-content-center">
                <canvas id="statusChart" height="250" width="250"></canvas>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0"><i class="fas fa-chart-pie me-2 text-orange"></i>Tingkat Urgensi Laporan</h5>
            </div>
            <div class="card-body d-flex justify-content-center">
                <canvas id="urgencyChart" height="250" width="250"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Row 4: 2 Charts (sejajar: Top 5 Fasilitas + Distribusi Rating) -->
<div class="row mb-4">
    <div class="col-md-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0"><i class="fas fa-chart-bar me-2 text-orange"></i>Top 5 Fasilitas Bermasalah</h5>
            </div>
            <div class="card-body">
                <canvas id="facilityChart" height="250"></canvas>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0"><i class="fas fa-chart-bar me-2 text-orange"></i>Distribusi Rating</h5>
            </div>
            <div class="card-body">
                <canvas id="ratingChart" height="250"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Row 5: 1 Chart Full Width (Tren Laporan per Bulan) -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0"><i class="fas fa-chart-line me-2 text-orange"></i>Tren Laporan per Bulan</h5>
            </div>
            <div class="card-body">
                <canvas id="trendChart" height="300"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Row 6: Laporan Terbaru Table -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                <h5 class="mb-0"><i class="fas fa-list-alt me-2 text-orange"></i>Laporan Terbaru</h5>
                <a href="{{ route('admin.reports.index') }}" class="btn btn-sm btn-outline-primary">Lihat Semua</a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr class="table-light">
                                <th>ID</th>
                                <th>Pelapor</th>
                                <th>Fasilitas</th>
                                <th>Judul</th>
                                <th>Status</th>
                                <th>Tanggal</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recent_reports as $report)
                            <tr>
                                <td>#{{ str_pad($report->id, 5, '0', STR_PAD_LEFT) }}</td>
                                <td>{{ $report->user->name }}</td>
                                <td>{{ $report->facility->name }}</td>
                                <td>{{ Str::limit($report->title, 30) }}</td>
                                <td>{!! $report->status_badge !!}</td>
                                <td>{{ $report->created_at->format('d/m/Y H:i') }}</td>
                                <td>
                                    <a href="{{ route('admin.reports.edit', $report->id) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center py-4">Belum ada laporan</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Row 7: User Terbaru Table -->
<div class="row">
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                <h5 class="mb-0"><i class="fas fa-users me-2 text-orange"></i>User Terbaru</h5>
                <a href="{{ route('admin.users') }}" class="btn btn-sm btn-outline-primary">Kelola User</a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr class="table-light">
                                <th>Nama</th>
                                <th>Email</th>
                                <th>NIM</th>
                                <th>Role</th>
                                <th>Tanggal Daftar</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recent_users as $user)
                            <tr>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>{{ $user->student_id ?? '-' }}</td>
                                <td>
                                    @if($user->role == 'admin')
                                        <span class="badge bg-danger">Admin</span>
                                    @elseif($user->role == 'teknisi')
                                        <span class="badge bg-info">Teknisi</span>
                                    @else
                                        <span class="badge bg-success">Mahasiswa</span>
                                    @endif
                                </td>
                                <td>{{ $user->created_at->format('d/m/Y') }}</td>
                                <td>
                                    <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center py-4">Belum ada user</td>
                            </tr>
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
.card {
    border-radius: 12px;
    overflow: hidden;
}
.card-header {
    border-bottom: 1px solid #e9ecef;
}
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Chart 1: Status Laporan (Donut)
const statusCtx = document.getElementById('statusChart').getContext('2d');
new Chart(statusCtx, {
    type: 'doughnut',
    data: {
        labels: ['Pending', 'Diproses', 'Selesai', 'Ditolak'],
        datasets: [{
            data: [
                {{ $stats['pending_reports'] ?? 0 }},
                {{ $stats['in_progress_reports'] ?? 0 }},
                {{ $stats['completed_reports'] ?? 0 }},
                {{ $stats['rejected_reports'] ?? 0 }}
            ],
            backgroundColor: ['#6c757d', '#ffc107', '#28a745', '#dc3545'],
            borderWidth: 0
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: { legend: { position: 'bottom' } }
    }
});

// Chart 2: Tingkat Urgensi (Donut)
const urgencyCtx = document.getElementById('urgencyChart').getContext('2d');
new Chart(urgencyCtx, {
    type: 'doughnut',
    data: {
        labels: ['Rendah', 'Sedang', 'Tinggi'],
        datasets: [{
            data: [
                {{ $urgencyData['low'] ?? 0 }},
                {{ $urgencyData['medium'] ?? 0 }},
                {{ $urgencyData['high'] ?? 0 }}
            ],
            backgroundColor: ['#28a745', '#ffc107', '#dc3545'],
            borderWidth: 0
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: { legend: { position: 'bottom' } }
    }
});

// Chart 3: Top 5 Fasilitas (Bar)
const facilityCtx = document.getElementById('facilityChart').getContext('2d');
const facilityLabels = @json($topFacilities->pluck('name'));
const facilityData = @json($topFacilities->pluck('reports_count'));

new Chart(facilityCtx, {
    type: 'bar',
    data: {
        labels: facilityLabels,
        datasets: [{
            label: 'Jumlah Laporan',
            data: facilityData,
            backgroundColor: '#FF6B35',
            borderRadius: 8
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: { legend: { display: false } },
        scales: {
            y: { beginAtZero: true, ticks: { stepSize: 1 } }
        }
    }
});

// Chart 4: Distribusi Rating (Bar)
const ratingCtx = document.getElementById('ratingChart').getContext('2d');
const ratingData = @json($ratingDistribution);

new Chart(ratingCtx, {
    type: 'bar',
    data: {
        labels: ['⭐ 1', '⭐⭐ 2', '⭐⭐⭐ 3', '⭐⭐⭐⭐ 4', '⭐⭐⭐⭐⭐ 5'],
        datasets: [{
            label: 'Jumlah Rating',
            data: ratingData,
            backgroundColor: '#FF8C42',
            borderRadius: 8
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: { legend: { display: false } },
        scales: {
            y: { beginAtZero: true, ticks: { stepSize: 1 } }
        }
    }
});

// Chart 5: Tren Laporan per Bulan (Line)
const trendCtx = document.getElementById('trendChart').getContext('2d');
const trendLabels = @json($monthlyLabels);
const trendData = @json($monthlyData);

new Chart(trendCtx, {
    type: 'line',
    data: {
        labels: trendLabels,
        datasets: [{
            label: 'Jumlah Laporan',
            data: trendData,
            borderColor: '#FF6B35',
            backgroundColor: 'rgba(255, 107, 53, 0.1)',
            tension: 0.4,
            fill: true
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: { legend: { position: 'top' } },
        scales: {
            y: { beginAtZero: true, ticks: { stepSize: 1 } }
        }
    }
});
</script>
@endpush