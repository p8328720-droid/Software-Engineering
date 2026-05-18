@extends('layouts.dashboard')

@section('title', 'Admin Dashboard')

@section('dashboard-content')
<div class="container-fluid px-0">
    {{-- 1. WELCOME BANNER (IKUT STYLE TEKNISI) --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="card bg-gradient-orange text-white border-0 shadow-sm">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h3 class="fw-bold mb-1">Pusat Kendali Admin</h3>
                            <p class="mb-0 opacity-75">Monitoring sistem SiRUKA secara real-time — {{ now()->format('d F Y') }}</p>
                        </div>
                        <div class="d-none d-md-block text-end">
                            <i class="fas fa-tachometer-alt fa-4x opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- 2. STATS ROW 1: STATUS LAPORAN (IKUT STYLE TEKNISI) --}}
    <div class="row mb-3">
        <div class="col-md-3 mb-3">
            <div class="card stat-card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted small-caps mb-1">Total Laporan</p>
                            <h2 class="fw-bold text-orange mb-0">{{ number_format($stats['total_reports']) }}</h2>
                        </div>
                        <i class="fas fa-flag-checkered fa-2x text-orange opacity-25"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card stat-card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted small-caps mb-1">Pending</p>
                            <h2 class="fw-bold text-warning mb-0">{{ number_format($stats['pending_reports']) }}</h2>
                        </div>
                        <i class="fas fa-clock fa-2x text-warning opacity-25"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card stat-card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted small-caps mb-1">Diproses</p>
                            <h2 class="fw-bold text-info mb-0">{{ number_format($stats['in_progress_reports']) }}</h2>
                        </div>
                        <i class="fas fa-spinner fa-2x text-info opacity-25"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card stat-card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted small-caps mb-1">Selesai</p>
                            <h2 class="fw-bold text-success mb-0">{{ number_format($stats['completed_reports']) }}</h2>
                        </div>
                        <i class="fas fa-check-circle fa-2x text-success opacity-25"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- 3. STATS ROW 2: SYSTEM & SLA METRICS --}}
    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <div class="card stat-card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <div>
                            <p class="text-muted small-caps mb-1">Total Users</p>
                            <h2 class="fw-bold text-primary mb-0">{{ number_format($stats['total_users']) }}</h2>
                        </div>
                        <i class="fas fa-users fa-2x text-primary opacity-25"></i>
                    </div>
                    <small class="text-muted d-block" style="font-size: 10px;">P: {{ number_format($stats['total_students']) }} | T: {{ number_format($stats['total_technicians']) }}</small>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card stat-card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted small-caps mb-1">Total Ruangan</p>
                            <h2 class="fw-bold text-success mb-0">{{ number_format($stats['total_rooms']) }}</h2>
                        </div>
                        <i class="fas fa-building fa-2x text-success opacity-25"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card stat-card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted small-caps mb-1">SLA Violation</p>
                            <h2 class="fw-bold text-danger mb-0">{{ number_format($stats['sla_violations']) }}</h2>
                        </div>
                        <i class="fas fa-exclamation-triangle fa-2x text-danger opacity-25"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card stat-card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted small-caps mb-1">Kepatuhan SLA</p>
                            @php
                                $compliance = $stats['total_reports'] > 0
                                    ? round((($stats['total_reports'] - $stats['sla_violations']) / $stats['total_reports']) * 100)
                                    : 100;
                            @endphp
                            <h2 class="fw-bold text-info mb-0">{{ $compliance }}<small class="fs-6">%</small></h2>
                        </div>
                        <i class="fas fa-chart-line fa-2x text-info opacity-25"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- 4. CHARTS SECTION --}}
    <div class="row mb-4">
        <div class="col-md-6 mb-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white py-3 border-0">
                    <h6 class="text-white mb-0 fw-bold small-caps"><i class="fas fa-chart-pie text-orange me-2"></i>Status Laporan</h6>
                </div>
                <div class="card-body">
                    <canvas id="statusChart" height="250"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-6 mb-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white py-3 border-0">
                    <h6 class="text-white mb-0 fw-bold small-caps"><i class="fas fa-chart-bar text-orange me-2"></i>Top 5 Ruangan Bermasalah</h6>
                </div>
                <div class="card-body">
                    <canvas id="roomChart" height="250"></canvas>
                </div>
            </div>
        </div>
    </div>

    {{-- 5. RECENT REPORTS TABLE --}}
    <x-data-table>
        <x-slot:header>
            <div class="d-flex justify-content-between align-items-center">
                <h6 class="mb-0 fw-bold small-caps"><i class="fas fa-list-alt text-orange me-2"></i>Laporan Terbaru</h6>
                <a href="{{ route('admin.reports.index') }}" class="btn btn-sm btn-outline-primary fw-bold" style="font-size: 11px;">LIHAT SEMUA</a>
            </div>
        </x-slot:header>
        <x-slot:thead>
            <th class="ps-3 py-3 small-caps">ID</th>
            <th class="py-3 small-caps">Pelapor</th>
            <th class="py-3 small-caps">Ruangan</th>
            <th class="py-3 small-caps text-center">Status</th>
            <th class="py-3 small-caps text-center">SLA</th>
            <th class="py-3 small-caps text-center">Aksi</th>
        </x-slot:thead>
        @forelse($recent_reports as $report)
            <tr>
                <td class="ps-3 fw-bold text-dark">#{{ str_pad($report->id, 5, '0', STR_PAD_LEFT) }}</td>
                <td class="small">{{ $report->reporter->name ?? 'N/A' }}</td>
                <td class="small">{{ $report->room->name ?? 'N/A' }}</td>
                <td class="text-center"><x-report-status :status="$report->status" /></td>
                <td class="text-center small">
                    @if($report->sla_deadline < now() && $report->status != 'completed')
                        <span class="text-danger fw-bold"><i class="fas fa-exclamation-circle"></i> Terlambat</span>
                    @else
                        <span class="text-success fw-bold"><i class="fas fa-check-circle"></i> On Track</span>
                    @endif
                </td>
                <td class="text-center">
                    <a href="{{ route('admin.reports.show', $report->id) }}" class="btn btn-sm btn-light border p-1 px-2">
                        <i class="fas fa-eye small text-muted"></i>
                    </a>
                </td>
            </tr>
        @empty
            <tr><td colspan="6" class="text-center py-4 small text-muted">Belum ada laporan masuk.</td></tr>
        @endforelse
    </x-data-table>

    {{-- 6. RECENT USERS TABLE --}}
    <x-data-table>
        <x-slot:header>
            <div class="d-flex justify-content-between align-items-center">
                <h6 class="mb-0 fw-bold small-caps"><i class="fas fa-users text-orange me-2"></i>User Terbaru</h6>
                <a href="{{ route('admin.users.index') }}" class="btn btn-sm btn-outline-primary fw-bold" style="font-size: 11px;">KELOLA USER</a>
            </div>
        </x-slot:header>
        <x-slot:thead>
            <th class="ps-3 py-3 small-caps">Nama</th>
            <th class="py-3 small-caps">Role</th>
            <th class="py-3 small-caps">Tgl Daftar</th>
            <th class="py-3 small-caps text-center">Aksi</th>
        </x-slot:thead>
        @forelse($recent_users as $user)
            <tr>
                <td class="ps-3 small fw-bold">{{ $user->name }}</td>
                <td>
                    @if($user->role == 'admin')<span class="badge bg-danger p-1 px-2" style="font-size: 10px;">ADMIN</span>
                    @elseif($user->role == 'teknisi')<span class="badge bg-info p-1 px-2" style="font-size: 10px;">TEKNISI</span>
                    @else<span class="badge bg-success p-1 px-2" style="font-size: 10px;">PELAPOR</span>@endif
                </td>
                <td class="small">{{ $user->created_at->format('d/m/Y') }}</td>
                <td class="text-center">
                    <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-sm btn-light border p-1 px-2">
                        <i class="fas fa-edit small text-muted"></i>
                    </a>
                </td>
            </tr>
        @empty
            <tr><td colspan="4" class="text-center py-4 small text-muted">Belum ada user baru.</td></tr>
        @endforelse
    </x-data-table>
</div>
@endsection

@push('styles')
<style>
    .bg-gradient-orange { background: linear-gradient(135deg, #FF6B35 0%, #FF8C42 100%); }
    .stat-card { transition: transform 0.3s ease; border-radius: 12px; }
    .stat-card:hover { transform: translateY(-5px); }
    .text-orange { color: #FF6B35 !important; }
    .small-caps { font-size: 0.65rem; text-transform: uppercase; letter-spacing: 1px; font-weight: 800; color: #6c757d; }
    .table-hover tbody tr:hover { background-color: rgba(255, 107, 53, 0.03); }
    .card { border-radius: 12px; }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    new Chart(document.getElementById('statusChart'), {
        type: 'doughnut',
        data: {
            labels: ['Pending', 'Diproses', 'Selesai'],
            datasets: [{
                data: [{{ $stats['pending_reports'] }}, {{ $stats['in_progress_reports'] }}, {{ $stats['completed_reports'] }}],
                backgroundColor: ['#ffc107', '#17a2b8', '#28a745'],
                borderWidth: 0
            }]
        },
        options: { responsive: true, plugins: { legend: { position: 'bottom', labels: { boxWidth: 12, font: { size: 11 } } } } }
    });

    new Chart(document.getElementById('roomChart'), {
        type: 'bar',
        data: {
            labels: @json($reports_by_room->pluck('name')),
            datasets: [{
                label: 'Laporan',
                data: @json($reports_by_room->pluck('reports_count')),
                backgroundColor: '#FF6B35',
                borderRadius: 5
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } },
            scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } }, x: { ticks: { font: { size: 10 } } } }
        }
    });
</script>
@endpush