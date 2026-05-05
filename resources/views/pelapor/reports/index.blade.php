@extends('layouts.dashboard')

@section('title', 'Daftar Laporan')

@section('dashboard-content')
<div class="card border-0">
    <div class="card-header bg-white d-flex justify-content-between align-items-center"><h5 class="mb-0"><i class="fas fa-list text-orange me-2"></i>Daftar Laporan Saya</h5><a href="{{ route('pelapor.reports.create') }}" class="btn btn-sm btn-primary"><i class="fas fa-plus"></i> Buat Laporan</a></div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead><tr class="table-light"><th>ID</th><th>Judul</th><th>Ruangan</th><th>Status</th><th>Tanggal</th><th>Aksi</th></tr></thead>
                <tbody>
                    @forelse($reports as $report)
                    <tr><td>#{{ str_pad($report->id, 5, '0', STR_PAD_LEFT) }}</td><td>{{ $report->title }}</td><td>{{ $report->room->name }}</td><td><x-report-status :status="$report->status" /></td><td>{{ $report->created_at->format('d/m/Y') }}</td><td><a href="{{ route('pelapor.reports.show', $report) }}" class="btn btn-sm btn-outline-primary">Detail</a></td></tr>
                    @empty
                    <tr><td colspan="6" class="text-center py-4">Belum ada laporan</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-4">{{ $reports->links() }}</div>
    </div>
</div>
@endsection