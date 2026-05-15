@extends('layouts.admin')

@section('title', 'Admin Dashboard')

@section('admin-content')
<div class="d-flex justify-content-between flex-wrap align-items-center pt-3 pb-2 mb-4 border-bottom">
    <h1 class="h2">Dashboard Admin</h1>
    <div>
        <span class="text-muted">{{ now()->format('d F Y') }}</span>
    </div>
</div>

<!-- Row 1: Stats Cards - Status Laporan -->
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

<!-- Row 2: Stats Cards - Total User, Total Ruangan, SLA Violation, Kepatuhan SLA -->
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
                <small class="text-muted">Total Ruangan</small>
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

<!-- Row 3: Additional Stats - Rata-rata Waktu & Rating -->
<div class="row mb-4">
    <div class="col-md-6">
        <div class="card text-center border-0 shadow-sm h-100">
            <div class="card-body">
                <i class="fas fa-clock fa-2x text-info mb-2"></i>
                <h4 class="mb-0">{{ round($avgResolutionTime ?? 0) }} <small>jam</small></h4>
                <small class="text-muted">Rata-rata Waktu Penyelesaian</small>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card text-center border-0 shadow-sm h-100">
            <div class="card-body">
                <i class="fas fa-star fa-2x text-warning mb-2"></i>
                <h4 class="mb-0">{{ number_format($avgRating ?? 0, 1) }} <small>/5</small></h4>
                <small class="text-muted">Rata-rata Rating</small>
            </div>
        </div>
    </div>
</div>

<!-- Row 4: 2 Charts (sejajar: Status Laporan + Komposisi Pengguna) -->
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
                <h5 class="mb-0"><i class="fas fa-chart-bar me-2 text-orange"></i>Komposisi Pengguna</h5>
            </div>
            <div class="card-body">
                <canvas id="userChart" height="250"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Row 5: Top 5 Fasilitas (Full Width Bar Chart) -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0"><i class="fas fa-chart-bar me-2 text-orange"></i>Top 5 Ruangan Bermasalah</h5>
            </div>
            <div class="card-body">
                <canvas id="facilityChart" height="300"></canvas>
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
                                <th>Ruangan</th>
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
        labels: @json($chartStatus['labels']),
        datasets: [{
            data: @json($chartStatus['data']),
            backgroundColor: @json($chartStatus['colors']),
            borderWidth: 0
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: true,
        plugins: { legend: { position: 'bottom' } }
    }
});

// Chart 2: Komposisi Pengguna (Bar)
const userCtx = document.getElementById('userChart').getContext('2d');
new Chart(userCtx, {
    type: 'bar',
    data: {
        labels: @json($chartUsers['labels']),
        datasets: [{
            label: 'Jumlah User',
            data: @json($chartUsers['data']),
            backgroundColor: @json($chartUsers['colors']),
            borderRadius: 8
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: true,
        plugins: { legend: { display: false } },
        scales: {
            y: { beginAtZero: true, ticks: { stepSize: 1 } }
        }
    }
});

// Chart 3: Top 5 Fasilitas (Bar)
const facilityCtx = document.getElementById('facilityChart').getContext('2d');
const facilityLabels = @json($chartFacility['labels']);
const facilityData = @json($chartFacility['data']);

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
        maintainAspectRatio: true,
        plugins: { legend: { display: false } },
        scales: {
            y: { beginAtZero: true, ticks: { stepSize: 1 } }
        }
    }
});
</script>
@endpush