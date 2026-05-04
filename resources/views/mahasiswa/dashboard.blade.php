@extends('layouts.dashboard')

@section('title', 'Dashboard Mahasiswa')

@section('dashboard-content')
<div class="row mb-4">
    <div class="col-md-3 mb-3"><div class="card text-center border-0"><div class="card-body"><i class="fas fa-flag-checkered fa-3x text-orange mb-2"></i><h5>Total Laporan</h5><h2 class="text-orange">{{ $stats['total_reports'] }}</h2></div></div></div>
    <div class="col-md-3 mb-3"><div class="card text-center border-0"><div class="card-body"><i class="fas fa-spinner fa-3x text-warning mb-2"></i><h5>Dalam Proses</h5><h2>{{ $stats['in_progress_reports'] }}</h2></div></div></div>
    <div class="col-md-3 mb-3"><div class="card text-center border-0"><div class="card-body"><i class="fas fa-check-circle fa-3x text-success mb-2"></i><h5>Selesai</h5><h2>{{ $stats['completed_reports'] }}</h2></div></div></div>
    <div class="col-md-3 mb-3"><div class="card text-center border-0"><div class="card-body"><i class="fas fa-clock fa-3x text-danger mb-2"></i><h5>Menunggu</h5><h2>{{ $stats['pending_reports'] }}</h2></div></div></div>
</div>

<div class="card border-0">
    <div class="card-header bg-white"><h5 class="mb-0"><i class="fas fa-history text-orange me-2"></i>Laporan Terbaru Anda</h5></div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead><tr class="table-light"><th>ID</th><th>Judul</th><th>Fasilitas</th><th>Status</th><th>Tanggal</th><th>Aksi</th></tr></thead>
                <tbody>
                    @forelse($recent_reports as $report)
                    <tr><td>#{{ str_pad($report->id, 5, '0', STR_PAD_LEFT) }}</td><td>{{ $report->title }}</td><td>{{ $report->facility->name }}</td><td><x-report-status :status="$report->status" /></td><td>{{ $report->created_at->format('d/m/Y') }}</td><td><a href="{{ route('mahasiswa.reports.show', $report) }}" class="btn btn-sm btn-outline-primary">Detail</a></td></tr>
                    @empty
                    <tr><td colspan="6" class="text-center py-4">Belum ada laporan</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="text-center mt-3"><a href="{{ route('mahasiswa.reports.create') }}" class="btn btn-primary"><i class="fas fa-plus-circle me-1"></i> Buat Laporan Baru</a></div>
    </div>
</div>
@endsection